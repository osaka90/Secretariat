<?php

namespace App\Filament\Resources\JusticeMinisterRegisters\Pages;

use App\Filament\Resources\JusticeMinisterRegisters\JusticeMinisterRegisterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJusticeMinisterRegister extends EditRecord
{
    protected static string $resource = JusticeMinisterRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}