<?php

namespace App\Filament\Resources\SecretRegisters;

use App\Filament\Resources\BaseRegisterResource;
use App\Filament\Resources\SecretRegisters\Pages\CreateSecretRegister;
use App\Filament\Resources\SecretRegisters\Pages\EditSecretRegister;
use App\Filament\Resources\SecretRegisters\Pages\ListSecretRegisters;

class SecretRegisterResource extends BaseRegisterResource
{
    public static ?string $category = 'secret';

    protected static ?string $navigationLabel = 'السجل المرسلات السرية';

    protected static ?string $modelLabel = 'إدخال السجل';

    protected static ?string $pluralModelLabel = 'السجل المرسلات السرية';

    protected static ?string $slug = 'secret-registers';

    public static function getPages(): array
    {
        return [
            'index'  => ListSecretRegisters::route('/'),
            'create' => CreateSecretRegister::route('/create'),
            'edit'   => EditSecretRegister::route('/{record}/edit'),
        ];
    }
}