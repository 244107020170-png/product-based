<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatchResource\Pages;
use App\Models\Matchs;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MatchResource extends Resource
{
    protected static ?string $model = Matchs::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Match';

    protected static ?string $modelLabel = 'Match';

    protected static ?string $pluralModelLabel = 'Match';

    protected static string | \UnitEnum | null $navigationGroup = 'Monitoring Aktivitas';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Match')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255),
                    Select::make('field_id')
                        ->label('Lapangan')
                        ->relationship('field', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('created_by')
                        ->label('Pembuat')
                        ->relationship('creator', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    DatePicker::make('date')
                        ->label('Tanggal')
                        ->required(),
                    TimePicker::make('time')
                        ->label('Jam')
                        ->seconds(false)
                        ->required(),
                    TextInput::make('max_player')
                        ->label('Maks. Pemain')
                        ->numeric()
                        ->minValue(2)
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Pembuat')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('time')
                    ->label('Jam'),
                TextColumn::make('max_player')
                    ->label('Slot')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatches::route('/'),
            'edit' => Pages\EditMatch::route('/{record}/edit'),
        ];
    }
}
