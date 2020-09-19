<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractManagerController as BaseManagerController;
use App\Exception\Controller\Api\ApiException;
use App\Util\Common\SafelyArrayJsonEncoder;
use phpDocumentor\Reflection\Types\This;

abstract class AbstractManagerController extends BaseManagerController
{
    private SafelyArrayJsonEncoder $safelyArrayJsonEncoder;

    public function __construct()
    {
        $this->safelyArrayJsonEncoder = new SafelyArrayJsonEncoder();
    }

    protected function safelyArrayJsonEncode(array $subject, int $options = 0, int $depth = 512): string
    {
        return $this->safelyArrayJsonEncoder->encode($subject, $options, $depth);
    }

    protected function safelyStringJsonDecode(string $subject, bool $assoc = true, int $depth = 512, int $options = 0): array
    {
        return $this->safelyArrayJsonEncoder->decode($subject, $assoc, $depth, $options);
    }
}