<?php

namespace App\Filament\Resources\Verifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VerificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('attempt_number')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('document_type')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('verification_code'),
                DateTimePicker::make('submitted_at'),
                DateTimePicker::make('approved_at'),
                DateTimePicker::make('expires_at'),
            ]);
    }
}
