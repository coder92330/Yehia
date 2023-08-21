<?php

namespace App\Filament\Resources\Testimonial\TestimonialResource\Pages;

use App\Filament\Resources\Testimonial\TestimonialResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = TestimonialResource::class;

    protected static string | array $middlewares = ['permission:Create Testimonials'];

    protected function getActions(): array
    {
        return [
             Actions\LocaleSwitcher::make(),
        ];
    }
}
