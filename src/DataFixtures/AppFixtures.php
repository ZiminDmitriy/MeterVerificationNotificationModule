<?php

namespace App\DataFixtures;

use App\Entity\Entity\PreparedNotificationData\ForPreparedNotificationData\Id;
use App\Entity\Entity\PreparedNotificationData\PreparedNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Email;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterFactoryNumber;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterModel;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Phone;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private int $quantityOfEntities = 25000;

    private int $quantityOfEntitiesForSaveInDbAtOnce = 1000;

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= $this->quantityOfEntities; ++$i) {
            if ($i % $this->quantityOfEntitiesForSaveInDbAtOnce == 0) {
                $manager->flush();
                $manager->clear();
                gc_collect_cycles();
            }

            $notificationData = $this->createPreparedNotificationData($i);

            $manager->persist($notificationData);
        }

        $manager->flush();
        $manager->clear();
        gc_collect_cycles();
    }

    private function createPreparedNotificationData(int $i): PreparedNotificationData
    {
        $nowDateTime = new DateTimeImmutable('now', null);

        return
            new PreparedNotificationData(
                Id::create(),
                new Contract('asdvsdkmwDCt'.(string)rand(1, $this->quantityOfEntities).'aJsdvHa'),
                new MeterId((string)$i),
                new MeterNextCheckDate(
                    new DateTimeImmutable(
                        sprintf(
                            '%s-%s-%s',
                            (string)rand(
                                (int)$nowDateTime->sub(new DateInterval('P1Y'))->format('Y'),
                                (int)$nowDateTime->add(new DateInterval('P1Y'))->format('Y')
                            ),
                            rand(1, 12),
                            rand(1, 28)
                        ),
                        null
                    ),
                ),
                new Email('email@yandex.ru'),
                new Phone('12312312312'),
                new MeterFactoryNumber('factory number'),
                new MeterModel('meter model')
            );
    }
}
