<?php

namespace App\Filament\Resources\TourguideSubmitForm;

use App\Filament\Resources\TourguideSubmitFormResource\Pages;
use App\Filament\Resources\TourguideSubmitFormResource\RelationManagers;
use App\Models\TourguideSubmitForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class TourguideSubmitFormResource extends Resource
{
    protected static ?string $model = TourguideSubmitForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getLabel(): ?string
    {
        return __('navigation.labels.tourguide_submit_forms');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.tourguide_submit_forms');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.submit_forms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                    ->label(__('attributes.full_name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label(__('attributes.phone.number'))
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label(__('attributes.email'))
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label(__('attributes.address'))
                    ->required()
                    ->maxLength(65535),

                Forms\Components\TextInput::make('languages')
                    ->label(__('attributes.languages'))
                    ->required(),

                Forms\Components\TextInput::make('gender')
                    ->label(__('attributes.gender'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('date_of_birth')
                    ->label(__('attributes.date_of_birth'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->label(__('attributes.full_name')),
                Tables\Columns\TextColumn::make('phone')->label(__('attributes.phone.number')),
                Tables\Columns\TextColumn::make('email')->label(__('attributes.email')),
                Tables\Columns\TextColumn::make('address')->label(__('attributes.address')),
                Tables\Columns\TextColumn::make('languages')->label(__('attributes.languages')),
                Tables\Columns\TextColumn::make('gender')->label(__('attributes.gender')),
                Tables\Columns\TextColumn::make('date_of_birth')->label(__('attributes.date_of_birth'))->date(),
                Tables\Columns\TextColumn::make('created_at')->label(__('attributes.created_at'))->dateTime()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export')
                    ->label(__('actions.export'))
                    ->icon('heroicon-o-archive')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label(__('attributes.export_type'))
                            ->options([
                                'all' => __('attributes.all_submissions'),
                                'date' => __('attributes.date_range'),
                            ])
                            ->default('all')
                            ->reactive()
                            ->required(),

                        Forms\Components\DatePicker::make('from')
                            ->label(__('attributes.from_date'))
                            ->visible(fn(callable $get) => $get('type') === 'date')
                            ->required(),

                        Forms\Components\DatePicker::make('to')
                            ->label(__('attributes.to_date'))
                            ->visible(fn(callable $get) => $get('type') === 'date')
                            ->default(now())
                            ->required(),
                    ])
                    ->modalButton('Export')
                    ->action(fn(array $data) => Excel::download(new \App\Exports\TourguideSubmitFormExport($data['from'] ?? null, $data['to'] ?? null, null), "tourguides_submit_form_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label(__('actions.export'))
                    ->icon('heroicon-o-archive')
                    ->color('primary')
                    ->action(fn(Collection $records) => Excel::download(new \App\Exports\TourguideSubmitFormExport(null, null, $records->pluck('id')->toArray()), "tourguides_submit_form_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),
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
            'index' => TourguideSubmitFormResource\Pages\ListTourguideSubmitForms::route('/'),
//            'edit' => TourguideSubmitFormResource\Pages\EditTourguideSubmitForm::route('/{record}/edit'),
        ];
    }
}
