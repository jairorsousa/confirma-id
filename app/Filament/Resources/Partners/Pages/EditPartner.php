<?php

namespace App\Filament\Resources\Partners\Pages;

use App\Filament\Resources\Partners\PartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function afterSave(): void
    {
        $changedFields = collect(array_keys($this->record->getChanges()))
            ->reject(fn (string $field): bool => in_array($field, ['updated_at', 'api_key_hash'], true))
            ->values()
            ->all();

        if ($changedFields === []) {
            return;
        }

        activity()
            ->performedOn($this->record)
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties([
                'changed_fields' => $changedFields,
            ])
            ->log('partner.updated');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
