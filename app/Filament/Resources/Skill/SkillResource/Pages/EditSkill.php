<?php

namespace App\Filament\Resources\Skill\SkillResource\Pages;

use App\Filament\Resources\Skill\SkillResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkill extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = SkillResource::class;

    protected static string | array $middlewares = ['permission:Edit Skills'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
