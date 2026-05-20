<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FieldResource\Pages;
use App\Models\Field;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Verifikasi Lapangan';

    protected static ?string $modelLabel = 'Lapangan';

    protected static ?string $pluralModelLabel = 'Verifikasi Lapangan';

    protected static string | \UnitEnum | null $navigationGroup = 'Manajemen Sistem';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Lapangan')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Lapangan')
                        ->required()
                        ->maxLength(255),
                    Select::make('owner_id')
                        ->label('Owner')
                        ->relationship('owner', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('location')
                        ->label('Lokasi')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('price_per_hour')
                        ->label('Harga per Jam')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Verifikasi Admin')
                ->schema([
                    Select::make('verification_status')
                        ->label('Status Verifikasi')
                        ->options([
                            'pending' => 'Menunggu',
                            'approved' => 'Terverifikasi',
                            'rejected' => 'Ditolak',
                        ])
                        ->default('pending')
                        ->required(),
                    Textarea::make('verification_notes')
                        ->label('Catatan Admin')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Lapangan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('price_per_hour')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('verification_status')
                    ->label('Verifikasi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    })
                    ->colors([
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'warning' => 'pending',
                    ])
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->label('Diverifikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Field $record): bool => $record->verification_status !== 'approved')
                    ->action(fn (Field $record): bool => $record->update([
                        'verification_status' => 'approved',
                        'verification_notes' => null,
                        'verified_at' => now(),
                        'verified_by' => auth()->id(),
                    ])),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('verification_notes')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->visible(fn (Field $record): bool => $record->verification_status !== 'rejected')
                    ->action(fn (Field $record, array $data): bool => $record->update([
                        'verification_status' => 'rejected',
                        'verification_notes' => $data['verification_notes'],
                        'verified_at' => now(),
                        'verified_by' => auth()->id(),
                    ])),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFields::route('/'),
            'create' => Pages\CreateField::route('/create'),
            'edit' => Pages\EditField::route('/{record}/edit'),
        ];
    }
}
