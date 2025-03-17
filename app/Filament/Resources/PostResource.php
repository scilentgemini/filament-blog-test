<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'All Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Create New Post')->tabs([
                    Tab::make('Tab 1')
                    ->icon('heroicon-m-inbox')
                    ->iconPosition(IconPosition::After)
                    ->schema([
                        TextInput::make('title')->required(),
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true)
                            ->required(),

                        Select::make('category_id')
                            ->label('Category')->required()
                            // ->options(Category::all()->pluck('name', 'id')),
                            ->relationship('category', 'name')
                            ->searchable(),

                        ColorPicker::make('color')->required(),
                    ]),

                    Tab::make('Content')->schema([
                        MarkdownEditor::make('content')->required()->columnSpanFull(),

                    ]),
                    Tab::make('Meta')->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),

                        Section::make('Meta')
                            ->schema([
                                TagsInput::make('tags')->required(),
                                Checkbox::make('published'),
                            ]),

                    ]),
                ])->columnSpanFull()->persistTabInQueryString(),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->sortable()->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('thumbnail')->toggleable(),
                ColorColumn::make('color')->toggleable(),
                TextColumn::make('title')
                    ->sortable()->searchable()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->sortable()->searchable()
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->sortable()->searchable()
                    ->toggleable(),

                TextColumn::make('tags')->toggleable(),
                CheckboxColumn::make('published')->toggleable(),

                TextColumn::make('created_at')
                    ->label('Published on')
                    ->date()
                    ->sortable()->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('Published Posts')
                ->query(
                    function (Builder $query): Builder {
                        return $query->where('published', true);
                    }
                ),
                SelectFilter::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            AuthorsRelationManager::class
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
