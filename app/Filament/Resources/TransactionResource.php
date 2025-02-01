<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        Forms\Components\TextInput::make('transaction_code')
                            ->default(fn () => Transaction::generateTransactionCode())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('property_id')
                            ->relationship('property', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('buyer_id')
                            ->relationship('buyer', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('seller_id')
                            ->relationship('seller', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'rent' => 'Rent',
                                'sale' => 'Sale',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_type')
                            ->options([
                                'full' => 'Full Payment',
                                'installment' => 'Installment',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->default(fn ($get) => $get('property_id')
                                ? \App\Models\Property::find($get('property_id'))?->price ?? 0
                                : 0
                            )
                            ->dehydrated(),
                        Forms\Components\TextInput::make('platform_fee')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('service_fee')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\Select::make('voucher_id')
                            ->relationship('voucher', 'name')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\TextInput::make('discount_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('final_amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\TextInput::make('midtrans_transaction_id')
                            ->maxLength(255)
                            ->disabled()
                            ->visible(fn (string $context) => $context === 'view'),
                        Forms\Components\KeyValue::make('midtrans_response')
                            ->disabled()
                            ->visible(fn (string $context) => $context === 'view'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('property.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('buyer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_amount')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'info',
                        'pending' => 'warning',
                        'failed', 'refunded' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'rent' => 'Rent',
                        'sale' => 'Sale',
                    ]),
                Tables\Filters\SelectFilter::make('payment_type')
                    ->options([
                        'full' => 'Full Payment',
                        'installment' => 'Installment',
                    ]),
                Tables\Filters\SelectFilter::make('property')
                    ->relationship('property', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('buyer')
                    ->relationship('buyer', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('check_payment')
                    ->icon('heroicon-o-credit-card')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Transaction $record) => $record->status === 'processing')
                    ->action(function (Transaction $record) {
                        // TODO: Implement Midtrans payment check
                        // $record->checkMidtransStatus();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['pending', 'processing'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['transaction_code', 'property.title', 'buyer.name', 'seller.name'];
    }
}
