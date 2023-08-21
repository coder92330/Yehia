<?php

namespace App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource\RelationManagers;

use App\Models\Order;
use App\Models\OrderTourguide;
use App\Models\Tourguide;
use App\Notifications\DatabaseNotification;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\DB;

class TourguidesRelationManager extends RelationManager
{
    protected static string $relationship = 'tourguides';

    protected static ?string $recordTitleAttribute = 'order_id';

    protected bool $allowsDuplicates = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\Select::make('tourguide_id')
//                    ->label('Tourguide')
//                    ->columnSpanFull()
//                    ->searchable()
//                    ->required()
//                    ->options(fn() => \App\Models\Tourguide::all()->pluck('full_name', 'id'))
//                    ->placeholder('Select a tourguide')
//                    ->rules(['required', 'exists:tourguides,id']),
//
//                Forms\Components\Select::make('status')
//                    ->label('Tourguide Status')
//                    ->columnSpanFull()
//                    ->required()
//                    ->options([
//                        'pending' => 'Pending',
//                        'accepted' => 'Accepted',
//                        'rejected' => 'Rejected',
//                    ])
//                    ->placeholder('Select a status')
//                    ->rules(['required', 'string']),
//
//                Forms\Components\Select::make('agent_status')
//                    ->label('Agent Status')
//                    ->columnSpanFull()
//                    ->required()
//                    ->options([
//                        'pending' => 'Pending',
//                        'accepted' => 'Accepted',
//                        'rejected' => 'Rejected',
//                    ])
//                    ->placeholder('Select a status')
//                    ->rules(['required', 'string']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->label(__('attributes.full_name')),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('attributes.tourguide_status'))
                    ->color(function ($record) {
                        return match ($record->status) {
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
                        return match ($record->agent_status) {
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

                Tables\Columns\TextColumn::make('created_at')->date()->label(__('attributes.assigned_at')),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->hidden()
                    ->label(__('buttons.assign_tourguide'))
                    ->color('primary')
                    ->successNotificationTitle(__('notifications.tourguide_assigned_successfully'))
                    ->mutateFormDataUsing(function (RelationManager $livewire, $data) {
                        $data['recordId'] = $livewire->ownerRecord->id;
                        return $data;
                    })
                    ->form([
                        Forms\Components\Select::make('tourguide_id')
                            ->label(__('attributes.tourguide'))
                            ->columnSpanFull()
                            ->searchable()
                            ->required()
                            ->options(fn(RelationManager $livewire) => Tourguide::whereDoesntHave('orders', function ($query) use ($livewire) {
                                $query->where('order_id', '=', $livewire->ownerRecord->id);
                            })->get()->pluck('full_name', 'id'))
                            ->placeholder(__('placeholders.select_tourguide'))
                            ->rules(['required', 'exists:tourguides,id'])
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('agent_approve')
                    ->label(__('buttons.approve'))
                    ->color('success')
                    ->icon('heroicon-s-check-circle')
                    ->visible(fn($record) => $record->status === 'approved' && $record->agent_status === 'pending')
                    ->action(function (RelationManager $livewire, $record) {
                        try {
                            DB::beginTransaction();
                            OrderTourguide::where([['order_id', '=', $record->order_id], ['tourguide_id', '=', $record->tourguide_id]])
                                ->update(['agent_status' => 'approved']);
                            Tourguide::find($record->tourguide_id)
                                ->notify(new DatabaseNotification(
                                    __('notifications.booking.approved.title'),
                                    __('notifications.booking.approved.body', ['event' => $livewire->ownerRecord->event->name, 'user' => ucwords(auth('agent')->user()->full_name)]),
                                    'order',
                                    ['id' => $record->order_id],
                                ));
                            Notification::make()
                                ->success()
                                ->title(__('notifications.booking.approved.title'))
                                ->body(__('notifications.booking.approved.body', ['event' => $livewire->ownerRecord->event->name, 'user' => ucwords(auth('agent')->user()->full_name)]))
                                ->send();
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.error'))
                                ->body(__('notifications.booking.approved.error', ['event' => $livewire->ownerRecord->event->name]))
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('agent_rejected')
                    ->label(__('buttons.reject'))
                    ->color('danger')
                    ->icon('heroicon-s-x-circle')
                    ->visible(fn($record) => $record->status === 'approved' && $record->agent_status === 'pending')
                    ->action(function (RelationManager $livewire, $record) {
                        try {
                            DB::beginTransaction();
                            OrderTourguide::where([['order_id', '=', $record->order_id], ['tourguide_id', '=', $record->tourguide_id]])
                                ->update(['agent_status' => 'rejected']);
                            Tourguide::find($record->tourguide_id)->notify(new DatabaseNotification(
                                __('notifications.booking.rejected.title'),
                                __('notifications.booking.rejected.body', ['event' => $livewire->ownerRecord->event->name, 'user' => ucwords(auth('agent')->user()->full_name)]),
                                'order',
                                ['id' => $record->order_id]
                            ));
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.booking.rejected.title'))
                                ->body(__('notifications.booking.rejected.body', ['event' => $livewire->ownerRecord->event->name, 'user' => ucwords(auth('agent')->user()->full_name)]))
                                ->send();
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.error'))
                                ->body(__('notifications.booking.rejected.error', ['event' => $livewire->ownerRecord->event->name]))
                                ->send();
                        }
                    }),
                Tables\Actions\DetachAction::make()
                    ->label(__('buttons.unassign'))
                    ->icon('heroicon-s-trash')
                    ->hidden(fn($record) => $record->status === 'approved' && $record->agent_status === 'approved')
                    ->successNotificationTitle(__('notifications.tourguide_unassigned_successfully'))
                    ->modalHeading(fn($record) => __('modal.unassign_tourguide.heading', ['tourguide' => $record->full_name]))
                    ->modalSubheading(fn($record) => __('modal.unassign_tourguide.subheading', ['tourguide' => $record->full_name]))
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->hidden()
                    ->label(__('buttons.unassign'))
                    ->icon('heroicon-s-trash')
                    ->successNotificationTitle(__('notifications.tourguides_unassigned_successfully'))
                    ->modalHeading(__('modal.unassign_tourguides.heading'))
                    ->modalSubheading(__('modal.unassign_tourguides.subheading'))
            ]);
    }
}
