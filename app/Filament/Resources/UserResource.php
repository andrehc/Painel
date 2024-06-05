<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Collection;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->description('User informations is here.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->placeholder('User name'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->placeholder('User email'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Toggle::make('active')
                            ->default(true),
                        Forms\Components\Toggle::make('is_admin')
                            ->default(false),
                        Forms\Components\TextInput::make('avatar')
                            ->autofocus()
                    ]),
                Forms\Components\Section::make('Users Authentication')
                    ->description('User password section.')
                    ->schema([
                        Forms\Components\TextInput::make('password')->password()->confirmed()->required(),
                        Forms\Components\TextInput::make('password_confirmation')->password()->label('Confirm Password')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('is_admin')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Edit Active')
            ])
            ->filters([
                TernaryFilter::make('is_admin')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make('Deactivate')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn(Collection $users)=> $users->each->update(['active' => false]))
                        ->after(fn()=> Notification::make()
                        ->title('Saved successfully')
                        ->warning()
                        ->send()
                    )
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
