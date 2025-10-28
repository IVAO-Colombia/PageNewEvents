<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Booking;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;


class Bookings extends TableWidget
{
    protected static ?int $sort = 20;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Booking::query()->latest())
            ->columns([
                TextColumn::make('event.name')
                    ->label('Event'),
                TextColumn::make('Callsign')
                    ->label('Callsign'),
                TextColumn::make('Departure')
                    ->label('Departure'),
                TextColumn::make('Arrival')
                    ->label('Arrival'),
                TextColumn::make('VID')
                    ->label('VID'),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Departure')
                    ->options(fn() => Booking::distinct()->pluck('Departure', 'Departure')->toArray())
                    ->searchable(),
                SelectFilter::make('Arrival')
                    ->options(fn() => Booking::distinct()->pluck('Arrival', 'Arrival')->toArray())
                    ->searchable(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'bookings-' . date('Y-m-d-His'))
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                            ->withColumns([
                                \pxlrbt\FilamentExcel\Columns\Column::make('event.name')
                                    ->heading('Event'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('Callsign')
                                    ->heading('Callsign'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('Departure')
                                    ->heading('Departure'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('Arrival')
                                    ->heading('Arrival'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('VID')
                                    ->heading('VID'),
                            ])
                    ])
            ])
            ->recordActions([

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn () => 'bookings-selected-' . date('Y-m-d-His'))
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                        ])
                ]),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}
