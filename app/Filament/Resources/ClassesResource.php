<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Classes;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClassesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClassesResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;

class ClassesResource extends Resource
{
    protected static ?string $model = Classes::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    // a static form() method is used to build the forms on the Create and Edit pages:
    // here we define the general structure of the form                               
    // passing the components we want for the form                                    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus()
                    ->unique()
                    // ->unique(ignoreRecord: true)
                    ->placeholder('Enter a Class Name'),
            ]);
    }

    // a static table() method that is used to build the table on the List page:
    // here we define the general structure of the table:columns,filters, actions,bulkActions
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            // show delete edit btns each row
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make() 
            ])
            // what to do when we select multiple records
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // here we define the relations one to many
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClasses::route('/create'),
            'edit' => Pages\EditClasses::route('/{record}/edit'),
        ];
    }
}
