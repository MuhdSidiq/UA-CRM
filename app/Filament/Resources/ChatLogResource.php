<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatLogResource\Pages;
use App\Filament\Resources\ChatLogResource\RelationManagers;
use App\Models\ChatLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatLogResource extends Resource
{
    protected static ?string $model = ChatLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lead_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('message_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sender_type')
                    ->required(),
                Forms\Components\TextInput::make('sender_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('message_type')
                    ->required(),
                Forms\Components\TextInput::make('file_url')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('sent_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('message_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender_type'),
                Tables\Columns\TextColumn::make('sender_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message_type'),
                Tables\Columns\TextColumn::make('file_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListChatLogs::route('/'),
            'create' => Pages\CreateChatLog::route('/create'),
            'view' => Pages\ViewChatLog::route('/{record}'),
            'edit' => Pages\EditChatLog::route('/{record}/edit'),
        ];
    }
}
