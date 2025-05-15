<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('passenger_id')
                    ->label('Passenger')
                    ->relationship('passenger', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('payment_gateway')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('card_number')
                    ->label('Card Number')
                    ->maxLength(255)
                    ->nullable(), // Remember to handle encryption
                Forms\Components\TextInput::make('expiry_date')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('cvv')
                    ->label('CVV')
                    ->maxLength(4)
                    ->nullable(), // Remember to handle encryption
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('passenger.full_name')
                    ->label('Passenger')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_gateway')->searchable(),
                Tables\Columns\TextColumn::make('card_number')->label('Card Number')->nullable(), // Consider masking
                Tables\Columns\TextColumn::make('expiry_date')->nullable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('passenger')
                    ->relationship('passenger', 'full_name'),
                Tables\Filters\SelectFilter::make('payment_gateway')
                    ->options(array_unique(PaymentMethod::pluck('payment_gateway')->toArray())),
            ])
            ->actions([
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
