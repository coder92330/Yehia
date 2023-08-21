<?php

namespace App\Filament\Pages;

use App\Models\Agent;
use App\Models\Chat\Room;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class CompanyChat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static string $view = 'filament.pages.company-chat';

    protected static ?int $navigationSort = 4;

    public $msg;
    public $search;
    public $user;
    public $room;
    public $messages;
    public $rooms;
    public $company_id;
    public $event_id;

    protected $queryString = ['search' => ['except' => '']];

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['Company Chat']);
    }

    public static function getRoutes(): \Closure
    {
        return fn() => Route::get('/company-chat/{company_id?}/{event_id?}', static::class)->name('company-chat');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function mount()
    {
        abort_unless(auth()->user()->hasPermissionTo('Company Chat'), 403, __('messages.unauthorized'));

        if ($this->company_id = request('company_id')) {
            $this->event_id   = request('event_id');
            $this->user       = Agent::whereRelation('company', 'id', $this->company_id)->whereHas('roles', fn($q) => $q->where([['name', 'super_admin'], ['guard_name', 'agent']]))->first() ?? ['id' => $this->company_id, 'type' => Agent::class];
            $this->room       = (new Room)->getRoomBetween(auth()->user(), $this->user, $this->event_id);
            $this->messages   = $this->room->messages()->oldest()->with("sender.memberable")->get();
        }
    }

    protected function getListeners()
    {
        return isset($this->room->id) ? ["echo-private:room.{$this->room->id},MessageSent" => 'sendMessage'] : [];
    }

    protected function getViewData(): array
    {
        $this->rooms = auth()->user()->rooms()
            ->withMembers('web', Agent::class)
            ->when($this->search, function ($q) {
                $q->whereHas('members', function ($q) {
                    $q->whereHasMorph('memberable', [Agent::class], function ($q) {
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
        $sender = $this->room->members()->where(['memberable_id' => auth()->id(), 'memberable_type' => auth()->user()->getMorphClass()])->first();
        $this->room->sendMsg($this->msg, $sender->id);
        $this->msg = null;
    }

    public function sendMessage($event)
    {
        $this->messages->push($event['message']);
    }

    public function selectRoom($company_id, $event_id = null)
    {
        return !is_null($event_id)
            ? redirect()->route('filament.pages.company-chat', ['company_id' => $company_id, 'event_id' => $event_id])
            : redirect()->route('filament.pages.company-chat', ['company_id' => $company_id]);
    }

    protected function getLayoutData(): array
    {
        return [...parent::getLayoutData(), 'vite' => true];
    }
}
