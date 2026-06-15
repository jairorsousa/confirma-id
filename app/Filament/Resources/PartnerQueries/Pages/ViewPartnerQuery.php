<?php

namespace App\Filament\Resources\PartnerQueries\Pages;

use App\Filament\Resources\PartnerQueries\PartnerQueryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnerQuery extends ViewRecord
{
    protected static string $resource = PartnerQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
