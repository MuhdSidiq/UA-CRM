<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('telegram_account_id')
                    ->tel()
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('chat_id')
                    ->numeric(),
                Forms\Components\TextInput::make('telegram_message_id')
                    ->tel()
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('message_text')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('message_timestamp')
                    ->required(),
                Forms\Components\TextInput::make('sender_type')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('telegram_account_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('chat_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telegram_message_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('message_timestamp')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender_type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'view' => Pages\ViewMessage::route('/{record}'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
