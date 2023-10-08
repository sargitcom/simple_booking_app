<?php

namespace App\Command;

use App\Application\Event\Application\UpdateReservationProjection\UpdateReservationProjection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:projections:update-reservation-projection', hidden: false)]
class UpdateReservationProjectionCommand extends Command
{
    public function __construct(private UpdateReservationProjection $updateReservationProjection) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->updateReservationProjection->updateProjection();
            return Command::FAILURE;
        } catch (Throwable $e) {
            dd($e->getMessage());
            return Command::FAILURE;
        }
    }
}
