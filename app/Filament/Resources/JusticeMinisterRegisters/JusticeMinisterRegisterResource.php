<?php

namespace App\Filament\Resources\JusticeMinisterRegisters;

use App\Filament\Resources\BaseRegisterResource;
use App\Filament\Resources\JusticeMinisterRegisters\Pages\CreateJusticeMinisterRegister;
use App\Filament\Resources\JusticeMinisterRegisters\Pages\EditJusticeMinisterRegister;
use App\Filament\Resources\JusticeMinisterRegisters\Pages\ListJusticeMinisterRegisters;

class JusticeMinisterRegisterResource extends BaseRegisterResource
{
    public static ?string $category = 'justice_minister';

    protected static ?string $navigationLabel = 'سجل مرسلات وزير العدل و الوكيل العام للملك';

    protected static ?string $modelLabel = 'إدخال السجل';

    protected static ?string $pluralModelLabel = 'سجل مرسلات وزير العدل و الوكيل العام للملك';

    protected static ?string $slug = 'justice-minister-registers';

    public static function getPages(): array
    {
        return [
            'index'  => ListJusticeMinisterRegisters::route('/'),
            'create' => CreateJusticeMinisterRegister::route('/create'),
            'edit'   => EditJusticeMinisterRegister::route('/{record}/edit'),
        ];
    }
}