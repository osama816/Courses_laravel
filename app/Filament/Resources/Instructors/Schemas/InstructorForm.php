<?php

namespace App\Filament\Resources\Instructors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InstructorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('bio')
                    ->required(),
                TextInput::make('avatar_url')
                    ->url()
                    ->required(),
            ]);
    }
}
