<?php

namespace App\Filament\Resources\JusticeMinisterRegisters\Pages;

use App\Filament\Resources\JusticeMinisterRegisters\JusticeMinisterRegisterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJusticeMinisterRegister extends CreateRecord
{
    protected static string $resource = JusticeMinisterRegisterResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category'] = 'justice_minister';
        if (empty($data['sequential_number'])) {
            $year = !empty($data['registration_date']) ? (int) \Carbon\Carbon::parse($data['registration_date'])->format('Y') : (int) now()->format('Y');
            $data['sequential_number'] = \App\Models\MinisterPublicRegister::getNextSequentialNumber('justice_minister', $year);
        }
        return $data;
    }
}