<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;


use \Filament\Resources\Components\Tab;

use App\Filament\Resources\PostResource;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            'All' => Tab::make('All'),
            'Published' => Tab::make('Published')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('published', true)),
            'Unpublished' => Tab::make('Unpublished')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('published', false)),
        ];
    }
}
