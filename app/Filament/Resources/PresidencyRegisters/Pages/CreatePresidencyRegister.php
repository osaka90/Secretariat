<?php

namespace App\Filament\Resources\PresidencyRegisters\Pages;

use App\Filament\Resources\PresidencyRegisters\PresidencyRegisterResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePresidencyRegister extends CreateRecord
{
    protected static string $resource = PresidencyRegisterResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category'] = 'presidency';
        if (empty($data['sequential_number'])) {
            $year = !empty($data['registration_date']) ? (int) \Carbon\Carbon::parse($data['registration_date'])->format('Y') : (int) now()->format('Y');
            $data['sequential_number'] = \App\Models\MinisterPublicRegister::getNextSequentialNumber('presidency', $year);
        }
        return $data;
    }
}