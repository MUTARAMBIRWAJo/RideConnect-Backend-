<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RideResource\Pages;
use App\Filament\Resources\RideResource\RelationManagers;
use App\Models\Ride;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RideResource extends Resource
{
    protected static ?string $model = Ride::class;

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
                Forms\Components\Select::make('rider_id')
                    ->label('Rider')
                    ->relationship('rider', 'full_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\TextInput::make('origin_latitude')
                    ->label('Origin Latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('origin_longitude')
                    ->label('Origin Longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('destination_latitude')
                    ->label('Destination Latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('destination_longitude')
                    ->label('Destination Longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('pickup_time')
                    ->label('Pickup Time')
                    ->native(false)
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\TextInput::make('fare')
                    ->label('Fare')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
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
            Tables\Columns\TextColumn::make('passenger.full_name')
                ->label('Passenger')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('rider.full_name')
                ->label('Rider')
                ->searchable()
                ->sortable()
                ->nullable(),
            Tables\Columns\TextColumn::make('origin_latitude')->label('Origin Lat'),
            Tables\Columns\TextColumn::make('origin_longitude')->label('Origin Long'),
            Tables\Columns\TextColumn::make('destination_latitude')->label('Dest Lat'),
            Tables\Columns\TextColumn::make('destination_longitude')->label('Dest Long'),
            Tables\Columns\TextColumn::make('pickup_time')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('status')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('fare')->sortable()->nullable(),
            Tables\Columns\TextColumn::make('payment_status')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Created'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('passenger')
                ->relationship('passenger', 'full_name'),
            Tables\Filters\SelectFilter::make('rider')
                ->relationship('rider', 'full_name'),
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'accepted' => 'Accepted',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
            Tables\Filters\SelectFilter::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                ]),
            Tables\Filters\Filter::make('pickup_time')
                ->form([
                    Forms\Components\DateTimePicker::make('pickup_time_from')
                        ->label('Pickup Time From'),
                    Forms\Components\DateTimePicker::make('pickup_time_until')
                        ->label('Pickup Time Until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['pickup_time_from'],
                            fn (Builder $query, $date): Builder => $query->where('pickup_time', '>=', $date),
                        )
                        ->when(
                            $data['pickup_time_until'],
                            fn (Builder $query, $date): Builder => $query->where('pickup_time', '<=', $date),
                        );
                }),
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
            'index' => Pages\ListRides::route('/'),
            'create' => Pages\CreateRide::route('/create'),
            'edit' => Pages\EditRide::route('/{record}/edit'),
        ];
    }
}
