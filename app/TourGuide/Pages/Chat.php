<?php

namespace App\TourGuide\Pages;

use App\Models\Agent;
use App\Models\User;
use Filament\Pages\Page;
use App\Models\Chat\Room;
use App\Models\Tourguide;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Chat extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static string $view = 'filament.pages.tourguide.chat';

    protected static ?string $slug = 'chat';

    protected $queryString = ['search' => ['except' => '']];

    public $messages;
    public $room_id;
    public $search;
    public $rooms;
    public $room;
    public $user;
    public $msg;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.chat');
    }

    public static function getRoutes(): \Closure
    {
        return fn() => Route::get('/chat/{room_id?}', static::class)->name('chat');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function mount()
    {
        if ($this->room_id = request('room_id')) {
            $this->room     = Room::find($this->room_id);
            $this->user     = $this->room->members()->where('memberable_type', '!=', Tourguide::class)->first()->memberable;
            $this->messages = $this->room->messages()->with("sender.memberable")->oldest()->get();
        }
    }

    protected function getListeners()
    {
        return isset($this->room->id) ? ["echo-private:room.{$this->room->id},MessageSent" => 'sendMessage'] : [];
    }

    protected function getViewData(): array
    {
        $this->rooms = auth('tourguide')->user()->rooms()
            ->withMembers('tourguide', [Agent::class, User::class])
            ->when($this->search, function ($q) {
                $q->whereHas('members', function ($q) {
                    $q->whereHasMorph('memberable', [Agent::class], function ($q) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$this->search%");
                    })
                        ->orWhereHasMorph('memberable', [User::class], fn($q) => $q->where('name', 'like', "%$this->search%"));
                });
            })->get();

        if ($this->room) {
            $this->room->unreadMessages('tourguide')->update(['is_read' => true]);
            $this->messages = $this->room->messages()->oldest()->with("sender.memberable")->get();

            return [
                'user'         => $this->user,
                'rooms'        => $this->rooms,
                'messages'     => $this->messages,
                'current_room' => $this->room
            ];
        }

        return ['rooms' => $this->rooms];
    }

    public function sendMsg()
    {
        $sender = $this->room->members()->where(['memberable_id' => auth('tourguide')->id(), 'memberable_type' => auth('tourguide')->user()->getMorphClass()])->first();
        $this->room->sendMsg($this->msg, $sender->id, 'tourguide');
        $this->msg = null;
    }

    public function sendMessage($event)
    {
        $this->messages->push($event['message']);
    }

    public function selectRoom($room_id)
    {
        return redirect()->route('tour-guide.pages.chat', ['room_id' => $room_id]);
    }
}
