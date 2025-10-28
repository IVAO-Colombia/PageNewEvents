<?php

namespace App\Filament\Widgets;

use App\Models\Controller;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class BookingController extends TableWidget
{
    protected static ?int $sort = 30;
    protected int | string | array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
             ->query(fn (): Builder => Controller::query()->latest())
            ->columns([
                TextColumn::make('event.name')
                    ->label('Event'),
                TextColumn::make('vid')
                    ->label('Ivao Vid'),
                TextColumn::make('position')
                    ->label('Frequency'),
                TextColumn::make('start')
                    ->dateTime()
                    ->label('Start Time'),
                TextColumn::make('end')
                    ->dateTime()
                    ->label('End Time'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'bookingsController-' . date('Y-m-d-His'))
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                            ->withColumns([
                                \pxlrbt\FilamentExcel\Columns\Column::make('event.name')
                                    ->heading('Event'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('position')
                                    ->heading('Frequency'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('vid')
                                    ->heading('VID'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('start')
                                    ->heading('Start Time')
                                    ->formatStateUsing(fn($state) => $state ? $state->format('Y-m-d H:i:s') : ''),
                                \pxlrbt\FilamentExcel\Columns\Column::make('end')
                                    ->heading('End Time')
                                    ->formatStateUsing(fn($state) => $state ? $state->format('Y-m-d H:i:s') : ''),
                            ])
                    ])
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn () => 'bookings-controller-' . date('Y-m-d-His'))
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                        ])
                ]),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}
