<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Property Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('user_id')
                            ->relationship('owner', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('property_type_id')
                            ->relationship('propertyType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\Select::make('type')
                            ->options([
                                'sale' => 'For Sale',
                                'rent' => 'For Rent',
                            ])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'pending' => 'Pending',
                                'rented' => 'Rented',
                                'sold' => 'Sold',
                            ])
                            ->default('available')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Location Details')
                    ->schema([
                        Forms\Components\TextInput::make('location_address')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->required(),
                        Forms\Components\TextInput::make('state')
                            ->required(),
                        Forms\Components\TextInput::make('postal_code')
                            ->required(),
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),

                Forms\Components\Section::make('Property Details')
                    ->schema([
                        Forms\Components\TextInput::make('bedrooms')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('bathrooms')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('area')
                            ->label('Area (m²)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TagsInput::make('features')
                            ->separator(','),
                        Forms\Components\TextInput::make('service_fee_percentage')
                            ->numeric()
                            ->default(0)
                            ->suffix('%'),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Verification')
                    ->schema([
                        Forms\Components\Toggle::make('is_recommended')
                            ->required(),
                        Forms\Components\Toggle::make('documents_verified')
                            ->required(),
                        Forms\Components\DateTimePicker::make('last_appraisal_date'),
                    ]),
                Forms\Components\Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->image()
                            ->collection('property-images')
                            ->multiple()
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->stacked(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('propertyType.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('price')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'pending' => 'warning',
                        'rented', 'sold' => 'danger',
                    }),
                Tables\Columns\IconColumn::make('is_recommended')
                    ->boolean(),
                Tables\Columns\IconColumn::make('documents_verified')
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
                Tables\Filters\SelectFilter::make('property_type_id')
                    ->relationship('propertyType', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Property Type'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'sale' => 'For Sale',
                        'rent' => 'For Rent',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'pending' => 'Pending',
                        'rented' => 'Rented',
                        'sold' => 'Sold',
                    ]),
                Tables\Filters\Filter::make('is_recommended')
                    ->query(fn (Builder $query): Builder => $query->where('is_recommended', true))
                    ->label('Recommended Only'),
                Tables\Filters\Filter::make('documents_verified')
                    ->query(fn (Builder $query): Builder => $query->where('documents_verified', true))
                    ->label('Verified Only'),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'location_address', 'city', 'owner.name'];
    }
}
