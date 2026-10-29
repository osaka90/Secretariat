<?php

namespace App\Filament\Resources\CircularsRegisters\Pages;

use App\Filament\Resources\CircularsRegisters\CircularsRegisterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCircularsRegisters extends ListRecords
{
    protected static string $resource = CircularsRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_register')
                ->label('استخراج السجل')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\TextInput::make('statistics_number')
                        ->label('رقم الاحصاء')
                        ->placeholder('مثال: 123/2025')
                        ->helperText('الصيغة: رقم/سنة')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('start_date')
                        ->label('تاريخ البداية')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('end_date')
                        ->label('تاريخ النهاية')
                        ->required(),
                    \Filament\Forms\Components\Select::make('format')
                        ->label('صيغة الاستخراج')
                        ->options([
                            'html' => 'صفحة طباعة (Print / PDF)',
                            'excel' => 'ملف إكسيل (Excel)',
                        ])
                        ->default('html')
                        ->required(),
                ])
                ->action(fn (array $data) => redirect()->route('print.minister_public_registers', array_merge($data, ['category' => 'circulars']))),

            CreateAction::make(),
        ];
    }
}