<?php

namespace App\Filament\Resources\Links\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('الاسم'),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'link' => 'رابط',
                        'folder' => 'مجلد',
                    ])
                    ->required()
                    ->default('link')
                    ->label('النوع')
                    ->reactive(),
                TextInput::make('url')
                    ->url()
                    ->label('الرابط')
                    ->visible(fn ($get) => $get('type') === 'link'),
                \Filament\Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->label('المجلد الأب'),
            ]);
    }
}
