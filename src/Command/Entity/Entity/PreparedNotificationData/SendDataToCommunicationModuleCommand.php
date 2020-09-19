<?php
declare(strict_types=1);

namespace App\Command\Entity\Entity\PreparedNotificationData;

use App\Handler\Command\Entity\PreparedNotificationData\SendDataToCommunicationModuleCommandHandler;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SendDataToCommunicationModuleCommand extends Command
{
    use LockableTrait;

    protected static $defaultName = 'app:sendDataToCommunicationModule';

    private SendDataToCommunicationModuleCommandHandler $sendDataToCommunicationModuleCommandHandler;

    public function __construct(
        SendDataToCommunicationModuleCommandHandler $sendDataToCommunicationModuleCommandHandler,
        string $name = null
    )
    {
        $this->sendDataToCommunicationModuleCommandHandler = $sendDataToCommunicationModuleCommandHandler;

        parent::__construct($name);
    }

    public function configure(): void
    {
        $this->setDescription('Send data to Communication module for notification');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('Start');

        try {
            if ($this->lock(null, false)) {
                $this->sendDataToCommunicationModuleCommandHandler->sendData();
            }

            $output->writeln('');
            $output->write('Finish');
        } catch (Exception $exception) {
            $output->writeln('');
            $output->write('The Process is not finished, because an exception was thrown:');
            $output->writeln('');
            $output->write($exception->getMessage());
            $output->writeln('');
            $output->write($exception->getTraceAsString());
        }

        $this->release();

        return 0;
    }
}