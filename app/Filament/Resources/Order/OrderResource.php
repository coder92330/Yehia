<?php

namespace App\Filament\Resources\Order;

use App\Filament\Resources\Order\OrderResource\Pages;
use App\Filament\Resources\Order\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Tourguide;
use App\Notifications\DatabaseNotification;
use App\Services\Map;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $slug = 'confirmed-bookings';

    protected static string | array $middlewares = ['permission:List Confirmed Bookings|Create Confirmed Bookings|View Confirmed Bookings|Edit Confirmed Bookings'];

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.bookings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.confirmed_bookings');
    }

    protected static function label(): string
    {
        return __('navigation.labels.confirmed_bookings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Confirmed Bookings', 'Create Confirmed Bookings', 'View Confirmed Bookings', 'Edit Confirmed Bookings']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->visible(fn(Closure $get) => !Route::Is('filament.resources.confirmed-bookings.view'))
                    ->schema([
                        Select::make('event_id')
                            ->label(__('attributes.event'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.event')]))
//                            ->afterStateUpdated(function (\Closure $set, $state) {
//                                if (Tourguide::available($state)->count() === 0) {
//                                    Notification::make()
//                                        ->title('No tourguides available')
//                                        ->danger()
//                                        ->body('There are no tourguides available for the selected event, please select another event or try again later.')
//                                        ->send();
//                                }
//                            })
//                            ->hint('Select an event to see available tourguides')
//                            ->reactive()
                            ->searchable()
                            ->required(),

//                        Select::make('tourguide_id')
//                            ->label('Tourguide')
//                            ->placeholder('Select a tourguide')
//                            ->options(function (callable $get) {
//                                return $get('event_id') !== null
//                                    ? Tourguide::available($get('event_id'))->get()->pluck('full_name', 'id')
//                                    : [];
//                            })
//                            ->hidden(fn(Closure $get) => $get('event_id') === null || Tourguide::available($get('event_id'))->count() === 0)
//                            ->searchable()
//                            ->required(),
                    ]),

                Map::make('location')
                    ->visible(fn(Closure $get) => Route::Is('filament.resources.confirmed-bookings.view'))
                    ->columnSpanFull()
                    ->disableLabel()
                    ->draggable(false)
                    ->defaultLocation([30.033333, 31.233334]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('event.cover')
                    ->circular()
                    ->label(__('attributes.event_cover')),

                Tables\Columns\TextColumn::make('event.name')
                    ->label(__('attributes.event'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.agent.company.name')
                    ->label(__('attributes.company.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.agent.full_name')
                    ->label(__('attributes.agent'))
                    ->visible(fn() => auth()->user()->whereHas('roles', function ($query) {
            $query->where([['name', 'admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        })),

//                Tables\Columns\TextColumn::make('tourguide.full_name')
//                    ->label('Tourguide')
//                    ->sortable(),

                Tables\Columns\TextColumn::make('event.start_at')
                    ->label(__('attributes.start_date'))
                    ->date()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.end_at')
                    ->label(__('attributes.end_date'))
                    ->date()
                    ->searchable()
                    ->sortable(),

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
//                Tables\Actions\EditAction::make(),
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
//                            $record->tourguide->notify(new DatabaseNotification('Order Approved', ucwords(auth('agent')->user()->full_name) . " has approved your order for {$record->event->name}"));
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
//                                ->title('Error')
//                                ->body("Failed to approve order for {$record->event->name}")
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
//                                ->title('Order Declined')
//                                ->body("You have declined the order for {$record->event->name}")
//                                ->send();
//                            DB::commit();
//                        } catch (\Exception $e) {
//                            DB::rollBack();
//                            Notification::make()
//                                ->danger()
//                                ->title('Error')
//                                ->body("Failed to reject order for {$record->event->name}")
//                                ->send();
//                        }
//                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TourguidesRelationManager::class
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
