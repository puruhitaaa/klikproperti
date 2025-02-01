<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppraisalResource\Pages;
use App\Models\Appraisal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppraisalResource extends Resource
{
    protected static ?string $model = Appraisal::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Property Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->relationship('property', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('appraised_value')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Toggle::make('is_recommended')
                            ->required()
                            ->visible(fn (string $context, ?Appraisal $record) =>
                                $context === 'edit' && $record?->status === 'completed'
                            ),
                    ])->columns(2),

                Forms\Components\Section::make('Assessment Details')
                    ->schema([
                        Forms\Components\Textarea::make('comments')
                            ->maxLength(65535),
                        Forms\Components\KeyValue::make('assessment_details')
                            ->keyLabel('Criteria')
                            ->valueLabel('Assessment')
                            ->addButtonLabel('Add Criteria')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DateTimePicker::make('scheduled_date')
                            ->required()
                            ->minDate(now()),
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->visible(fn (string $context, ?Appraisal $record) =>
                                $context === 'edit' && $record?->status === 'completed'
                            ),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appraised_value')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\IconColumn::make('is_recommended')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
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
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('property')
                    ->relationship('property', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('agent')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('recommended')
                    ->query(fn (Builder $query): Builder => $query->where('is_recommended', true)),
                Tables\Filters\Filter::make('scheduled_today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('scheduled_date', today())),
                Tables\Filters\Filter::make('completed_this_month')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('completed_at', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('complete')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Appraisal $record) => $record->status === 'pending')
                    ->action(function (Appraisal $record) {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);

                        $record->property->update([
                            'last_appraisal_date' => now(),
                        ]);
                    }),
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
            'index' => Pages\ListAppraisals::route('/'),
            'create' => Pages\CreateAppraisal::route('/create'),
            'edit' => Pages\EditAppraisal::route('/{record}/edit'),
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
        return ['property.title', 'agent.name', 'comments'];
    }
}
