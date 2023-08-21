<?php

namespace App\TourGuide\Resources\Order;

use App\Facades\Firebase;
use App\Models\Order;
use App\Models\OrderTourguide;
use App\Models\Rate;
use App\Models\Tourguide;
use App\Notifications\DatabaseNotification;
use App\TourGuide\Resources\Order\OrderResource\Pages;
use App\Services\Map;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\DB;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;

class OrderResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $slug = 'bookings';

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.requested_bookings');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.requested_bookings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Map::make('location')
                    ->draggable(false)
                    ->columnSpanFull()
                    ->disableLabel()
                    ->defaultLocation([30.033333, 31.233334]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('event.cover')
                    ->circular()
                    ->label(__('attributes.cover')),

                Tables\Columns\TextColumn::make('event.name')
                    ->label(__('attributes.event'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.agent.company.name')
                    ->label(__('attributes.company.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tourguide_status')
                    ->label(__('attributes.tourguide_status'))
                    ->color(function ($record) {
                        return match ($record->tourguides()->where('tourguides.id', auth('tourguide')->id())->first()->pivot->status) {
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'declined' => 'warning',
                            default => 'secondary',
                        };
                    })
                    ->enum([
                        'approved' => __('attributes.approved'),
                        'rejected' => __('attributes.rejected'),
                        'declined' => __('attributes.declined'),
                    ]),

                Tables\Columns\BadgeColumn::make('agent_status')
                    ->label(__('attributes.agent_status'))
                    ->color(function ($record) {
                        return match ($record->tourguides()->where('tourguides.id', auth('tourguide')->id())->first()->pivot->agent_status) {
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'declined' => 'warning',
                            default => 'secondary',
                        };
                    })
                    ->enum([
                        'approved' => __('attributes.approved'),
                        'rejected' => __('attributes.rejected'),
                        'declined' => __('attributes.declined'),
                    ]),

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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('Company Profile')
                    ->label(__('attributes.company_profile'))
                    ->color('primary')
                    ->icon('heroicon-o-office-building')
                    ->action(fn($record) => redirect()->route('tour-guide.pages.company-profile', $record->event->agent->company)),

                Tables\Actions\Action::make('approve')
                    ->label(__('attributes.approve'))
                    ->color('success')
                    ->icon('heroicon-s-check-circle')
                    ->visible(fn($record) => $record->tourguide_status === 'pending' && $record->agent_status === 'pending')
                    ->action(function ($record) {
                        try {
                            DB::beginTransaction();
                            $body = ucwords(auth('tourguide')->user()->full_name) . " has approved your order for {$record->event->name}";
                            $record->tourguides()->where('tourguides.id', auth('tourguide')->id())->first()->pivot->update(['status' => 'approved']);
                            $record->orderable->notify(new DatabaseNotification(
                                __('notifications.booking.approved.title'),
                                __('notifications.booking.approved.body', ['user' => ucwords(auth('tourguide')->user()->full_name), 'event' => $record->event->name]),
                                'order',
                                ['id' => $record->id]
                            ));
                            Notification::make()->success()
                                ->title(__('notifications.booking.approved.title'))
                                ->body(__('notifications.booking.approved.body', ['user' => ucwords(auth('tourguide')->user()->full_name), 'event' => $record->event->name]))
                                ->send();
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.error'))
                                ->body(__('messages.something_went_wrong'))
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('Reject')
                    ->label(__('attributes.reject'))
                    ->color('danger')
                    ->icon('heroicon-s-x')
                    ->visible(fn($record) => $record->tourguide_status === 'pending' && $record->agent_status === 'pending')
                    ->action(function ($record) {
                        try {
                            DB::beginTransaction();
                            $record->tourguides()->where('tourguides.id', auth('tourguide')->id())->first()->pivot
                                ->update(['status' => 'rejected']);
                            $record->orderable->notify(new DatabaseNotification(
                                __('notifications.booking.rejected.title'),
                                __('notifications.booking.rejected.body', ['user' => ucwords(auth('tourguide')->user()->full_name), 'event' => $record->event->name]),
                                'order',
                                ['id' => $record->id]
                            ));
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.booking.rejected.title'))
                                ->body(__('notifications.booking.rejected.body', ['user' => ucwords(auth('tourguide')->user()->full_name), 'event' => $record->event->name]))
                                ->send();
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.error'))
                                ->body(__('messages.something_went_wrong'))
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('rate')
                    ->label(__('attributes.feedback'))
                    ->modalHeading(__('attributes.rate_company'))
                    ->color('warning')
                    ->icon('heroicon-s-star')
                    ->visible(fn($record) => $record->event->end_at->isPast() && $record->status === 'approved' && $record->agent_status === 'approved')
                    ->form(fn($record) => [
                        Radio::make('rate')
                            ->label('Rate')
                            ->required()
                            ->options([
                                '1' => __('attributes.star', ['count' => '1']),
                                '2' => __('attributes.star', ['count' => '2']),
                                '3' => __('attributes.star', ['count' => '3']),
                                '4' => __('attributes.star', ['count' => '4']),
                                '5' => __('attributes.star', ['count' => '5']),
                            ]),

                        Textarea::make('comment')
                            ->label(__('attributes.feedback'))
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {
                        try {
                            Rate::create([
                                'ratable_type' => auth('tourguide')->user()->getMorphClass(),
                                'ratable_id' => auth('tourguide')->user()->id,
                                'rate' => $data['rate'],
                                'comment' => $data['comment'],
                                'agent_id' => $record->event->agent_id,
                            ]);

                            Notification::make()
                                ->success()
                                ->title(__('notifications.rating_success'))
                                ->body(__('notifications.rating_success_body', ['tourguide' => $record->event->agent->full_name]))
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.rating_failed'))
                                ->body(__('notifications.rating_failed_body', ['tourguide' => $record->event->agent->full_name]))
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
