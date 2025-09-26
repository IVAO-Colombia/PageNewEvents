<?php

namespace App\Filament\Resources\Airports\Pages;

use Filament\Actions;
use App\Models\Airport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Airports\AirportResource;

class ListAirports extends ListRecords
{
    protected static string $resource = AirportResource::class;

    public $csvFile = null;
    const BATCH_SIZE = 200;

    public function mount(): void
    {
        parent::mount();
        set_time_limit(600);
        ini_set('memory_limit', '512M');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(false),
            Actions\Action::make('upload')
                ->visible(fn (): bool => ! $this->hasUploadedCsv())
                ->label('Subir CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('Archivo CSV')
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel'])
                        ->maxSize(15000)
                        ->helperText('Sube un archivo CSV con datos de aeropuertos'),
                ])
                ->action(function (array $data): void {
                    $this->importFromCsv($data['csv_file']);
                }),

            Actions\Action::make('resetUpload')
                ->visible(fn (): bool => $this->hasUploadedCsv())
                ->label('Reactivar Subida CSV')
                ->color('danger')
                ->action(function (): void {
                    $this->resetUploadedCsv();
                }),
        ];
    }

    public function importFromCsv(string $csvFilePath): void
    {
        try {
            Notification::make('import-start')
                ->title('Iniciando importación')
                ->body('Procesando archivo CSV...')
                ->info()
                ->send();

            $path = Storage::path($csvFilePath);
            $this->processCsvFile($path);

            Storage::delete($csvFilePath);

            Storage::disk('local')->put('airports/csv_uploaded', now()->toDateTimeString());

            Notification::make('import-complete')
                ->title('Importación completada')
                ->success()
                ->send();

            $this->redirect(request()->fullUrl());

        } catch (\Exception $e) {
            Notification::make('import-error')
                ->title('Error en la importación')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function processCsvFile(string $path): void
    {
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle, 1000, ',');
        $headers = array_map('strtolower', $headers);

        $totalImported = 0;
        $currentBatch = [];
        $batchCount = 0;

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($headers) !== count($data)) {
                    continue;
                }

                $rowData = array_combine($headers, $data);
                $rowData = array_map(function ($value) {
                    if ($value === '' || $value === '\N' || $value === '?') {
                        return null;
                    }
                    return $value;
                }, $rowData);

                $airportData = [
                    'ident' => $rowData['ident'] ?? ('UNKNOWN-' . uniqid()),
                    'name_airport' => $rowData['name_airport'] ?? 'Unnamed Airport',
                    'city' => $rowData['city'] ?? 'Unknown',
                    'country' => $rowData['country'] ?? 'Unknown',
                    'iata_code' => $rowData['iata_code'] ?? null,
                    'icao_code' => $rowData['icao_code'] ?? null,
                    'latitude_deg' => $rowData['latitude_deg'] ?? null,
                    'longitude_deg' => $rowData['longitude_deg'] ?? null,
                    'elevation_ft' => $rowData['elevation_ft'] ?? null,
                    'timezone' => $rowData['timezone'] ?? null,
                    'dst' => $rowData['dst'] ?? null,
                    'tz_database_time_zone' => $rowData['tz_database_time_zone'] ?? null,
                    'type' => $rowData['type'] ?? null,
                    'source' => $rowData['source'] ?? 'csv_import',
                ];


                Airport::updateOrCreate(
                    ['ident' => $airportData['ident']],
                    $airportData
                );

                $totalImported++;


                if ($totalImported % 100 === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }

            DB::commit();
            fclose($handle);

            Notification::make('import-summary')
                ->title("Importación exitosa")
                ->body("Se importaron $totalImported aeropuertos correctamente")
                ->success()
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            throw $e;
        }
    }

    protected function hasUploadedCsv(): bool
    {
        return Storage::disk('local')->exists('airports/csv_uploaded');
    }

    public function resetUploadedCsv(): void
    {
        Storage::disk('local')->delete('airports/csv_uploaded');

        Notification::make('reset-upload')
            ->title('Marcador eliminado')
            ->body('El botón "Subir CSV" ha sido reactivado. Recarga si es necesario.')
            ->success()
            ->send();

        $this->redirect(request()->fullUrl());
    }
}
