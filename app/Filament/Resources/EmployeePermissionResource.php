<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePermissionResource\Pages;
use App\Filament\Resources\EmployeePermissionResource\RelationManagers;
use App\Models\EmployeePermission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeePermissionResource extends Resource
{
    protected static ?string $model = EmployeePermission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Cuti';
    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Cuti Pegawai')
                    ->description('Form input cuti pegawai')
                    ->schema([
                        Forms\Components\DatePicker::make('start_permission_date')
                            ->label('Tanggal Mulai Cuti')
                            ->required(),
                        Forms\Components\DatePicker::make('end_permission_date')
                            ->label('Tanggal Selesai Cuti')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeePermission', 'name')
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('permission_id')
                            ->relationship('permission', 'name')
                            ->label('Jenis Cuti')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('permission_desc')
                            ->label('Keterangan Cuti')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('scan_doc')
                            ->label('Dokumen Pendukung')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])->columns(2)
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
                Tables\Columns\TextColumn::make('start_permission_date')
                    ->label('Tanggal Mulai Cuti')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_permission_date')
                    ->label('Tanggal Selesai Cuti')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employeePermission.name')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('permission.name')
                    ->label('Jenis Cuti')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('permission_desc')
                    ->label('Keterangan Cuti')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scan_doc')
                    ->label('Data Pendukung')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListEmployeePermissions::route('/'),
            'create' => Pages\CreateEmployeePermission::route('/create'),
            'edit' => Pages\EditEmployeePermission::route('/{record}/edit'),
        ];
    }
}
