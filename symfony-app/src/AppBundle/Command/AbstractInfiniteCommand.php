<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * Abstract command class with some convenience methdods.
 *
 * @package AppBundle\Command
 */
abstract class AbstractInfiniteCommand extends ContainerAwareCommand
{
    /** @var int Tick interval in milliseconds */
    protected $interval = 2500;

    /** @var int Tick counter */
    protected $ticks = 0;

    /** @var bool */
    protected $shouldTerminate = false;

    /**
     * Initializes the signal handlers for terminating the command.
     */
    protected function configure()
    {
        declare(ticks = 1);
        pcntl_signal(SIGTERM, [$this, 'terminate']);
        pcntl_signal(SIGINT, [$this, 'terminate']);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (!$this->shouldTerminate) {
            $lastTick = microtime(true);

            $this->logMemoryUsage($output);

            $this->tick($input, $output);

            /* Sleep, so the tick function gets invoked every $this->interval ms */
            $msToSleep = (int)($this->interval - (microtime(true) - $lastTick) * 100);

            /* Count all ticks */
            $this->ticks++;

            if ($msToSleep > 0) {
                $output->writeln('sleep until next tick: ' . $msToSleep . 'ms');
                usleep($msToSleep * 1000);
            }
        }

        $output->writeln('command terminated');
    }

    /**
     * Abstract function, which should hold all the commands logic.
     */
    abstract protected function tick(InputInterface $input, OutputInterface $output);

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    public function getDoctrine()
    {
        if (!$this->getContainer()->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->getContainer()->get('doctrine');
    }

    /**
     * Indicates that the command should better terminate soon.
     */
    public function terminate()
    {
        $this->shouldTerminate = true;
    }

    /**
     * @param OutputInterface $output
     */
    protected function logMemoryUsage(OutputInterface $output)
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getContainer()->get('kernel');
        if ('dev' === $kernel->getEnvironment()) {
            $usage = memory_get_usage(true) / 1024 / 1024;
            $output->writeln('Memory usage: ' . $usage . ' MB');
        }
    }
}
