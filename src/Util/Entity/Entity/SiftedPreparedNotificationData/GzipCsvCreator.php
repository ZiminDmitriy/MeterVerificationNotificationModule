<?php
declare(strict_types=1);

namespace App\Util\Entity\Entity\SiftedPreparedNotificationData;

use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDate;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Service\Entity\SiftedPreparedNotificationData\GetterService;
use App\Util\Common\SafelyGzipEncoder;
use App\Util\Common\SimpleSafelyFileManipulator;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\HttpKernel\KernelInterface;

final class GzipCsvCreator
{
    public const PATH_TO_CSV_AFTER_ROOT_DIR = 'var/csv';

    public const CSV_FILENAME = 'notificationData.csv';

    public const CSV_GZ_FILENAME = 'notificationData.csv.gz';

    private EntityManagerInterface $entityManager;

    private GetterService $getterService;

    private SimpleSafelyFileManipulator $simpleSafelyFileManipulator;

    private SafelyGzipEncoder $safelyGzipEncoder;

    private string $pathToCSVFile;

    private string $pathToCSVGZFile;

    private bool $isDataPrepared = false;

    public function __construct(
        EntityManagerInterface $entityManager,
        GetterService $getterService,
        KernelInterface $kernel
    )
    {
        $this->entityManager = $entityManager;
        $this->getterService = $getterService;
        $this->simpleSafelyFileManipulator = new SimpleSafelyFileManipulator();
        $this->safelyGzipEncoder = new SafelyGzipEncoder();
        $this->pathToCSVFile = sprintf('%s/%s/%s', $kernel->getProjectDir(), self::PATH_TO_CSV_AFTER_ROOT_DIR, self::CSV_FILENAME);
        $this->pathToCSVGZFile = sprintf('%s/%s/%s', $kernel->getProjectDir(), self::PATH_TO_CSV_AFTER_ROOT_DIR, self::CSV_GZ_FILENAME);
    }

    public function prepareSendingData(AbstractSendingDate $sendingDate): void
    {
        $sendingDate = new SendingDate($sendingDate->getDateTimeImmutableValue());

        $this->createCsvFile($sendingDate);

        $content = $this->simpleSafelyFileManipulator->fileGetContents($this->pathToCSVFile, false);

        $encodedContent = $this->safelyGzipEncoder->encode($content, 9, FORCE_GZIP);

        $this->simpleSafelyFileManipulator->filePutStringContents($this->pathToCSVGZFile, $encodedContent, FILE_APPEND|LOCK_EX);

        $this->isDataPrepared = true;
    }

    private function createCsvFile(SendingDate $sendingDate): void
    {
        $simpleSerializer = new SimpleSerializer();

        $this->deleteExistingFiles();

        $limit = 5000;
        $count = $this->getterService->countBySendingDate($sendingDate);

        for ($offset = 0; $offset <= ($count - 1); $offset += $limit) {
            $this->createOrUpdateCsvFile($sendingDate, $simpleSerializer, $limit, $offset);

            $this->entityManager->clear(null);
            gc_collect_cycles();
        }
    }

    private function deleteExistingFiles(): void
    {
        if (file_exists($pathToFile = $this->pathToCSVFile)) {
            unlink($pathToFile);
        }

        if (file_exists($pathToFile = $this->pathToCSVGZFile)) {
            unlink($pathToFile);
        }
    }

    private function createOrUpdateCsvFile(
        SendingDate $sendingDate,
        SimpleSerializer $simpleSerializer,
        int $limit,
        int $offset
    ): void
    {
        $siftedPreparedNotificationDataCollector = $this->getterService->getAllBySendingDate($sendingDate, $limit, $offset);

        if (!$siftedPreparedNotificationDataCollector->isEmpty()) {
            $serializedSiftedPreparedNotificationData = $simpleSerializer->withCsvFormatSerialize(
                $siftedPreparedNotificationDataCollector, 'send_to_communicationModule', $offset === 0 ? false : true
            );

            $this->simpleSafelyFileManipulator->filePutStringContents($this->pathToCSVFile, $serializedSiftedPreparedNotificationData, FILE_APPEND | LOCK_EX);
        }
    }

    public function getPreparedData(): string
    {
        if (!$this->isDataPrepared) {
            throw new LogicException(
                sprintf(
                    'The method %s has been invoked before invoking %s::%s method', __METHOD__, get_called_class(), 'prepareSendingData()'
                ), 0, null
            );
        }
        if (!file_exists($pathToFile = $this->pathToCSVGZFile)) {
            throw new LogicException(sprintf('File %s does not exist', $pathToFile), 0, null);
        }

        return $this->simpleSafelyFileManipulator->fileGetContents($pathToFile, false);
    }
}