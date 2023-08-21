<?php

namespace App\Filament\Resources\Tourguide\TourguideResource\Pages;

use App\Filament\Resources\Tourguide\TourguideResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditTourguide extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = TourguideResource::class;

    protected static string | array $middlewares = ['permission:Edit Tourguides'];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password'], $data['password_confirmation']);
        } else {
            unset($data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
        }

        if (in_array($data['email_verified_at'], [null, false, '0', ''], true)) {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = null;
        } else {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = now();
        }

        return $data;
    }

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
            Actions\DeleteAction::make(),

            Actions\Action::make('recommend')
                ->label(__('actions.recommend'))
                ->requiresConfirmation()
                ->color('warning')
                ->visible(fn() => !$this->record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->exists())
                ->action(function () {
                    if ($this->record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->exists()) {
                        return;
                    }
                    auth()->user()->favourites()->create([
                        'favouritable_id' => $this->record->id,
                        'favouritable_type' => $this->record->getMorphClass(),
                    ]);
                    Notification::make()
                        ->success()
                        ->title(__('notifications.recommendation_added'))
                        ->body(__('notifications.recommendation_added_body', ['name' => $this->record->full_name]))
                        ->send();
                }),

            Actions\Action::make('Unrecommend')
                ->label(__('actions.unrecommend'))
                ->action(function () {
                    $this->record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->delete();
                    Notification::make()
                        ->success()
                        ->title(__('notifications.recommendation_removed'))
                        ->body(__('notifications.recommendation_removed_body', ['name' => $this->record->full_name]))
                        ->send();
                })
                ->visible(fn() => $this->record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->exists())
                ->requiresConfirmation(),

            Actions\Action::make('new_chat')
                ->label(__('actions.new_chat'))
                ->color('success')
                ->action(fn() => redirect()->route('filament.pages.chat', $this->record->id)),
        ];
    }
}
