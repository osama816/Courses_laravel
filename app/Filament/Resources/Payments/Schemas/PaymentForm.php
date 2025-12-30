<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_id')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->options(['paymob' => 'Paymob', 'cash' => 'Cash', 'myfatoorah' => 'Myfatoorah'])
                    ->default('cash')
                    ->required(),
                TextInput::make('amount')
                    ->required(),
                Select::make('status')
                    ->options(['paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded', 'pending' => 'Pending'])
                    ->default('pending')
                    ->required(),
                TextInput::make('transaction_id')
                    ->required(),
                DateTimePicker::make('paid_at'),
            ]);
    }
}
