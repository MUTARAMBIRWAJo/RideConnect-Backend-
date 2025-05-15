<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ride_id')
                    ->label('Ride')
                    ->relationship('ride', 'id') // You might want to display more info about the ride
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('payment_method_id')
                    ->label('Payment Method')
                    ->relationship('paymentMethod', 'payment_gateway') // Display payment gateway info
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\TextInput::make('transaction_id')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('ride.id')->label('Ride ID')->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.payment_gateway')->label('Payment Method')->nullable(),
                Tables\Columns\TextColumn::make('transaction_id')->searchable()->nullable(),
                Tables\Columns\TextColumn::make('amount')->sortable(),
                Tables\Columns\TextColumn::make('status')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ride')
                    ->relationship('ride', 'id'),
                Tables\Filters\SelectFilter::make('paymentMethod')
                    ->relationship('paymentMethod', 'payment_gateway'),
                Tables\Filters\SelectFilter::make('status')
                    ->options(array_unique(Transaction::pluck('status')->toArray())),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
