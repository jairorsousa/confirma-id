<?php

namespace App\Filament\Resources\PartnerQueries\Pages;

use App\Filament\Resources\PartnerQueries\PartnerQueryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartnerQueries extends ListRecords
{
    protected static string $resource = PartnerQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
