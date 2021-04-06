<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB;

use Bradesco\Multipag\Assert;
use Bradesco\Multipag\Exception\InvalidValue;

abstract class CNAB
{
    public const G001_237_BRADESCO = '237';
    public const G002_0000_FILE_HEADER = '0000';
    public const G002_9999_FILE_TRAILER = '9999';
    public const G003_1_FILE_HEADER = '0';
    public const G003_1_BATCH_HEADER = '1';
    public const G003_3_REGISTER_TYPE_DETAIL = '3';
    public const G003_5_BATCH_TRAILER = '5';
    public const G003_9_FILE_TRAILER = '9';
    public const G015_1_REMITTANCE = '1';
    public const G019_089_FILE_LAYOUT_VERSION = '089';
    public const G020_01600_FILE_DENSITY = '01600';
    public const G025_98_SERVICE_TYPE_OTHERS = '70';
    public const G028_C_OPERATION_TYPE = 'C';
    public const G029_01_ENTRY_TYPE_ACCOUNT_CREDIT = '01';
    public const G029_03_ENTRY_TYPE_DOC_TED = '03';
    public const G030_045_LAYOUT_VERSION = '045';
    public const G039_SEGMENT_TYPE_A = 'A';
    public const G039_SEGMENT_TYPE_B = 'B';
    public const G040_BRL_CURRENCY_TYPE = 'BRL';
    public const G060_0_MOVEMENT_TYPE_INCLUDE = '0';
    public const G061_00_MOVEMENT_CODE_INCLUDE_RELEASED = '00';

    public const P001_COMPENSATION_CHAMBER_TED = '018';
    public const P006_0_DO_NOT_NOTIFY = '0';
    public const P011_00010_TRANSFER_REASON_ACCOUNT_CREDIT = '00010';
    public const P014_01_PAYMENT_METHOD = '01';

    public static function value(float $value, int $length): string
    {
        Assert::that($value >= 0, InvalidValue::valueIsNegative($value));
        $content = number_format($value, 2, '', '');

        return self::num($content, $length);
    }

    public static function num(string|float|int $value, int $length): string
    {
        $content = preg_replace('/[.\-]/', '', "{$value}");
        $content = "{$content}";
        Assert::that(strlen("$content") <= $length, InvalidValue::valueIsToLong("$value", $length));

        return str_pad("{$content}", $length, '0', STR_PAD_LEFT);
    }

    public static function alpha(string|int $value, int $length): string
    {
        $content = mb_strtoupper("{$value}");
        $content = self::stripAccents("{$content}");
        $content = preg_replace('/[^0-9A-Z\s]/', '', "{$content}");
        $content = str_pad("{$content}", $length, ' ', STR_PAD_RIGHT);

        return substr($content, 0, $length);
    }

    public static function pad(string|int $content, int $length, string $fill): string
    {
        $content = "{$content}";
        Assert::that(strlen($content) <= $length, InvalidValue::valueIsToLong($content, $length));

        return str_pad("{$content}", $length, $fill, STR_PAD_RIGHT);
    }

    /**
     * @param array<mixed> $data
     */
    public static function join(array $data, string $separator = ''): string
    {
        return join($separator, $data);
    }

    private static function stripAccents(string $stripAccents): string
    {
        return strtr(
            utf8_decode($stripAccents),
            utf8_decode('ÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
            'AAAAACEEEEIIIINOOOOOUUUUY',
        );
    }
}
