<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelegramAccountResource\Pages;
use App\Models\TelegramAccount;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramAccountResource extends Resource
{
    protected static ?string $model = TelegramAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->label('Telegram Phone Number')
                    ->required()
                    ->placeholder('+1234567890'),
                Forms\Components\TextInput::make('api_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_hash')
                    ->maxLength(255),
                Forms\Components\Textarea::make('session_data'),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('api_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('api_hash')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('session_data')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
                Tables\Actions\Action::make('authenticate')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->label('Authenticate')
                    ->form([
                        TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->default(fn ($record) => $record->phone_number)
                            ->disabled()
                            ->required(),
                        TextInput::make('api_id')
                            ->default(fn ($record) => $record->api_id)
                            ->required()
                            ->visible(fn ($record) => empty(session("telegram_auth.{$record->id}.verification_id"))),
                        TextInput::make('api_hash')
                            ->label('API Hash')
                            ->default(fn ($record) => $record->api_hash)
                            ->required()
                            ->visible(fn ($record) => empty(session("telegram_auth.{$record->id}.verification_id"))),
                        TextInput::make('verification_code')
                            ->label('Verification Code')
                            ->numeric()
                            ->length(5)
                            ->visible(fn ($record) => filled(session("telegram_auth.{$record->id}.verification_id")))
                            ->required(fn ($record) => filled(session("telegram_auth.{$record->id}.verification_id")))
                    ])
                    ->action(function ($record, $data, $action) {
                        // Step 1: Start Authentication (when no verification code is provided)
                        if (empty($data['verification_code'])) {
                            try {
                                // Log the request data
                                Log::debug('Starting authentication request with data:', [
                                    'phone_number' => $record->phone_number,
                                    'api_id' => $data['api_id'],
                                    'api_hash' => $data['api_hash'],
                                ]);

                                // Make the request
                                $response = Http::post('http://localhost:3000/api/telegram/authenticate', [
                                    'phone_number' => $record->phone_number,
                                    'api_id' => $data['api_id'],
                                    'api_hash' => $data['api_hash'],
                                ]);

                                // Log the raw response for debugging
                                Log::debug('Raw response:', [
                                    'status' => $response->status(),
                                    'body' => $response->body(),
                                    'headers' => $response->headers(),
                                ]);

                                if (!$response->successful()) {
                                    throw new \Exception($response->json()['message'] ?? 'Failed to start authentication');
                                }

                                $responseData = $response->json();

                                // Log the parsed response data
                                Log::debug('Parsed response data:', $responseData);

                                // Explicit check for required fields
                                if (!isset($responseData['phone_code_hash'])) {
                                    Log::error('Response data missing phone_code_hash:', $responseData);
                                    throw new \Exception('Phone code hash not received from server. Response: ' . json_encode($responseData));
                                }

                                if (!isset($responseData['verification_id'])) {
                                    throw new \Exception('Verification ID not received from server');
                                }

                                // Store authentication data in session with explicit data
                                $sessionData = [
                                    'verification_id' => $responseData['verification_id'],
                                    'phone_code_hash' => $responseData['phone_code_hash'],
                                    'api_id' => $data['api_id'],
                                    'api_hash' => $data['api_hash'],
                                ];

                                // Log the session data before storing
                                Log::debug('Storing session data:', $sessionData);

                                session(["telegram_auth.{$record->id}" => $sessionData]);

                                // Verify session was stored
                                $storedData = session("telegram_auth.{$record->id}");
                                Log::debug('Stored session data:', $storedData ?? ['error' => 'No data stored']);

                                Notification::make()
                                    ->title('Enter Verification Code')
                                    ->body('Please check your Telegram app for the verification code')
                                    ->info()
                                    ->persistent()
                                    ->send();

                                $action->halt();
                                return;
                            } catch (\Exception $e) {
                                Log::error('Authentication error:', [
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);

                                Notification::make()
                                    ->title('Error')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                                return;
                            }
                        }

                        // Step 2: Verify Code
                        try {
                            $authData = session("telegram_auth.{$record->id}");

                            // Log verification attempt
                            Log::debug('Starting verification with data:', [
                                'auth_data' => $authData,
                                'verification_code' => $data['verification_code']
                            ]);

                            // Validate session data
                            if (empty($authData)) {
                                throw new \Exception('Authentication session not found. Please try again.');
                            }

                            if (empty($authData['phone_code_hash'])) {
                                throw new \Exception('Phone code hash is missing. Please try again.');
                            }

                            // Make verification request
                            $response = Http::post('http://localhost:3000/api/telegram/verify', [
                                'verification_id' => $authData['verification_id'],
                                'phone_number' => $record->phone_number,
                                'phone_code' => $data['verification_code'],
                                'phone_code_hash' => $authData['phone_code_hash'],
                                'api_id' => $authData['api_id'],
                                'api_hash' => $authData['api_hash'],
                            ]);

                            // Log verification response
                            Log::debug('Verification response:', [
                                'status' => $response->status(),
                                'body' => $response->json()
                            ]);

                            if (!$response->successful()) {
                                throw new \Exception($response->json()['message'] ?? 'Invalid verification code');
                            }

                            $responseData = $response->json();

                            // Update account with session data
                            $record->update([
                                'api_id' => $authData['api_id'],
                                'api_hash' => $authData['api_hash'],
                                'session_data' => $responseData['session'] ?? null,
                                'status' => true,
                            ]);

                            // Clear session data
                            session()->forget("telegram_auth.{$record->id}");

                            Notification::make()
                                ->title('Authentication Successful')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Log::error('Verification error:', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);

                            Notification::make()
                                ->title('Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();

                            // Clear the session on error to allow retry
                            session()->forget("telegram_auth.{$record->id}");
                        }
                    })
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
            'index' => Pages\ListTelegramAccounts::route('/'),
            'create' => Pages\CreateTelegramAccount::route('/create'),
            'view' => Pages\ViewTelegramAccount::route('/{record}'),
            'edit' => Pages\EditTelegramAccount::route('/{record}/edit'),
        ];
    }
}
