<?php

namespace App\Filament\Resources\Skill\SkillResource\Pages;

use App\Filament\Resources\Skill\SkillResource;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateSkill extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SkillResource::class;

    protected static string | array $middlewares = ['permission:Create Skills'];

    protected function getActions(): array
    {
        return [
             LocaleSwitcher::make(),
        ];
    }
}
