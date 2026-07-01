<?php

namespace App\Enums;

enum CurrencyCodes: string
{
    case EUR = 'EUR';
    case GBP = 'GBP';
    case USD = 'USD';
    case CAD = 'CAD';
    case AUD = 'AUD';
    case NZD = 'NZD';
    case JPY = 'JPY';
    case CNY = 'CNY';
    case HKD = 'HKD';
    case SGD = 'SGD';
    case CHF = 'CHF';
    case SEK = 'SEK';
    case NOK = 'NOK';
    case DKK = 'DKK';
    case PLN = 'PLN';
    case CZK = 'CZK';
    case HUF = 'HUF';
    case TRY = 'TRY';
    case ZAR = 'ZAR';
    case BRL = 'BRL';
    case MXN = 'MXN';
    case INR = 'INR';

    public static function values(): array
    {
        return array_map(
            fn (self $currencyCode) => $currencyCode->value,
            self::cases(),
        );
    }
}
