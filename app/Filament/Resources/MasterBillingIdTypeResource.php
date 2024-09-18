<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingIdTypeResource\Pages;
use App\Filament\Resources\MasterBillingIdTypeResource\RelationManagers;
use App\Models\MasterBillingIdType;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingIdTypeResource extends Resource
{
    protected static ?string $model = MasterBillingIdType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Tipe Kartu Identitas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Pemilihan Identitas')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Tipe Identitas')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('desc')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
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
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('desc')
                    ->label('Keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListMasterBillingIdTypes::route('/'),
            'create' => Pages\CreateMasterBillingIdType::route('/create'),
            'edit' => Pages\EditMasterBillingIdType::route('/{record}/edit'),
        ];
    }
}
