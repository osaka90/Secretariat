<?php

namespace App\Filament\Resources\SecretRegisters\Pages;

use App\Filament\Resources\SecretRegisters\SecretRegisterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSecretRegister extends CreateRecord
{
    protected static string $resource = SecretRegisterResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category'] = 'secret';
        if (empty($data['sequential_number'])) {
            $year = !empty($data['registration_date']) ? (int) \Carbon\Carbon::parse($data['registration_date'])->format('Y') : (int) now()->format('Y');
            $data['sequential_number'] = \App\Models\MinisterPublicRegister::getNextSequentialNumber('secret', $year);
        }
        return $data;
    }
}