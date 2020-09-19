<?php
declare(strict_types=1);

namespace App\Util\Common;

use RuntimeException;

final class SimpleSafelyFileManipulator
{
    public function fileGetContents(string $pathToFile, bool $use_include_path = false, int $offset = 0, int $maxlen = null): string
    {
        $content = file_get_contents($pathToFile, $use_include_path, null, $offset, $maxlen);
        if ($content === false) {
            throw new RuntimeException('Problem with file reading in %s', 0, null);
        }

        return $content;
    }

    public function filePutStringContents(string $pathToFile, string $content, int $flags = 0): int
    {
        $result = file_put_contents($pathToFile, $content, $flags, null);
        if ($result === false) {
            throw new RuntimeException('Problem with file writing', 0, null);
        }

        return $result;
    }
}