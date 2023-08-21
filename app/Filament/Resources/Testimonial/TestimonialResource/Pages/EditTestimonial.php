<?php

namespace App\Filament\Resources\Testimonial\TestimonialResource\Pages;

use App\Filament\Resources\Testimonial\TestimonialResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestimonial extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = TestimonialResource::class;

    protected static string | array $middlewares = ['permission:Edit Testimonials'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
