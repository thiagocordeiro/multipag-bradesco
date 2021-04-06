<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File;

use Bradesco\Multipag\CNAB\CNAB;
use Bradesco\Multipag\Exception\InvalidValue;
use PHPUnit\Framework\TestCase;

class CNABTest extends TestCase
{
    /**
     * @dataProvider valuesDataset
     */
    public function testRenderStringWithPaddings(
        float $value,
        int $num,
        string $alpha,
        string $padding,
        string $expected
    ): void {
        $array = [
            CNAB::value($value, 10),
            CNAB::num($num, 10),
            CNAB::alpha($alpha, 10),
            CNAB::pad($padding, 10, '?'),
        ];

        $rendered = implode('', $array);

        $this->assertEquals($expected, $rendered);
    }

    public function testWhenValueLengthIsGreaterThanMaximumThenThrowAnException(): void
    {
        $value = 345678912.12;

        $this->expectException(InvalidValue::class);
        $this->expectExceptionMessage(
            "Value '34567891212' is invalid. The maximum length for this value is 10, 11 given",
        );

        CNAB::value($value, 10);
    }

    public function testWhenValueIsNegativeThenThrowAnException(): void
    {
        $value = -0.01;

        $this->expectException(InvalidValue::class);
        $this->expectExceptionMessage('Value \'-0.01\' is invalid. The value must be zero or grater');

        CNAB::value($value, 10);
    }

    public function testWhenValueIsZeroThenReturnRendered(): void
    {
        $value = 0;

        $rendered = CNAB::value($value, 3);

        $this->assertEquals('000', $rendered);
    }

    public function testWhenNumLengthIsGreaterThanMaximumThenThrowAnException(): void
    {
        $value = 12345678912;

        $this->expectException(InvalidValue::class);
        $this->expectExceptionMessage("Value '{$value}' is invalid. The maximum length for this value is 10, 11 given");

        CNAB::num($value, 10);
    }

    public function testWhenAlphaLengthIsGreaterThanMaximumThenStripAdditionalChars(): void
    {
        $value = 'TEST FOO BAR';

        $result = CNAB::alpha($value, 10);

        $this->assertEquals('TEST FOO B', $result);
    }

    public function testWhenPadLengthIsGreaterThanMaximumThenThrowAnException(): void
    {
        $value = '#############';

        $this->expectException(InvalidValue::class);
        $this->expectExceptionMessage("Value '{$value}' is invalid. The maximum length for this value is 10, 13 given");

        CNAB::pad($value, 10, '?');
    }

    /**
     * @return array<string, array<int|string|float>>
     */
    public function valuesDataset(): array
    {
        return [
            'result with auto filled values' => [
                'value' => 123.95,
                'num' => 123,
                'alpha' => 'TEST',
                'pad' => '###',
                'rendered' => '00000123950000000123TEST      ###???????',
            ],
            'result without auto filled values' => [
                'value' => 12345678.95,
                'num' => 1234567890,
                'alpha' => 'ABCDEFGHIJ',
                'pad' => '##########',
                'rendered' => '12345678951234567890ABCDEFGHIJ##########',
            ],
        ];
    }
}
