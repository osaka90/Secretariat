<?php

namespace App\Filament\Resources\SecretRegisters\Pages;

use App\Filament\Resources\SecretRegisters\SecretRegisterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSecretRegister extends EditRecord
{
    protected static string $resource = SecretRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}