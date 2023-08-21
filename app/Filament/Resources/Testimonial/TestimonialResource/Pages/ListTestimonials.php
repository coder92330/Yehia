<?php

namespace App\Filament\Resources\Testimonial\TestimonialResource\Pages;

use App\Filament\Resources\Testimonial\TestimonialResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestimonials extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = TestimonialResource::class;

    protected static string | array $middlewares = ['permission:List Testimonials'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
