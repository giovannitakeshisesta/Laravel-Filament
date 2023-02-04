<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use App\Models\Student;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestStudents extends BaseWidget
{
    // Positioning: order of elements in the dashboard 
    protected static ?int $sort = 2;
    // Sizing :
    protected int | string | array $columnSpan = 'full';

    // define the query
    protected function getTableQuery(): Builder
    {
        return Student::query()
            ->latest()
            ->take(5);
    }

    // what to show
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->sortable()
                ->searchable(),

            TextColumn::make('class.name')
                ->sortable()
                ->searchable(),

            TextColumn::make('section.name')
                ->sortable()
                ->searchable()
        ];
    }

    // pagination : dont show more lines than requested
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}