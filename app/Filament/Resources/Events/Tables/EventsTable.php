<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkAction as ActionsBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;
use Webbingbrasil\FilamentCopyActions\Tables\CopyableTextColumn;


class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagen')
                    ->label('Event Image')
                    ->disk('public')
                    ->circular()
                    ->width(50),
                TextColumn::make('name')
                    ->label('Event Name')
                    ->searchable(),
                TextColumn::make('start_time')
                    ->label('Start Time')
                    ->dateTime(),
                TextColumn::make('end_time')
                    ->label('End Time')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                CopyAction::make('copyImageUrl')
                    ->label('Copy Image URL')
                    ->icon('heroicon-o-clipboard')
                    ->copyable(fn ($record) => asset('storage/' . $record->imagen))
                    ->successNotificationTitle('Image URL copied!')
                    ->color('secondary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                ]),
            ]);
    }


    public static function copyImageUrlsBulkAction()
    {
        return CopyAction::make('copyImageUrls')
            ->label('Copy Image URLs')
            ->icon('heroicon-o-clipboard-document-list')
            ->copyable(function (Collection $records) {
                return $records->map(function ($record) {
                    return asset('storage/' . $record->imagen);
                })->join("\n");
            })
            ->successNotificationTitle('Image URLs copied!')
            ->color('secondary');
    }
}
