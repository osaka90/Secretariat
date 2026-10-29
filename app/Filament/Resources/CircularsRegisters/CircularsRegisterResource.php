<?php

namespace App\Filament\Resources\CircularsRegisters;

use App\Filament\Resources\BaseRegisterResource;
use App\Filament\Resources\CircularsRegisters\Pages\CreateCircularsRegister;
use App\Filament\Resources\CircularsRegisters\Pages\EditCircularsRegister;
use App\Filament\Resources\CircularsRegisters\Pages\ListCircularsRegisters;

class CircularsRegisterResource extends BaseRegisterResource
{
    public static ?string $category = 'circulars';

    protected static ?string $navigationLabel = 'سجل الدوريات و المناشير';

    protected static ?string $modelLabel = 'إدخال السجل';

    protected static ?string $pluralModelLabel = 'سجل الدوريات و المناشير';

    protected static ?string $slug = 'circulars-registers';

    public static function getPages(): array
    {
        return [
            'index'  => ListCircularsRegisters::route('/'),
            'create' => CreateCircularsRegister::route('/create'),
            'edit'   => EditCircularsRegister::route('/{record}/edit'),
        ];
    }
}