<?php

namespace App\Agent\Resources\ConfirmedOrder;

use App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource\RelationManagers\TourguidesRelationManager;
use App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource\Pages\{CreateConfirmedOrder,
    EditConfirmedOrder,
    ListConfirmedOrder,
    ViewConfirmedOrder
};
use App\Filament\Resources\ConfirmedOrder\ConfirmedOrderResource\RelationManagers;
use App\Models\{Event, Order, Rate};
use App\Services\Map;
use Filament\Forms\Components\{Card, Radio, Select, Textarea};
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\{Form, Resource, Table};
use Filament\Tables;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;

class ConfirmedOrderResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $slug = 'confirmed-bookings';

    protected static string|array $middlewares = 'permission:List Confirmed Bookings|Create Confirmed Bookings|View Confirmed Bookings|Edit Confirmed Bookings|Delete Confirmed Bookings';

    protected static ?int $navigationSort = 6;

    public static function getLabel(): ?string
    {
        return __('navigation.labels.confirmed-bookings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.confirmed-bookings');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.bookings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasAnyPermission(['List Confirmed Bookings', 'Create Confirmed Bookings', 'View Confirmed Bookings', 'Edit Confirmed Bookings', 'Delete Confirmed Bookings']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return Order::whereHas('event.agent.company', fn($query) => $query->whereId(auth('agent')->user()->company_id))
            ->whereHas('tourguides', fn($q) => $q->where([['status', 'approved'], ['agent_status', 'approved']]))
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
                            ->placeholder(__('attributes.select', ['field' => __('attributes.event')]))
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
                        ->visible(fn() => auth('agent')->user()->whereHas('roles', function ($query) {
            $query->where([['name', 'admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        })),

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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('rate')
                    ->label(__('actions.rate'))
                    ->modalHeading(__('modals.rate.heading'))
                    ->color('warning')
                    ->icon('heroicon-s-star')
                    ->visible(fn($record) => $record->event->end_at->isPast() && $record->status === 'approved' && $record->agent_status === 'approved')
                    ->form(fn($record) => [
                        Radio::make('rate')
                            ->label(__('attributes.rate'))
                            ->required()
                            ->options([
                                '1' => __('attributes.star', ['count' => 1]),
                                '2' => __('attributes.star', ['count' => 2]),
                                '3' => __('attributes.star', ['count' => 3]),
                                '4' => __('attributes.star', ['count' => 4]),
                                '5' => __('attributes.star', ['count' => 5]),
                            ]),

                        Textarea::make('comment')
                            ->label(__('attributes.feedback'))
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {
                        try {
                            Rate::create([
                                'ratable_type' => auth('agent')->user()->getMorphClass(),
                                'ratable_id' => auth('agent')->user()->id,
                                'rate' => $data['rate'],
                                'comment' => $data['comment'],
                                'tourguide_id' => $record->tourguide_id,
                            ]);

                            Notification::make()
                                ->success()
                                ->title(__('notifications.rating_success'))
                                ->body(__('notifications.rating_success_body', ['tourguide' => $record->tourguide->full_name]))
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.rating_failed'))
                                ->body(__('notifications.rating_failed_body', ['tourguide' => $record->tourguide->full_name]))
                                ->send();
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TourguidesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConfirmedOrder::route('/'),
            'create' => CreateConfirmedOrder::route('/create'),
            'edit' => EditConfirmedOrder::route('/{record}/edit'),
            'view' => ViewConfirmedOrder::route('/{record}'),
        ];
    }
}
