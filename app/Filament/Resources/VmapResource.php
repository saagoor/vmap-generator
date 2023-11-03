<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VmapResource\Pages;
use App\Models\Vmap;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VmapResource extends Resource
{
    protected static ?string $model = Vmap::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected function getMessages(): array
    {
        return [
            'data.name.min' => __('VMAPs'),
            'data.adBreaks.*.time_offset.required_without_all' => __('Required for midroll.'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->placeholder('Enter name')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Repeater::make('adBreaks')
                    ->relationship('adBreaks')
                    ->schema([
                        TextInput::make('vast_url')
                            ->required()
                            ->url()
                            ->placeholder('https://example.com/vast.xml')
                            ->columnSpan(2),
                        Select::make('break_type')
                            ->options([
                                'linear' => 'Video (linear)',
                                'nonlinear' => 'Overlay (non-linear)',
                            ])
                            ->default('linear')
                            ->required(),
                        TextInput::make('time_offset')
                            ->placeholder('Seconds')
                            ->rules(['required_without_all:is_pre_roll,is_post_roll'])
                            ->numeric(),
                        TextInput::make('repeat_after')
                            ->numeric()
                            ->placeholder('Seconds'),
                        Toggle::make('is_pre_roll')
                            ->label('Pre-roll')
                            ->default(false),
                        Toggle::make('is_post_roll')
                            ->label('Post-roll')
                            ->default(false),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ad_breaks_count')
                    ->label('Ad Breaks')
                    ->counts('adBreaks')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Open')
                    ->url(fn (Vmap $record): string => route('api.vmaps.show', $record))
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVmaps::route('/'),
            'create' => Pages\CreateVmap::route('/create'),
            'edit' => Pages\EditVmap::route('/{record}/edit'),
        ];
    }
}
