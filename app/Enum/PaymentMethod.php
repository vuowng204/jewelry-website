<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Cod          = 'cod';
    case BankTransfer = 'bank_transfer';
    case Vnpay        = 'vnpay';
    case Momo         = 'momo';
    case Zalopay      = 'zalopay';
    case CreditCard   = 'credit_card';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cod          => 'Thanh toán khi nhận hàng (COD)',
            self::BankTransfer => 'Chuyển khoản ngân hàng',
            self::Vnpay        => 'VNPay',
            self::Momo         => 'Ví MoMo',
            self::Zalopay      => 'ZaloPay',
            self::CreditCard   => 'Thẻ tín dụng',
        };
    }
}