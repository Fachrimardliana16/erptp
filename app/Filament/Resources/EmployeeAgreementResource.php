<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeAgreementResource\Pages;
use App\Filament\Resources\EmployeeAgreementResource\RelationManagers;
use App\Models\EmployeeAgreement;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeAgreementResource extends Resource
{
    protected static ?string $model = EmployeeAgreement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Perjanjian Kontrak Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Perjanjian Kontrak Pegawai')
                    ->description('Input data perjanjian kontrak pegawai.')
                    ->schema([
                        Forms\Components\TextInput::make('agreement_number')
                            ->label('Nomor Perjanjian Kontrak')
                            ->maxLength(255),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('agreementEmployee', 'name')
                            ->label('Nama Pegawai')
                            ->required(),
                        Forms\Components\Select::make('agreement_id')
                            ->relationship('agreement', 'name')
                            ->label('Status Kontrak')
                            ->required(),
                        Forms\Components\Select::make('employee_position_id')
                            ->relationship('agreementPosition', 'name')
                            ->label('Jabatan')
                            ->required(),
                        Forms\Components\Select::make('status_employemnts_id')
                            ->relationship('agreementStatus', 'name')
                            ->label('Status Pegawai')
                            ->required(),
                        Forms\Components\DatePicker::make('agreement_date_start')
                            ->label('Tanggal Mulai Perjanjian')
                            ->required(),
                        Forms\Components\DatePicker::make('agreement_date_end')
                            ->label('Tanggal Akhir Perjanjian')
                            ->required(),
                        Forms\Components\FileUpload::make('docs')
                            ->directory('Perjanjian Kontrak')
                            ->label('Lampiran Dokumen'),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreement_number')
                    ->label('Nomor Surat Perjanjian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreementEmployee.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreement.name')
                    ->label('Perjanjian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreementPosition.name')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreementStatus.name')
                    ->label('Status Kepegawaian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreement_date_start')
                    ->label('Tanggal Mulai Perjanjian Kontrak')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agreement_date_end')
                    ->label('Tanggal Akhir Perjanjian Kontrak')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('docs')
                    ->label('Lampiran Dokumen')
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
            'index' => Pages\ListEmployeeAgreements::route('/'),
            'create' => Pages\CreateEmployeeAgreement::route('/create'),
            'edit' => Pages\EditEmployeeAgreement::route('/{record}/edit'),
        ];
    }
}
