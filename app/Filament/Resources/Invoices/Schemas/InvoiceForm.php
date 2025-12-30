<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_number')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_id')
                    ->required()
                    ->numeric(),
                TextInput::make('booking_id')
                    ->required()
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['paid' => 'Paid', 'pending' => 'Pending', 'cancelled' => 'Cancelled'])
                    ->default('paid')
                    ->required(),
                TextInput::make('invoice_path'),
                DateTimePicker::make('issued_at')
                    ->required(),
            ]);
    }
}
