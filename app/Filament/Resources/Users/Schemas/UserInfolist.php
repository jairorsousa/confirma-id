<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Support\SensitiveData;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Conta')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nome'),
                                TextEntry::make('email')
                                    ->label('E-mail')
                                    ->formatStateUsing(fn (?string $state): string => SensitiveData::email($state)),
                                TextEntry::make('account_status')
                                    ->label('Status')
                                    ->badge(),
                                TextEntry::make('roles.name')
                                    ->label('Perfis')
                                    ->badge(),
                                TextEntry::make('email_verified_at')
                                    ->label('E-mail verificado em')
                                    ->dateTime()
                                    ->placeholder('-'),
                                TextEntry::make('created_at')
                                    ->label('Criado em')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ]),
                    ]),
                Section::make('Perfil')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('profile.full_name')
                                    ->label('Nome completo')
                                    ->placeholder('-'),
                                TextEntry::make('profile.cpf')
                                    ->label('CPF')
                                    ->formatStateUsing(fn (?string $state): string => SensitiveData::cpf($state))
                                    ->placeholder('-'),
                                TextEntry::make('profile.phone')
                                    ->label('Telefone')
                                    ->formatStateUsing(fn (?string $state): string => SensitiveData::phone($state))
                                    ->placeholder('-'),
                            ]),
                    ]),
            ]);
    }
}
