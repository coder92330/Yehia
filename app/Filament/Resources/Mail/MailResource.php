<?php

namespace App\Filament\Resources\Mail;

use App\Filament\Resources\Mail\MailResource\Pages;
use App\Filament\Resources\Mail\MailResource\RelationManagers;
use App\Mail\SendMail;
use App\Models\Agent;
use App\Models\Mail;
use App\Models\Tourguide;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MailResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $navigationIcon = 'heroicon-o-mail';

    protected static ?string $slug = 'mails';

    protected static bool $shouldRegisterNavigation = false;

    protected static string | array $middlewares = ['permission:List Mails|Create Mails'];

//    protected static function shouldRegisterNavigation(): bool
//    {
//        return auth()->user()->hasAnyPermission(['List Mails', 'Create Mails']);
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->autofocus()
                        ->placeholder('The subject of the mail'),

                    Forms\Components\TextInput::make('from')
                        ->email()
                        ->required()
                        ->default(auth()->user()->email)
                        ->placeholder('The sender of the mail'),

                    Forms\Components\TextInput::make('to')
                        ->email()
                        ->required()
                        ->placeholder('The recipient of the mail'),

                    Forms\Components\Textarea::make('body')
                        ->required()
                        ->placeholder('The body of the mail'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('from')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('to')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'sent', 'received'        => 'success',
                            'draft', 'important'      => 'primary',
                            'spam', 'trash', 'failed' => 'danger',
                        };
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('resend_mail')
                    ->icon('heroicon-o-refresh')
                    ->label('Resend')
                    ->visible(fn ($record) => $record->is_mail_sent === false)
                    ->successNotificationTitle('Mail sent successfully')
                    ->failureNotificationTitle('Mail failed to send')
                    ->action(function ($record) {
                        if (\Illuminate\Support\Facades\Mail::to($record->to)->send(new SendMail($record))) {
                            $record->update(['status' => 'sent', 'is_mail_sent' => true]);
                        }
                    }),
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
            'index' => Pages\ListMails::route('/'),
            'create' => Pages\CreateMail::route('/create'),
        ];
    }
}
