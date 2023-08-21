<?php

namespace App\Filament\Pages;

use Closure;
use Filament\Pages\Page;
use App\Models\Chat\Room;
use App\Models\Tourguide;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class Chat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static string $view = 'filament.pages.chat';

    protected static ?string $slug = 'chat';

    protected static ?int $navigationSort = 3;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.tourguide_chat');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.tourguides');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['Chat']);
    }

    public $msg;
    public $search;
    public $user;
    public $room;
    public $messages;
    public $rooms;
    public $tourguide_id;
    public $event_id;

    protected $queryString = ['search' => ['except' => '']];

    public static function getRoutes(): Closure
    {
        return fn() => Route::get('/chat/{tourguide_id?}/{event_id?}', static::class)->name('chat');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function mount()
    {
        abort_unless(auth()->user()->hasPermissionTo('Chat'), 403, __('messages.unauthorized'));

        if ($this->tourguide_id = request('tourguide_id')) {
            $this->event_id     = request('event_id');
            $this->user         = Tourguide::find($this->tourguide_id) ?? ['id' => $this->tourguide_id, 'type' => Tourguide::class];
            $this->room         = (new Room)->getRoomBetween(auth()->user(), $this->user, $this->event_id);
            $this->messages     = $this->room->messages()->oldest()->with("sender.memberable")->get();
        }
    }

    protected function getListeners()
    {
        return isset($this->room->id) ? ["echo-private:room.{$this->room->id},MessageSent" => 'sendMessage'] : [];
    }

    protected function getViewData(): array
    {
        $this->rooms = auth()->user()->rooms()
            ->withMembers('web', Tourguide::class)
            ->when($this->search, function ($q) {
                $q->whereHas('members', function ($q) {
                    $q->whereHasMorph('memberable', [Tourguide::class], function ($q) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$this->search%");
                    });
                });
            })->get();

        if ($this->room) {
            $this->room->unreadMessages('web')->update(['is_read' => true]);
            $this->messages = $this->room->messages()->oldest()->with("sender.memberable")->get();

            return [
                'user'         => $this->user,
                'msg'          => $this->msg,
                'rooms'        => $this->rooms,
                'current_room' => $this->room,
            ];
        }

        return ['rooms' => $this->rooms];
    }

    public function sendMsg()
    {
        $sender = $this->room->members()->whereMe('web')->first();
        $this->room->sendMsg($this->msg, $sender->id, 'admin');
        $this->msg = null;
    }

    public function sendMessage($event)
    {
        $this->messages->push($event['message']);
    }

    public function selectRoom($tourguide_id, $event_id = null)
    {
        return !is_null($event_id)
            ? redirect()->route('filament.pages.chat', ['tourguide_id' => $tourguide_id, 'event_id' => $event_id])
            : redirect()->route('filament.pages.chat', ['tourguide_id' => $tourguide_id]);
    }

    protected function getLayoutData(): array
    {
        return [...parent::getLayoutData(), 'vite' => true];
    }
}
