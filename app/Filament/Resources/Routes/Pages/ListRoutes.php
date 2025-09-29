<?php

namespace App\Filament\Resources\Routes\Pages;

use App\Filament\Resources\Routes\RouteResource;
use App\Models\Airport;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class ListRoutes extends ListRecords
{
    protected static string $resource = RouteResource::class;

    public function mount(): void
    {
        parent::mount();
        set_time_limit(600);
        ini_set('memory_limit', '512M');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(false),
            Action::make('upload')
            ->label('Upload Excel')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('success')
            ->form([
                Select::make('event_id')
                    ->label('Event')
                    ->options(function () {
                        return DB::table('events')->pluck('name', 'id')->toArray();
                    })
                    ->required(),
                FileUpload::make('excel_file')
                    ->label('Excel File')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                    ->maxSize(15000)
                    ->helperText('Upload an Excel file with route data'),
            ])
            ->action(function (array $data): void {
                $filepath = $data['excel_file'];

                $collection = Excel::toCollection(null, Storage::path($filepath));
                $rows = $collection->first();

                DB::beginTransaction();
                try {
                    foreach ($rows as $index => $row) {
                        if ($index === 0) {
                            continue;
                        }

                        $col = fn($i) => $row[$i] ?? null;

                        $parseTime = function ($value) {
                            if ($value === null || $value === '') {
                                return null;
                            }
                            if (is_numeric($value)) {
                                try {
                                    return ExcelDate::excelToDateTimeObject((float) $value)->format('H:i:s');
                                } catch (\Throwable $e) {
                                    return null;
                                }
                            }
                            $v = trim((string) $value);
                            foreach (['H:i:s', 'H:i'] as $fmt) {
                                try {
                                    return Carbon::createFromFormat($fmt, $v)->format('H:i:s');
                                } catch (\Throwable $e) {
                                }
                            }
                            return null;
                        };

                        $hourOrigin = $parseTime($col(0)) ?? '00:00:00';
                        $hourDestination = $parseTime($col(1)) ?? '00:00:00';

                        $originAiport = Airport::where('icao_code',$row['4'])->first();
                        $destinationAiport = Airport::where('icao_code',$row['5'])->first();

                        Route::Create([
                            'event_id' => $data['event_id'],
                            'hourOrigin' => $hourOrigin,
                            'hourDestination' => $hourDestination,
                            'flight_number' => $row['2'],
                            'airline' => $row['3'],
                            'origin' => $row['4'],
                            'name_airport_origin' => $originAiport ? $originAiport->name_airport : null,
                            'longitude_origin' => $originAiport ? $originAiport->longitude_deg : null,
                            'latitude_origin' => $originAiport ? $originAiport->latitude_deg : null,
                            'iato_code_origin' => $originAiport ? $originAiport->iata_code : null,
                            'gate_origin' => $row['6'],
                            'destination' => $row['5'],
                            'name_airport_destination' => $destinationAiport ? $destinationAiport->name_airport : null,
                            'longitude_destination' => $destinationAiport ? $destinationAiport->longitude_deg : null,
                            'latitude_destination' => $destinationAiport ? $destinationAiport->latitude_deg : null,
                            'iato_code_destination' => $destinationAiport ? $destinationAiport->iata_code : null,
                            'gate_destination' => $row['7'],
                            'aircraft_type' => $row['12'],
                            'type' => in_array(strtolower($row[8]), ['arrival', 'departure'])
                                        ? ucfirst(strtolower($row[8]))
                                        : 'none',

                            'is_commercial' => filter_var($row[9], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,

                            'is_international' => filter_var($row[10], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,

                            'is_cargo' => filter_var($row[11], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
                            'is_active' => true,
                        ]);

                        DB::commit();

                        Storage::delete($filepath);
                        
                        Notification::make()
                            ->title('Routes imported successfully')
                            ->success()
                            ->send();
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    Notification::make()
                        ->title('Error importing routes: ' . $e->getMessage())
                        ->danger()
                        ->send();
                    return;
                }


            }),
        ];

    }
}
