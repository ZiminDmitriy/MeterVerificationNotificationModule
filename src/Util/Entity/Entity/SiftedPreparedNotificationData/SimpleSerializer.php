<?php
declare(strict_types=1);

namespace App\Util\Entity\Entity\SiftedPreparedNotificationData;

use App\Entity\Entity\SiftedPreparedNotificationData\SiftedPreparedNotificationDataCollector;
use App\Serializer\NameConverter\Entity\Entity\SiftedPreparedNotificationData\NameConverter;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

final class SimpleSerializer
{
    public function withCsvFormatSerialize(
        SiftedPreparedNotificationDataCollector $siftedPreparedNotificationDataCollector,
        string $group,
        bool $no_headers_key = false
    ): string
    {
        $serializer = $this->getConfiguredSerializer($no_headers_key);

        return $serializer->serialize($siftedPreparedNotificationDataCollector->getArrayCopy(), 'csv', ['groups' => [$group]]);
    }

    private function getConfiguredSerializer(bool $no_headers_key = false): Serializer
    {
        return
            new Serializer(
                [
                    new GetSetMethodNormalizer(
                        new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader(new DocParser()))), new NameConverter()
                    ),
                    new ArrayDenormalizer()
                ],
                [new CsvEncoder([CsvEncoder::NO_HEADERS_KEY => $no_headers_key, CsvEncoder::DELIMITER_KEY => ';'])]
            );
    }
}