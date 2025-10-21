<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('News Title')
                    ->helperText('Enter the title of the news article')
                    ->required(),
                RichEditor::make('content_en')
                    ->label('Content (English)')
                    ->helperText('Enter the content of the news article in English')
                    ->required()
                        ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike','link'],
                            ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                            ['undo', 'redo'],
                        ])
                    ->columnSpanFull(),
                RichEditor::make('content_es')
                    ->label('Content (Spanish)')
                    ->helperText('Enter the content of the news article in Spanish')
                    ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike','link'],
                            ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                            ['undo', 'redo'],
                        ])
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image_url')
                    ->label('News Image')
                    ->directory('news/images')
                    ->disk('public')
                    ->visibility('public')
                    ->image(),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->helperText('Toggle to activate or deactivate the news article')
                    ->default(true)
                    ->required(),
            ]);
    }
}
