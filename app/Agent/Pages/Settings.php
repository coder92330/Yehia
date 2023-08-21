<?php

namespace App\Agent\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Settings extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $slug = 'settings';
    protected static string $view = 'filament.pages.settings';
    protected static ?int $navigationSort = 100;
    public $settings;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.settings');
    }

    protected function getBreadcrumbs(): array
    {
        return [
            route('agent.pages.dashboard') => 'Dashboard',
            url()->current() => 'Settings',
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return Setting::class;
    }

    public function mount()
    {
        $this->settings = auth('agent')->user()->settings()->get();
        foreach ($this->settings as $setting) {
            $this->form->fill([
                $setting->label => is_bool($setting->pivot->value) ? (bool)$setting->pivot->value : $setting->pivot->value,
            ]);
        }
    }

    protected function getFormSchema(): array
    {
        $emailFormSchema = [
            Placeholder::make('email_me_whenever')
                ->inlineLabel()
                ->label(__('attributes.email_me_whenever')),
        ];
        $eventFormSchema = [
            Placeholder::make('event_notifications')
                ->inlineLabel()
                ->label(__('attributes.events')),
        ];

        foreach ($this->settings as $setting) {
            if ($setting->type === 'boolean') {
                if ($setting->group === 'emails') {
                    $emailFormSchema[] = Toggle::make($setting->label);
                } else {
                    $eventFormSchema[] = Toggle::make($setting->label);
                }
            }

            if (in_array($setting->type, ['string', 'integer'])) {
                if ($setting->group === 'emails') {
                    $emailFormSchema[] = TextInput::make($setting->label);
                } else {
                    $eventFormSchema[] = TextInput::make($setting->label);
                }
            }
        }

        return [
            Card::make()->schema([...$emailFormSchema, ...$eventFormSchema]),
        ];
    }

    public function submit()
    {
        try {
            DB::beginTransaction();
            $this->settings->each(function ($setting) {
                auth('agent')->user()->settings()->syncWithoutDetaching([$setting->id => ['value' => $this->form->getState()[$setting->label]]]);
            });
            Notification::make()->success()->title(__('notifications.updated_successfully', ['field' => __('attributes.settings')]))->send();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in Setting@submit: {$e->getMessage()} in File: {$e->getFile()} on Line: {$e->getLine()}");
            Notification::make()->danger()->title(__('messages.something_went_wrong'))->send();
        }
    }
}
