<?php

namespace App\Filament\Resources\PartnerQueries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartnerQueryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('partner_id')
                    ->relationship('partner', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('query_type')
                    ->required(),
                TextInput::make('queried_term_hash')
                    ->required(),
                TextInput::make('queried_term_masked'),
                TextInput::make('result')
                    ->required(),
                TextInput::make('ip_address'),
                TextInput::make('origin'),
                TextInput::make('credential_label'),
            ]);
    }
}
