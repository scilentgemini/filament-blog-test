<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Forms\Components\Group;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'All Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create a Post')
                    ->description('Create posts over here.')
                    ->schema([
                        TextInput::make('title')->required(),
                        TextInput::make('slug')->required(),
                        
                        Select::make('category_id')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->label('Category'),

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
                        Checkbox::make('published')->required(),
                    ]),
                    
                ])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail'),
                ColorColumn::make('color'),
                TextColumn::make('title'),
                TextColumn::make('slug'),

                TextColumn::make('category.name'),

                TextColumn::make('tags'),
                CheckboxColumn::make('published')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
