<?php

namespace App\Filament\Resources\Skill\SkillResource\Pages;

use App\Filament\Resources\Skill\SkillResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkills extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = SkillResource::class;

    protected static string | array $middlewares = ['permission:List Skills'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
