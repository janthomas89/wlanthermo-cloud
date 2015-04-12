<?php

namespace AppBundle\Command;


use AppBundle\Service\HealthCheckService;
use AppBundle\Service\NotificationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HealthCheckCommand
 * Command for checking the "health" of the system.
 *
 * @package AppBundle\Command
 */
class HealthCheckCommand extends ContainerAwareCommand
{
    /**
     * Configures the command.
     */
    protected function configure()
    {
        $this
            ->setName('app:healthcheck')
            ->setDescription('Wlanthermo healthcheck command');
    }

    /**
     * Executes the health check.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Execute health check:');

        /** @var HealthCheckService $service */
        $service = $this->getContainer()->get('health_check_service');

        $results = $service->execute();
        foreach ($results as $key => $result) {
            $output->writeln($key . ': '. ($result ? 'true' : 'false' ));
        }
    }
}
