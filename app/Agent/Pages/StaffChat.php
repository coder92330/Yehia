<?php

namespace App\Agent\Pages;

use App\Models\Agent;
use App\Models\Chat\Room;
use App\Models\Tourguide;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class StaffChat extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-chat';

    protected static string $view = 'filament.pages.agent.staff-chat';

    protected static string|array $middlewares = 'permission:Staff Chat';

    protected static ?int $navigationSort = 7;

    public $search;
    public $staffs;
    public $enabled_staff;
    public $messages;
    public $tourguide_id;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.staff_chat');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.chats');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasPermissionTo('Staff Chat');
    }

    public static function getRoutes(): \Closure
    {
        return fn() => Route::get('staff-chat/{tourguide_id?}/{event_id?}', static::class)->name('staff-chat');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function mount()
    {
        $this->staffs = Room::staffChat()
            ->when(request()->event_id, fn($q) => $q->where('event_id', request('event_id')))
            ->with('members.memberable', 'messages.sender.memberable')
            ->get();

        if (($this->tourguide_id = request()->tourguide_id) && $this->staffs->count() > 0) {
            $this->enabled_staff = $this->staffs->filter(fn($room) => $room->members->where(['memberable_id' => $this->tourguide_id, 'memberable_type' => Tourguide::class]))->first();
            $this->messages      = $this->enabled_staff->messages;
        }
    }

    protected function getViewData(): array
    {
        return ['staffs' => $this->staffs, 'staff' => $this->enabled_staff, 'messages' => $this->messages];
    }

    public function selectRoom($tourguide_id, $event_id = null)
    {
        return !is_null($event_id)
            ? redirect()->route('agent.pages.staff-chat', ['tourguide_id' => $tourguide_id, 'event_id' => $event_id])
            : redirect()->route('agent.pages.staff-chat', ['tourguide_id' => $tourguide_id]);
    }
}
