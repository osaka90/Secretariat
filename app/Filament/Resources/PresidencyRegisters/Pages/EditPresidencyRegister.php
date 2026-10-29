<?php

namespace App\Filament\Resources\PresidencyRegisters\Pages;

use App\Filament\Resources\PresidencyRegisters\PresidencyRegisterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPresidencyRegister extends EditRecord
{
    protected static string $resource = PresidencyRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}