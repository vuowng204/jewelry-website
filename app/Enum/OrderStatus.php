<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Processing = 'processing';
    case Shipping = 'shipping';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Returned = 'returned';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Chờ xử lý',
            self::Confirmed => 'Đã xác nhận',
            self::Processing => 'Đang xử lý',
            self::Shipping => 'Đang giao',
            self::Completed => 'Hoàn thành',
            self::Cancelled => 'Đã huỷ',
            self::Returned => 'Trả hàng',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'info',
            self::Processing => 'primary',
            self::Shipping => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
            self::Returned => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Confirmed => 'heroicon-o-check-circle',
            self::Processing => 'heroicon-o-cog',
            self::Shipping => 'heroicon-o-truck',
            self::Completed => 'heroicon-o-check-badge',
            self::Cancelled => 'heroicon-o-x-circle',
            self::Returned => 'heroicon-o-arrow-uturn-left',
        };
    }

    // Danh sách cho phép chuyển trạng thái (dùng cho action)
    public static function allowedTransitions(self|string $current): array
    {
        if (is_string($current)) {
            $current = self::from($current);
        }

        return match ($current) {
            self::Pending => [self::Confirmed, self::Cancelled],
            self::Confirmed => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipping, self::Cancelled],
            self::Shipping => [self::Completed, self::Returned],
            self::Completed => [self::Returned],
            self::Cancelled => [],
            self::Returned => [],
        };
    }
}
