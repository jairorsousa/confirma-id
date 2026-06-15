<?php

namespace App\Filament\Resources\PartnerQueries;

use App\Filament\Resources\PartnerQueries\Pages\ListPartnerQueries;
use App\Filament\Resources\PartnerQueries\Pages\ViewPartnerQuery;
use App\Filament\Resources\PartnerQueries\Schemas\PartnerQueryForm;
use App\Filament\Resources\PartnerQueries\Schemas\PartnerQueryInfolist;
use App\Filament\Resources\PartnerQueries\Tables\PartnerQueriesTable;
use App\Models\PartnerQuery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartnerQueryResource extends Resource
{
    protected static ?string $model = PartnerQuery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static string|UnitEnum|null $navigationGroup = 'Operacao';

    protected static ?string $navigationLabel = 'Consultas de parceiros';

    protected static ?string $modelLabel = 'consulta de parceiro';

    protected static ?string $pluralModelLabel = 'consultas de parceiros';

    protected static ?int $navigationSort = 20;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PartnerQueryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartnerQueryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnerQueriesTable::configure($table);
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
            'index' => ListPartnerQueries::route('/'),
            'view' => ViewPartnerQuery::route('/{record}'),
        ];
    }
}
