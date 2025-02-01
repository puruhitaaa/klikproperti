<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Setting Details')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options([
                                'string' => 'String',
                                'integer' => 'Integer',
                                'float' => 'Float',
                                'boolean' => 'Boolean',
                                'json' => 'JSON',
                            ])
                            ->required()
                            ->default('string')
                            ->live(),
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->visible(fn ($get) => $get('type') === 'string'),
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->visible(fn ($get) => $get('type') === 'integer'),
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->visible(fn ($get) => $get('type') === 'float'),
                        Forms\Components\Toggle::make('value')
                            ->required()
                            ->visible(fn ($get) => $get('type') === 'boolean'),
                        Forms\Components\Textarea::make('value')
                            ->required()
                            ->visible(fn ($get) => $get('type') === 'json')
                            ->helperText('Enter valid JSON'),
                        Forms\Components\Select::make('group')
                            ->options([
                                'general' => 'General',
                                'fees' => 'Platform Fees',
                                'documents' => 'Document Requirements',
                                'notifications' => 'Notifications',
                                'integrations' => 'Third-party Integrations',
                            ])
                            ->required()
                            ->default('general'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_public')
                            ->required()
                            ->default(false)
                            ->helperText('Public settings can be accessed without authentication'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn (Setting $record) => match ($record->type) {
                        'boolean' => $record->value ? 'True' : 'False',
                        'json' => 'JSON Data',
                        default => $record->value,
                    })
                    ->wrap(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'fees' => 'Platform Fees',
                        'documents' => 'Document Requirements',
                        'notifications' => 'Notifications',
                        'integrations' => 'Third-party Integrations',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'string' => 'String',
                        'integer' => 'Integer',
                        'float' => 'Float',
                        'boolean' => 'Boolean',
                        'json' => 'JSON',
                    ]),
                Tables\Filters\TernaryFilter::make('is_public'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['key', 'value', 'description'];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()?->hasRole('admin') ?? false;
    }
}
