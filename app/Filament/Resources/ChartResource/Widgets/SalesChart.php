<?php

namespace App\Filament\Resources\ChartResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SalesChart extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // ...
            )
            ->columns([
                // ...
            ]);
    }
}
