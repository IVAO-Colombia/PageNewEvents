<?php

namespace App\Filament\Resources\Events\Schemas;

use DragonCode\PrettyArray\Services\File;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use PHPUnit\Framework\TestStatus\Notice;
use App\Models\Airport;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Image Upload')
                ->columnSpanFull()
                ->columns(1)
                ->maxWidth('full')
                ->description('Upload an image for the event')
                ->schema([
                    FileUpload::make('imagen')
                        ->label('Event Image')
                        ->image()
                        ->maxSize(1024) // 1MB
                        ->directory('events/images')
                        ->required()
                        ->columnSpanFull(),
                ]),
                Section::make('Location Details')
                    ->columnSpanFull()
                    ->description('Provide location details for the event')
                    ->columns(3)
                    ->schema([
                        Group::make([
                            TextInput::make('icao_code')
                                ->label('ICAO Airport Code')
                                ->helperText('Enter the ICAO code (e.g., KJFK for John F. Kennedy International Airport)')
                                ->required(),
                            Action::make('searchAirport')
                                ->label('Search Airport')
                                ->icon('heroicon-o-magnifying-glass')
                                ->requiresConfirmation()
                                ->modalDescription(fn (Get $get): string => '¿Want to search for the airport by ICAO code: ' . strtoupper($get('icao_code') ?? 'N/A') . '?')
                                ->action(function (Get $get, Set $set) {
                                    $icao = $get('icao_code');
                                    $airportData = (new self())->getAirportData($icao);
                                    if ($airportData) {
                                        $set('name_airport', $airportData['name']);
                                        $set('longitude', $airportData['longitude']);
                                        $set('latitude', $airportData['latitude']);
                                    } else {
                                        $set('name_airport', null);
                                        $set('longitude', null);
                                        $set('latitude', null);
                                    }
                                }),
                        ])->columnSpan(3),
                        TextInput::make('name_airport')
                            ->label('Airport Name')
                            ->required()
                            ->columnSpan(3)
                            ->readOnly(),
                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->required()
                            ->readOnly(),
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->required()
                            ->readOnly(),
                    ]),

                Section::make('Event Details')
                    ->columnSpanFull()
                    ->description('Provide details about the event')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Event Title')
                            ->required()
                            ->columnSpan(2),
                        RichEditor::make('description')
                            ->label('Event Description')
                            ->required()
                            ->columnSpan(2)
                            ->toolbarButtons([
                                'h2',
                                'h3',
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'link',
                                'undo',
                                'redo',
                            ]),
                        DateTimePicker::make('start_time')
                            ->label('Start Time')
                            ->required(),
                        DateTimePicker::make('end_time')
                            ->label('End Time')
                            ->required(),
                        ]),
            ]);
    }

    public function getAirportData($icao): ? array
    {
        if (empty($icao)) {
            Notification::make()
                ->title('ICAO code is required')
                ->warning()
                ->send();
            return null;
        }
        $airport = Airport::where('icao_code', strtoupper($icao))->first();
        if ($airport) {
            return [
                'name' => $airport->name_airport,
                'longitude' => $airport->longitude_deg,
                'latitude' => $airport->latitude_deg,
            ];
        } else {
            Notification::make()
                ->title('Airport not found')
                ->warning()
                ->send();
            return null;
        }

    }
}
