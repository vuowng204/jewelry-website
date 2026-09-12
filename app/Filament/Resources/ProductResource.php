<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Quản lý sản phẩm';

    public static function form(Form $form): Form
    {
         return $form->schema([
        Forms\Components\Section::make('Thông tin cơ bản')->schema([
            Forms\Components\Select::make('category_id')
                ->label('Danh mục')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label('Tên sản phẩm')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Set $set) =>
                    $set('slug', Str::slug($state))
                ),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Textarea::make('description')
                ->label('Mô tả')
                ->rows(5)
                ->columnSpanFull(),
        ])->columns(2),

        Forms\Components\Section::make('Giá & Kho')->schema([
            Forms\Components\TextInput::make('price')
                ->label('Giá gốc (VNĐ)')
                ->numeric()
                ->required()
                ->prefix('₫'),

            Forms\Components\TextInput::make('sale_price')
                ->label('Giá khuyến mãi')
                ->numeric()
                ->prefix('₫'),

            Forms\Components\TextInput::make('stock')
                ->label('Tồn kho')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('material')
                ->label('Chất liệu')
                ->placeholder('VD: Vàng 18K, Bạc 925, Kim cương'),
        ])->columns(2),

        Forms\Components\Section::make('Hình ảnh')->schema([
            Forms\Components\FileUpload::make('thumbnail')
                ->label('Ảnh chính')
                ->image()
                ->directory('products')
                ->imageEditor()
                ->required(),

            Forms\Components\FileUpload::make('gallery')
                ->label('Ảnh phụ (nhiều ảnh)')
                ->image()
                ->multiple()
                ->directory('products/gallery')
                ->dehydrated(false) // không lưu trực tiếp vào bảng products
                ->columnSpanFull(),
        ]),

        Forms\Components\Section::make('Trạng thái')->schema([
            Forms\Components\Toggle::make('is_active')
                ->label('Đang bán')
                ->default(true),

            Forms\Components\Toggle::make('is_featured')
                ->label('Sản phẩm nổi bật'),
        ])->columns(2),
    ]);
    }

    public static function table(Table $table): Table
    {
       return $table
        ->columns([
            Tables\Columns\ImageColumn::make('thumbnail')
                ->label('Ảnh')
                ->circular(),

            Tables\Columns\TextColumn::make('name')
                ->label('Tên')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Danh mục')
                ->badge(),

            Tables\Columns\TextColumn::make('price')
                ->label('Giá')
                ->money('VND', divideBy: 1)
                ->sortable(),

            Tables\Columns\TextColumn::make('stock')
                ->label('Kho')
                ->badge()
                ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),

            Tables\Columns\IconColumn::make('is_active')
                ->label('Bán')
                ->boolean(),

            Tables\Columns\IconColumn::make('is_featured')
                ->label('Nổi bật')
                ->boolean(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d/m/Y H:i')
                ->label('Ngày tạo')
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('category')
                ->relationship('category', 'name'),
            Tables\Filters\TernaryFilter::make('is_active')->label('Đang bán'),
            Tables\Filters\TernaryFilter::make('is_featured')->label('Nổi bật'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
