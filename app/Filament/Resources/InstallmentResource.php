<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstallmentResource\Pages;
use App\Models\Installment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InstallmentResource extends Resource
{
    protected static ?string $model = Installment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Installment Information')
                    ->schema([
                        Forms\Components\Select::make('transaction_id')
                            ->relationship('transaction', 'transaction_code')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('installment_number')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\DatePicker::make('due_date')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
                                'failed' => 'Failed',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\TextInput::make('late_fee')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->visible(fn (string $context, ?Installment $record) =>
                                $context === 'view' && $record?->late_fee > 0
                            ),
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
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->disabled()
                            ->visible(fn (string $context) => $context === 'view'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.transaction_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('installment_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('late_fee')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color(fn (Installment $record) =>
                        $record->isOverdue() ? 'danger' : null
                    ),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'processing' => 'info',
                        'pending' => 'warning',
                        'overdue', 'failed' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
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
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\Filter::make('due_this_month')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('due_date', now())),
                Tables\Filters\Filter::make('overdue')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('status', 'pending')
                        ->where('due_date', '<', now())
                    ),
                Tables\Filters\Filter::make('paid_this_month')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('paid_at', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('check_payment')
                    ->icon('heroicon-o-credit-card')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Installment $record) => $record->status === 'processing')
                    ->action(function (Installment $record) {
                        // TODO: Implement Midtrans payment check
                        // $record->checkMidtransStatus();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('due_date');
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
            'index' => Pages\ListInstallments::route('/'),
            'create' => Pages\CreateInstallment::route('/create'),
            'edit' => Pages\EditInstallment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['pending', 'overdue'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueCount = static::getModel()::where('status', 'overdue')->count();
        return $overdueCount > 0 ? 'danger' : 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['transaction.transaction_code', 'status'];
    }
}
