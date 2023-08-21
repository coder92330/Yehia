<?php

namespace App\Agent\Resources\Order;

use App\Agent\Resources\Order\OrderResource\Pages;
use App\Agent\Resources\Order\OrderResource\RelationManagers;
use App\Models\Event;
use App\Models\Order;
use App\Models\Rate;
use App\Models\Tourguide;
use App\Notifications\DatabaseNotification;
use App\Services\Map;
use Closure;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;

class OrderResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $slug = 'ordered-bookings';

    protected static string|array $middlewares = 'permission:List Ordered Bookings|Create Ordered Bookings|View Ordered Bookings|Edit Ordered Bookings|Delete Ordered Bookings';

    protected static ?int $navigationSort = 5;

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.bookings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.ordered_bookings');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.ordered_booking');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasAnyPermission(['List Ordered Bookings', 'Create Ordered Bookings', 'View Ordered Bookings', 'Edit Ordered Bookings', 'Delete Ordered Bookings']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return Order::whereHas('event.agent.company', fn($query) => $query->whereId(auth('agent')->user()->company_id))
            ->whereHas('tourguides', fn($q) => $q->where('status', '!=', 'approved')->orWhere('agent_status', '!=', 'approved'))
            ->when(auth('agent')->user()->hasRole('agent'), function ($query) {
                $query->whereHas('orderable', fn($query) => $query->where([
                    ['orderable_id', auth('agent')->id()],
                    ['orderable_type', auth('agent')->user()->getMorphClass()]
                ]));
            })->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->hiddenOn('view')
                    ->schema([
                        Select::make('event_id')
                            ->label(__('attributes.event'))
                            ->placeholder(__('attributes.select', ['attribute' => __('attributes.event')]))
                            ->required()
                            ->searchable()
                            ->options(fn() => Event::whereRelation('agent.company', 'id', auth('agent')->user()->company_id)->pluck('name', 'id')),
                    ]),

                Map::make('location')
                    ->draggable(false)
                    ->visibleOn('view')
                    ->columnSpanFull()
                    ->disableLabel()
                    ->defaultLocation([30.033333, 31.233334]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Tables\Columns\ImageColumn::make('event.cover')
                        ->circular()
                        ->label(__('attributes.event_cover')),

                    Tables\Columns\TextColumn::make('event.name')
                        ->label(__('attributes.event'))
                        ->searchable()
                        ->sortable(),

                    Tables\Columns\TextColumn::make('event.agent.full_name')
                        ->label(__('attributes.agent'))
                        ->visible(fn() => auth('agent')->user()->admins()->exists()),

                    Tables\Columns\TextColumn::make('event.start_at')
                        ->label(__('attributes.start_date'))
                        ->date('M d, Y')
                        ->searchable()
                        ->sortable(),

                    Tables\Columns\TextColumn::make('event.end_at')
                        ->label(__('attributes.end_date'))
                        ->date('M d, Y')
                        ->searchable()
                        ->sortable(),
                ]),

                View::make('filament.table.collapsible-order-content')->collapsible()

//                Tables\Columns\BadgeColumn::make('status')
//                    ->label('Tourguide Status')
//                    ->color(function ($record) {
//                        return match ($record->status) {
//                            'approved' => 'success',
//                            'rejected' => 'danger',
//                            'declined' => 'warning',
//                            default => 'secondary',
//                        };
//                    })
//                    ->searchable()
//                    ->sortable(),
//
//                Tables\Columns\BadgeColumn::make('agent_status')
//                    ->label('Agent Status')
//                    ->color(function ($record) {
//                        return match ($record->agent_status) {
//                            'approved' => 'success',
//                            'rejected' => 'danger',
//                            'declined' => 'warning',
//                            default => 'secondary',
//                        };
//                    })
//                    ->searchable()
//                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

//                Tables\Actions\Action::make('agent_approve')
//                    ->label('Approve')
//                    ->color('success')
//                    ->icon('heroicon-s-check-circle')
//                    ->visible(fn($record) => $record->status === 'approved' && $record->agent_status === 'pending')
//                    ->action(function ($record) {
//                        try {
//                            DB::beginTransaction();
//                            $record->update(['agent_status' => 'approved']);
//                            Order::where('event_id', $record->event_id)
//                                ->where('id', '!=', $record->id)
//                                ->update(['agent_status' => 'declined']);
//                            $record->tourguide->notify(new DatabaseNotification('Order Approved', ucwords(auth('agent')->user()->full_name) . " has approved your order for " . $record->event->name));
//
//                            Notification::make()
//                                ->success()
//                                ->title('Order Approved')
//                                ->body("You have approved the order for {$record->event->name}")
//                                ->send();
//                            DB::commit();
//                        } catch (\Exception $e) {
//                            DB::rollBack();
//                            Notification::make()
//                                ->danger()
//                                ->title('Order Approval Failed')
//                                ->body("Failed to approve the order for {$record->event->name}")
//                                ->send();
//                        }
//                    }),
//
//                Tables\Actions\Action::make('agent_rejected')
//                    ->label('Reject')
//                    ->color('danger')
//                    ->icon('heroicon-s-x-circle')
//                    ->visible(fn($record) => $record->status === 'approved' && $record->agent_status === 'pending')
//                    ->action(function ($record) {
//                        try {
//                            DB::beginTransaction();
//                            $record->update(['agent_status' => 'rejected']);
//                            Order::where('event_id', $record->event_id)
//                                ->where('id', '!=', $record->id)
//                                ->update(['agent_status' => 'declined']);
//                            $record->tourguide->notify(new DatabaseNotification('Order Rejected', ucwords(auth('agent')->user()->full_name) . " has rejected your order for {$record->event->name}"));
//                            Notification::make()
//                                ->danger()
//                                ->title('Order Rejected')
//                                ->body("You have rejected the order for {$record->event->name}")
//                                ->send();
//                            DB::commit();
//                        } catch (\Exception $e) {
//                            DB::rollBack();
//                            Notification::make()
//                                ->danger()
//                                ->title('Order Rejection Failed')
//                                ->body("Failed to reject the order for {$record->event->name}")
//                                ->send();
//                        }
//                    }),

//                Tables\Actions\Action::make('rate')
//                    ->label(__('actions.rate'))
//                    ->modalHeading(__('modals.rate.heading'))
//                    ->color('warning')
//                    ->icon('heroicon-s-star')
//                    ->visible(fn($record) => $record->event->end_at->isPast() && $record->status === 'approved' && $record->agent_status === 'approved')
//                    ->form(fn($record) => [
//                        Radio::make('rate')
//                            ->label('Rate')
//                            ->required()
//                            ->options([
//                                '1' => '1 Star',
//                                '2' => '2 Stars',
//                                '3' => '3 Stars',
//                                '4' => '4 Stars',
//                                '5' => '5 Stars',
//                            ]),
//
//                        Textarea::make('comment')
//                            ->label(__('attributes.feedback'))
//                            ->required(),
//                    ])
//                    ->action(function (array $data, $record) {
//                        try {
//                            Rate::create([
//                                'ratable_type' => auth('agent')->user()->getMorphClass(),
//                                'ratable_id' => auth('agent')->user()->id,
//                                'rate' => $data['rate'],
//                                'comment' => $data['comment'],
//                                'tourguide_id' => $record->tourguide_id,
//                            ]);
//
//                            Notification::make()
//                                ->success()
//                                ->title(__('notifications.rating_success'))
//                                ->body(__('notifications.rating_success_body', ['tourguide' => $record->tourguide->full_name]))
//                                ->send();
//                        } catch (\Exception $e) {
//                            Notification::make()
//                                ->danger()
//                                ->title(__('notifications.rating_failed'))
//                                ->body(__('notifications.rating_failed_body', ['tourguide' => $record->tourguide->full_name]))
//                                ->send();
//                        }
//                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TourguidesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
