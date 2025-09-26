<?php

namespace App\Filament\Resources\Airports;

use App\Filament\Resources\Airports\Pages\CreateAirport;
use App\Filament\Resources\Airports\Pages\EditAirport;
use App\Filament\Resources\Airports\Pages\ListAirports;
use App\Filament\Resources\Airports\Schemas\AirportForm;
use App\Filament\Resources\Airports\Tables\AirportsTable;
use App\Models\Airport;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AirportResource extends Resource
{
    protected static ?string $model = Airport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::GlobeAlt;

    protected static ?string $recordTitleAttribute = 'Airport';


    protected static string | UnitEnum | null $navigationGroup = 'Settings';
    public static function form(Schema $schema): Schema
    {
        return AirportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AirportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAirports::route('/'),
            'create' => CreateAirport::route('/create'),
            'edit' => EditAirport::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $staffPermitions = [
            'CO-WM',
            'CO-AWM',
            'CO-WMA1'
        ];

        return in_array(Auth::user()->rank_ivao, $staffPermitions);
    }

}
