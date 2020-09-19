<?php
declare(strict_types=1);

namespace App\Util\Common;

use RuntimeException;

final class SafelyGzipEncoder
{
    public function encode(string $content, int $level = -1, int $encoding_mode = FORCE_GZIP): string
    {
        $encodedContent = gzencode($content, $level, $encoding_mode);
        if ($encodedContent === false) {
            throw new RuntimeException('Problem with file encoding in %s', 0, null);
        }

        return $encodedContent;
    }
}