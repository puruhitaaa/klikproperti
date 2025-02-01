<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RenterDocumentResource\Pages;
use App\Models\RenterDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RenterDocumentResource extends Resource
{
    protected static ?string $model = RenterDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Document Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('renter', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('document_type')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'verified' => 'Verified',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\DateTimePicker::make('expiry_date')
                            ->nullable(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->maxLength(65535)
                            ->visible(fn (string $context, ?Model $record) =>
                                $context === 'edit' &&
                                ($record?->status === 'rejected' || $record?->status === 'pending')
                            ),
                    ])->columns(2),

                Forms\Components\Section::make('Verification')
                    ->schema([
                        Forms\Components\DateTimePicker::make('verified_at')
                            ->visible(fn (string $context, ?Model $record) =>
                                $context === 'edit' && $record?->status === 'verified'
                            ),
                        Forms\Components\Select::make('verified_by')
                            ->relationship('verifier', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (string $context, ?Model $record) =>
                                $context === 'edit' && $record?->status === 'verified'
                            ),
                    ])->columns(2),

                Forms\Components\Section::make('Document File')
                    ->schema([
                        Forms\Components\FileUpload::make('document')
                            ->acceptedFileTypes(['application/pdf'])
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('renter.name')
                    ->label('Renter')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) =>
                        $record->expiry_date && $record->expiry_date->isPast() ? 'danger' : null
                    ),
                Tables\Columns\TextColumn::make('verifier.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('verified_at')
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('renter')
                    ->relationship('renter', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('verifier')
                    ->relationship('verifier', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '<', now())
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (RenterDocument $record) => $record->getFirstMediaUrl('document'))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListRenterDocuments::route('/'),
            'create' => Pages\CreateRenterDocument::route('/create'),
            'edit' => Pages\EditRenterDocument::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['document_type', 'title', 'renter.name'];
    }
}
