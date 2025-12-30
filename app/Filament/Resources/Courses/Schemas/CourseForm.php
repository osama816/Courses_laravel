<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Language')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description.en')
                                    ->label('Description')
                                    ->required()
                                    ->rows(4),
                            ]),
                        Tab::make('Arabic')
                            ->schema([
                                TextInput::make('title.ar')
                                    ->label('العنوان')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description.ar')
                                    ->label('الوصف')
                                    ->required()
                                    ->rows(4),
                            ]),
                    ])
                    ->columnSpanFull(),

                FileUpload::make('image_url')
                    ->label('Course Image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('courses')
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull(),
                
                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->maxValue(99999.99),
                
                \Filament\Forms\Components\Select::make('level')
                    ->label('Level')
                    ->required()
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                
                TextInput::make('total_seats')
                    ->label('Total Seats')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(1000),
                
                TextInput::make('available_seats')
                    ->label('Available Seats')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(1000),
                
                TextInput::make('rating')
                    ->label('Rating')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(5)
                    ->step(0.1),
                
                TextInput::make('duration')
                    ->label('Duration')
                    ->placeholder('e.g., 6 weeks, 40 hours'),
                
                \Filament\Forms\Components\Select::make('instructor_id')
                    ->label('Instructor')
                    ->required()
                    ->options(\App\Models\Instructor::join('users', 'instructors.user_id', '=', 'users.id')->pluck('users.name', 'instructors.id'))
                    ->searchable(),
                
                \Filament\Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
