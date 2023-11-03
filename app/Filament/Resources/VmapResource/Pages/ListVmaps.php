<?php

namespace App\Filament\Resources\VmapResource\Pages;

use App\Filament\Resources\VmapResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVmaps extends ListRecords
{
    protected static string $resource = VmapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
