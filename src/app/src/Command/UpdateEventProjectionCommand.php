<?php

namespace App\Command;

use App\Application\Event\Application\UpdateEventProjection\UpdateEventProjection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:projections:update-event-projection', hidden: false)]
class UpdateEventProjectionCommand extends Command
{
    public function __construct(private UpdateEventProjection $updateEventProjection) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->updateEventProjection->updateProjection();
            return Command::FAILURE;
        } catch (Throwable $e) {
            var_dump($e->getMessage());
            return Command::FAILURE;
        }
    }
}
