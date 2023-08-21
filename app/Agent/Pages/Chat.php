<?php

namespace App\Agent\Pages;

use App\Models\Agent;
use App\Models\User;
use Filament\Pages\Page;
use App\Models\Chat\Room;
use App\Models\Tourguide;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Chat extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static string $view = 'filament.pages.agent.chat';

    protected static ?string $slug = 'chat';

    protected static string|array $middlewares = 'permission:Chat';

    protected static ?int $navigationSort = 6;

    protected $queryString = ['search' => ['except' => '']];

    public $msg;
    public $search;
    public $user;
    public $room;
    public $messages;
    public $rooms;
    public $tourguide_id;
    public $event_id;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.chat');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.chats');
    }

    protected static function getNavigationBadge(): ?string
    {
        return Room::whereRelation('members', 'memberable_id', auth('agent')->id())
            ->whereRelation('members', 'memberable_type', Agent::class)
            ->count();
    }

    public static function getRoutes(): \Closure
    {
        return fn() => Route::get('/chat/{tourguide_id?}/{event_id?}', static::class)->name('chat');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function mount()
    {
        if ($this->tourguide_id = request('tourguide_id')) {
            $this->event_id     = request('event_id');
            $this->user         = Tourguide::find($this->tourguide_id) ?? ['id' => $this->tourguide_id, 'type' => Tourguide::class];
            $this->room         = (new Room)->getRoomBetween(auth('agent')->user(), $this->user, $this->event_id);
            $this->messages     = $this->room->messages()->oldest()->with("sender.memberable")->get();
        }
    }

    protected function getListeners()
    {
        return isset($this->room->id) ? ["echo-private:room.{$this->room->id},MessageSent" => 'sendMessage'] : [];
    }

    protected function getViewData(): array
    {
        $this->rooms = auth('agent')->user()->rooms()
            ->withMembers('agent', Tourguide::class)
            ->when($this->search, function ($q) {
                $q->whereHas('members', function ($q) {
                    $q->whereHasMorph('memberable', [Tourguide::class], function ($q) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$this->search%");
                    });
                });
            })->get();

        if ($this->room) {
            $this->room->unreadMessages('agent')->update(['is_read' => true]);
            $this->messages     = $this->room->messages()->oldest()->with("sender.memberable")->get();
            $allow_send_message = auth('agent')->user()->whereHas('roles', function ($query) {
            $query->where([['name', 'admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        })
                ? true
                : $this->room->members()->whereMe('agent')->first()->is_enabled;

            return [
                'user'               => $this->user,
                'rooms'              => $this->rooms,
                'current_room'       => $this->room,
                'allow_send_message' => $allow_send_message,
            ];
        }

        return ['rooms' => $this->rooms];
    }

    public function sendMsg()
    {
        $sender = $this->room->members()->whereMe('agent')->first();
        $this->room->sendMsg($this->msg, $sender->id, 'agent');
        $this->msg = null;
    }

    public function sendMessage($event)
    {
        $this->messages->push($event['message']);
    }

    public function selectRoom($tourguide_id, $event_id = null)
    {
        return !is_null($event_id)
            ? redirect()->route('agent.pages.chat', ['tourguide_id' => $tourguide_id, 'event_id' => $event_id])
            : redirect()->route('agent.pages.chat', ['tourguide_id' => $tourguide_id]);
    }

    protected function getLayoutData(): array
    {
        return [...parent::getLayoutData(), 'vite' => true];
    }
}
