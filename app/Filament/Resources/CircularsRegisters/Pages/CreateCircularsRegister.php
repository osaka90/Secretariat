<?php

namespace App\Filament\Resources\CircularsRegisters\Pages;

use App\Filament\Resources\CircularsRegisters\CircularsRegisterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCircularsRegister extends CreateRecord
{
    protected static string $resource = CircularsRegisterResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category'] = 'circulars';
        if (empty($data['sequential_number'])) {
            $year = !empty($data['registration_date']) ? (int) \Carbon\Carbon::parse($data['registration_date'])->format('Y') : (int) now()->format('Y');
            $data['sequential_number'] = \App\Models\MinisterPublicRegister::getNextSequentialNumber('circulars', $year);
        }
        return $data;
    }
}