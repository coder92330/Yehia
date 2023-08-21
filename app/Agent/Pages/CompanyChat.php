<?php

namespace App\Agent\Pages;

use App\Models\Agent;
use App\Models\Chat\Message;
use App\Models\Chat\Room;
use App\Models\Company;
use App\Models\Tourguide;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CompanyChat extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt';

    protected static string $view = 'filament.pages.agent.company-chat';

    protected static ?int $navigationSort = 8;

    protected $queryString = ['search' => ['except' => '']];

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.company_chat');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    public $msg;
    public $search;
    public $user;
    public $room;
    public $messages;
    public $rooms;
    public $user_id;
    public $event_id;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasAnyPermission(['Company Chat']);
    }

    public static function getRoutes(): \Closure
    {
        return fn() => Route::get('/company-chat/{user_id?}/{event_id?}', static::class)->name('company-chat');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function mount()
    {
        abort_unless(auth('agent')->user()->hasPermissionTo('Company Chat'), 403, __('messages.unauthorized_access'));

        if ($this->user_id  = request('user_id')) {
            $this->event_id = request('event_id');
            $this->user     = User::find($this->user_id) ?? ['id' => $this->user_id, 'type' => User::class];
            $this->room     = (new Room)->getRoomBetween(auth('agent')->user(), $this->user, $this->event_id, 'user');
            $this->messages = $this->room->messages()->oldest()->with("sender.memberable")->get();
        }
    }

    protected function getListeners()
    {
        return isset($this->room->id) ? ["echo-private:room.{$this->room->id},MessageSent" => 'sendMessage'] : [];
    }

    protected function getViewData(): array
    {
        $this->rooms = auth('agent')->user()->rooms()
            ->withMembers('agent', User::class)
            ->when($this->search, function ($query) {
                $query->whereHas('members', function ($query) {
                    $query->whereHasMorph('memberable', [User::class], function ($query) {
                        $query->where('name', 'like', "%$this->search%");
                    });
                });
            })
            ->selectRaw('rooms.*, "company_chat" AS chat_type')
            ->get();

        if ($this->room) {
            $this->room->unreadMessages('agent')->update(['is_read' => true]);
            $this->messages = $this->room->messages()->oldest()->with("sender.memberable")->get();

            return [
                'user'         => $this->user,
                'msg'          => $this->msg,
                'rooms'        => $this->rooms,
                'current_room' => $this->room
            ];
        }

        return ['rooms' => $this->rooms];
    }

    public function sendMsg()
    {
        $this->room->sendMsg($this->msg, $this->room->getMemberId('agent'));
        $this->msg = null;
    }

    public function sendMessage($event)
    {
        $this->messages->push($event['message']);
    }

    public function selectRoom($user_id, $event_id = null)
    {
        return !is_null($event_id)
            ? redirect()->route('agent.pages.company-chat', ['user_id' => $user_id, 'event_id' => $event_id])
            : redirect()->route('agent.pages.company-chat', ['user_id' => $user_id]);
    }

    protected function getLayoutData(): array
    {
        return [...parent::getLayoutData(), 'vite' => true];
    }
}
