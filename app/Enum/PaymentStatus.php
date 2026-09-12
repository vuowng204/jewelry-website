<?php
 namespace App\Enum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasColor, HasLabel
{
   case Unpaid   = 'unpaid';
    case Paid     = 'paid';
    case Refunded = 'refunded';
    case Failed   = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Unpaid   => 'Chưa thanh toán',
            self::Paid     => 'Đã thanh toán',
            self::Refunded => 'Đã hoàn tiền',
            self::Failed   => 'Thất bại',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Unpaid   => 'warning',
            self::Paid     => 'success',
            self::Refunded => 'info',
            self::Failed   => 'danger',
        };
    }
}
