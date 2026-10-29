<?php

namespace App\Filament\Resources\PresidencyRegisters;

use App\Filament\Resources\BaseRegisterResource;
use App\Filament\Resources\PresidencyRegisters\Pages\CreatePresidencyRegister;
use App\Filament\Resources\PresidencyRegisters\Pages\EditPresidencyRegister;
use App\Filament\Resources\PresidencyRegisters\Pages\ListPresidencyRegisters;

class PresidencyRegisterResource extends BaseRegisterResource
{
    public static ?string $category = 'presidency';

    protected static ?string $navigationLabel = 'سجل رئاسة النيابة العامة و السلطة القضائية';

    protected static ?string $modelLabel = 'إدخال السجل';

    protected static ?string $pluralModelLabel = 'سجل رئاسة النيابة العامة و السلطة القضائية';

    protected static ?string $slug = 'presidency-registers';

    public static function getPages(): array
    {
        return [
            'index'  => ListPresidencyRegisters::route('/'),
            'create' => CreatePresidencyRegister::route('/create'),
            'edit'   => EditPresidencyRegister::route('/{record}/edit'),
        ];
    }
}