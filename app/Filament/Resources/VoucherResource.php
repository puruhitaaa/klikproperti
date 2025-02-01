<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Marketing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Voucher Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'percentage' => 'Percentage',
                                'fixed_amount' => 'Fixed Amount',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->prefix(fn (string $get) => $get('type') === 'percentage' ? '%' : 'Rp'),
                    ])->columns(2),

                Forms\Components\Section::make('Usage Limits')
                    ->schema([
                        Forms\Components\TextInput::make('min_transaction_amount')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('max_discount_amount')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('usage_limit')
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\TextInput::make('used_count')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->visible(fn (string $context) => $context === 'edit'),
                    ])->columns(2),

                Forms\Components\Section::make('Validity')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true),
                        Forms\Components\DateTimePicker::make('valid_from')
                            ->required(),
                        Forms\Components\DateTimePicker::make('valid_until')
                            ->after('valid_from'),
                        Forms\Components\Select::make('applicable_property_types')
                            ->multiple()
                            ->options([
                                'sale' => 'For Sale',
                                'rent' => 'For Rent',
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn (Voucher $record) =>
                        $record->type === 'percentage'
                            ? "{$record->value}%"
                            : "Rp " . number_format($record->value, 0, ',', '.')
                    ),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Usage')
                    ->formatStateUsing(fn (Voucher $record) =>
                        $record->usage_limit
                            ? "{$record->used_count} / {$record->usage_limit}"
                            : $record->used_count
                    ),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('valid_from')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\Filter::make('valid')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('valid_from', '<=', now())
                        ->where(function ($q) {
                            $q->whereNull('valid_until')
                                ->orWhere('valid_until', '>=', now());
                        })
                    ),
                Tables\Filters\Filter::make('has_remaining_usage')
                    ->query(fn (Builder $query): Builder => $query
                        ->where(function ($q) {
                            $q->whereNull('usage_limit')
                                ->orWhereRaw('used_count < usage_limit');
                        })
                    ),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed_amount' => 'Fixed Amount',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('copy_code')
                    ->icon('heroicon-o-clipboard')
                    ->label('Copy Code')
                    ->action(fn (Voucher $record) => null) // Handled by JavaScript
                    ->extraAttributes([
                        'x-on:click' => 'navigator.clipboard.writeText($el.dataset.code)',
                        'data-code' => fn (Voucher $record) => $record->code,
                    ]),
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'name', 'description'];
    }
}
