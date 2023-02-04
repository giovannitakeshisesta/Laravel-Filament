<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = "People";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus()
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('phone_number')
                    ->required()
                    ->tel()
                    // ->unique(),
                    ->unique(ignoreRecord: true),
                TextInput::make('address')
                    ->required(),

                Select::make('class_id')
                    ->relationship('class', 'name')
                    ->reactive(), // has to be reactive for the next select input

                Select::make('section_id')
                    ->label('Select Section')
                    ->options(function (callable $get) {
                        $classId = $get('class_id');

                        if ($classId) {
                            return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                        }
                    })

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone_number')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('address')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->wrap(),

                TextColumn::make('class.name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('section.name')
                    ->sortable()
                    ->searchable()
            ])

            //----------------------------------------------------------------
            ->filters([
                // components to show 
                Filter::make('class-section-filter')
                    ->form([
                        Select::make('class_id')
                            ->label('Filter By Class')
                            ->placeholder('Select a Class')
                            ->options(
                                Classes::pluck('name', 'id')->toArray()
                            )

                            // reset the select every time it changes (set section_id to null)
                            ->afterStateUpdated(
                                fn (callable $set) => $set('section_id', null)
                            ),

                        Select::make('section_id')
                            ->label('Filter By Section')
                            ->placeholder('Select a Section')
                            ->options(
                                function (callable $get) {
                                    $classId = $get('class_id');

                                    if ($classId) {
                                        return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                                    }
                                }
                            ),
                    ])
                    // logic of the query 
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['class_id'],
                                // $record= element selected, return all elements whose class_id===$record
                                fn (Builder $query, $record): Builder => $query->where('class_id', $record),
                            )
                            ->when(
                                $data['section_id'],
                                fn (Builder $query, $record): Builder => $query->where('section_id', $record),
                            );
                    })

            ])

            //----------------------------------------------------------------
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    // badges : student counter
    protected static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }
}
