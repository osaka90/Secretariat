<?php

namespace App\Filament\Resources\CircularsRegisters\Pages;

use App\Filament\Resources\CircularsRegisters\CircularsRegisterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCircularsRegister extends EditRecord
{
    protected static string $resource = CircularsRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}