<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Create a Post')
                ->description('Create posts over here.')
                ->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('slug')
                    ->unique(ignoreRecord:true)
                    ->required(),

                    ColorPicker::make('color')->required(),

                    MarkdownEditor::make('content')->required()->columnSpanFull(),
                ])->columnSpan(2)->columns(2),

            Group::make()
            ->schema([
                Section::make('Image')
                ->collapsible()
                ->schema([
                    FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'), 
                ])->columnSpan(1),
                Section::make('Meta')
                ->schema([
                    TagsInput::make('tags')->required(),
                    Checkbox::make('published'),
                ]),
                
            ])
        ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\CheckboxColumn::make('published'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
