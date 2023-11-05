<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VmapResource\Pages;
use App\Models\Vmap;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VmapResource extends Resource
{
    protected static ?string $model = Vmap::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->addActionLabel('Add ad break')
                    ->cloneable()
                    ->schema([
                        TextInput::make('vast_url')
                            ->required()
                            ->url()
                            ->placeholder('https://example.com/vast.xml')
                            ->columnSpan(2),
                        Select::make('category')
                            ->label('Type')
                            ->required()
                            ->options([
                                'preroll' => 'Preroll',
                                'midroll' => 'Midroll',
                                'postroll' => 'Postroll',
                            ])
                            ->default('preroll')
                            ->live(),
                        TextInput::make('time_offset')
                            ->placeholder('Seconds')
                            ->numeric()
                            ->requiredIf('category', 'midroll')
                            ->minValue(60)
                            ->hidden(fn (Get $get): bool => $get('category') !== 'midroll'),
                        TextInput::make('repeat_after')
                            ->placeholder('Seconds')
                            ->numeric()
                            ->minValue(60)
                            ->hidden(fn (Get $get): bool => $get('category') !== 'midroll'),
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->copyable()
                    ->tooltip('Click to copy url')
                    ->state(function (Vmap $record): string {
                        return route('api.vmaps.show', $record);
                    }),
                Tables\Columns\TextColumn::make('ad_breaks_count')
                    ->label('Vast')
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
