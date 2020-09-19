<?php
declare(strict_types=1);

namespace App\Service\Entity\PreparedNotificationData;

use App\Entity\Entity\PreparedNotificationData\PreparedNotificationDataCollector;
use App\Repository\PreparedNotificationDataRepository;

final class GetterService
{
    private PreparedNotificationDataRepository $preparedNotificationDataRepository;

    public function __construct(PreparedNotificationDataRepository $preparedNotificationDataRepository)
    {
        $this->preparedNotificationDataRepository = $preparedNotificationDataRepository;
    }

    public function getAll(int $limit, int $offset): PreparedNotificationDataCollector
    {
        $preparedNotificationDataRegistry = $this->preparedNotificationDataRepository->findBy([], [], $limit, $offset);

        return
            !empty($preparedNotificationDataRegistry) ?
                PreparedNotificationDataCollector::createFulledCollector(...$preparedNotificationDataRegistry) :
                PreparedNotificationDataCollector::createEmptyCollector();
    }

    public function countOfAll(): int
    {
        return $this->preparedNotificationDataRepository->count([]);
    }
}