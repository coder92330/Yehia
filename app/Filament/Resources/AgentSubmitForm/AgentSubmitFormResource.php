<?php

namespace App\Filament\Resources\AgentSubmitForm;

use App\Filament\Resources\AgentSubmitFormResource\Pages;
use App\Filament\Resources\AgentSubmitFormResource\RelationManagers;
use App\Models\AgentSubmitForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class AgentSubmitFormResource extends Resource
{
    protected static ?string $model = AgentSubmitForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getLabel(): ?string
    {
        return __('navigation.labels.agent_submit_forms');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.submit_forms');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.agent_submit_forms');
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
                Forms\Components\TextInput::make('website')
                    ->label(__('attributes.website'))
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('website')->label(__('attributes.website')),
                Tables\Columns\TextColumn::make('created_at')->label(__('attributes.created_at'))->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
//                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make(__('actions.export'))
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
                    ->action(fn(array $data) => Excel::download(new \App\Exports\AgentSubmitFormExport($data['from'] ?? null, $data['to'] ?? null, null), "agent_submit_form_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label(__('actions.export'))
                    ->icon('heroicon-o-archive')
                    ->color('primary')
                    ->action(fn(Collection $records) => Excel::download(new \App\Exports\AgentSubmitFormExport(null, null, $records->pluck('id')->toArray()), "agent_submit_form_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),
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
            'index' => AgentSubmitFormResource\Pages\ListAgentSubmitForms::route('/'),
//            'edit' => AgentSubmitFormResource\Pages\EditAgentSubmitForm::route('/{record}/edit'),
        ];
    }
}
