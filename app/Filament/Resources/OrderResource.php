<?php

namespace App\Filament\Resources;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Enum\PaymentMethod;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Quản lý đơn hàng';
    protected static ?string $navigationLabel = 'Order';
    protected static ?string $modelLabel = 'Đơn hàng';
    protected static ?string $pluralModelLabel = 'Đơn hàng';
    protected static ?int $navigationSort = 1;

   // thông báo so lượng đơn hàng ở bên sidebar(đây là hàm tiện ích của filament)
    public static function getNavigationBadge(): ?string
    {
        return (string) Order::where('status', OrderStatus::Pending)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin khách hàng')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('order_code')
                        ->label('Mã đơn hàng')->disabled(),
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Tên khách hàng')->disabled(),
                    Forms\Components\TextInput::make('customer_phone')
                        ->label('Số điện thoại')->disabled(),
                    Forms\Components\TextInput::make('customer_email')
                        ->label('Email')->disabled(),
                    Forms\Components\Textarea::make('customer_address')
                        ->label('Địa chỉ')->columnSpanFull()->disabled(),
                    ]),

            Forms\Components\Section::make('Trạng thái đơn hàng')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Trạng thái')
                        ->options(collect(OrderStatus::cases())->mapWithKeys(
                            fn($s) => [$s->value => $s->getLabel()]
                        ))
                        ->required(),
                    Forms\Components\Select::make('payment_status')
                        ->label('Trạng thái thanh toán')
                        ->options(collect(PaymentStatus::cases())->mapWithKeys(
                            fn($s) => [$s->value => $s->getLabel()]
                        ))
                        ->required(),
                ]),
            Forms\Components\Section::make('Thanh toán')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('discount_amount')
                        ->label('Giảm giá')->numeric()->disabled(),
                    Forms\Components\TextInput::make('shipping_fee')
                        ->label('Phí ship')->numeric()->disabled(),
                    Forms\Components\TextInput::make('total_amount')
                        ->label('Tổng tiền')->numeric()->disabled(),
                    Forms\Components\Select::make('payment_method')
                        ->label('Phương thức thanh toán')
                        ->options(collect(PaymentMethod::cases())->mapWithKeys(
                            fn ($s) => [$s->value => $s->getLabel()]
                        ))
                        ->disabled(),
                ]),

            Forms\Components\Section::make('Ghi chú nội bộ')
                ->schema([
                    Forms\Components\Textarea::make('admin_note')
                        ->label('Ghi chú admin')
                        ->rows(3)
                        ->placeholder('Nhập ghi chú nội bộ cho đơn hàng này...'),
                ]),

        ]);
    }

   public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Mã đơn')->searchable()->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Khách hàng')->searchable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('SĐT')->searchable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Tổng tiền')
                    ->money('VND', locale: 'vi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')->badge(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Thanh toán')->badge(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Số SP')->counts('items')->badge()->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày đặt')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(
                        fn ($s) => [$s->value => $s->getLabel()]
                    )),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Thanh toán')
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(
                        fn ($s) => [$s->value => $s->getLabel()]
                    )),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Từ ngày'),
                        Forms\Components\DatePicker::make('until')->label('Đến ngày'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['until'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                // Nút "Cập nhật trạng thái" - dropdown các bước tiếp theo
                Tables\Actions\Action::make('updateStatus')
                    ->label('Chuyển trạng thái')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Order $record) => count(OrderStatus::allowedTransitions($record->status)) > 0)
                    ->form(fn (Order $record) => [
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái mới')
                            ->options(collect(OrderStatus::allowedTransitions($record->status))
                                ->mapWithKeys(fn ($s) => [$s->value => $s->getLabel()]))
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $newStatus = OrderStatus::from($data['status']);
                        $updates = ['status' => $newStatus];

                        // Ghi mốc thời gian tương ứng
                        match ($newStatus) {
                            OrderStatus::Confirmed  => $updates['confirmed_at'] = now(),
                            OrderStatus::Shipping   => $updates['shipped_at']   = now(),
                            OrderStatus::Completed  => $updates['completed_at'] = now(),
                            OrderStatus::Cancelled  => $updates['cancelled_at'] = now(),
                            default                 => null,
                        };

                        $record->update($updates);
                    })
                    ->successNotificationTitle('Đã cập nhật trạng thái'),

                Tables\Actions\EditAction::make()->label('Sửa'),
                Tables\Actions\DeleteAction::make()->label('Xoá'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Xoá đã chọn'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}