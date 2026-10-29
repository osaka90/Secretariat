<?php

namespace App\Filament\Resources\Links\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (\Illuminate\Database\Eloquent\Builder $query) use ($table) {
                $parentId = $table->getLivewire()->parent_id ?? null;
                if ($parentId) {
                    $query->where('parent_id', $parentId);
                } else {
                    $query->whereNull('parent_id');
                }
            })
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->columns([
                \Filament\Tables\Columns\Layout\Stack::make([
                    \Filament\Tables\Columns\IconColumn::make('type')
                        ->icon(fn (string $state): string => match ($state) {
                            'folder' => 'heroicon-s-folder',
                            'link' => 'heroicon-s-link',
                            default => 'heroicon-s-link',
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'folder' => 'warning',
                            'link' => 'info',
                            default => 'primary',
                        })
                        ->size('lg')
                        ->alignCenter(),
                    
                    \Filament\Tables\Columns\TextColumn::make('name')
                        ->label('الاسم')
                        ->weight('bold')
                        ->searchable()
                        ->alignCenter(),

                    \Filament\Tables\Columns\TextColumn::make('url')
                        ->label('الرابط')
                        ->size('sm')
                        ->color('gray')
                        ->searchable()
                        ->alignCenter()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])->space(3),
            ])
            ->filters([
                //
            ])
            ->recordAction('open')
            ->actions([
                \Filament\Actions\Action::make('open')
                    ->label('فتح')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (\App\Models\Link $record): string => $record->type === 'folder' 
                        ? route('filament.admin.resources.links.index', ['parent_id' => $record->id])
                        : ($record->url ?? '#')
                    )
                    ->openUrlInNewTab(fn (\App\Models\Link $record): bool => $record->type === 'link'),
                \Filament\Actions\EditAction::make()
                    ->button()
                    ->color('gray'),
                \Filament\Actions\DeleteAction::make()
                    ->button()
                    ->color('danger'),
            ])
            ->toolbarActions([
                // Remove Bulk Actions as they normally don't work great with Grids out of the box
            ]);
    }
}
