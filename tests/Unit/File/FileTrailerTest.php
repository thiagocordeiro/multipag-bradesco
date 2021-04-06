<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File;

use Bradesco\Multipag\CNAB\CNAB;
use Bradesco\Multipag\CNAB\FileTrailer;
use PHPUnit\Framework\TestCase;

class FileTrailerTest extends TestCase
{
    public function testRenderFileTrailer(): void
    {
        $numBatches = 12;
        $numEntries = 5899;

        $header = FileTrailer::render($numBatches, $numEntries);

        $this->assertEquals(240, strlen($header));
        $this->assertEquals(
            join('', [
                // Control
                '01.9' => '237',
                '02.9' => '9999',
                '03.9' => '9',

                '04.9' => '         ',

                '05.9' => '000012',
                '06.9' => '005923',
                '07.9' => '000000',

                '08.0' => CNAB::alpha('', 205),
            ]),
            $header,
        );
    }
}
