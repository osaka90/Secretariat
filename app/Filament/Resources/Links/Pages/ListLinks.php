<?php

namespace App\Filament\Resources\Links\Pages;

use App\Filament\Resources\Links\LinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use Livewire\Attributes\Url;
use App\Models\Link;
use Filament\Actions\Action;

class ListLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    #[Url]
    public ?int $parent_id = null;

    public function getSubheading(): ?string
    {
        if ($this->parent_id) {
            $parent = Link::find($this->parent_id);
            if ($parent) {
                return "داخل المجلد: " . $parent->name;
            }
        }
        return null;
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if ($this->parent_id) {
            $parent = Link::find($this->parent_id);
            if ($parent) {
                $actions[] = Action::make('goBack')
                    ->label('الرجوع')
                    ->icon('heroicon-m-arrow-right')
                    ->url(route('filament.admin.resources.links.index', ['parent_id' => $parent->parent_id]))
                    ->color('gray');
            }
        }

        $actions[] = CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                if ($this->parent_id && empty($data['parent_id'])) {
                    $data['parent_id'] = $this->parent_id;
                }
                return $data;
            });

        return $actions;
    }
}
