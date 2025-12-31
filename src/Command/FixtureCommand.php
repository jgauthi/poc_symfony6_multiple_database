<?php
/*******************************************************************************
 * @name: Command Fixture for Multiple databases
 * @author: Jgauthi, created at [28july2023], url: <github.com/jgauthi/poc_symfony6_multiple_database>
 * @version: 1.1
 * @Requirements:
    - PHP version >= 8.3+, Symfony 6.4+
    - Doctrine with multiple configuration: https://symfony.com/doc/6.4/doctrine/multiple_entity_managers.html

 *******************************************************************************/

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:fixtures',
    description: 'Load fixtures for all configured databases.',
)]
class FixtureCommand extends Command
{
    // On réutilise la même liste que pour les migrations
    private const array DATABASES = MakeMigrationCommand::LIST_DATABASE;

    protected function configure(): void
    {
        $this
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
            ->addOption('group', 'g', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Only load fixtures that belong to this group.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nbError = 0;

        foreach (self::DATABASES as $database) {
            $io->title("Loading fixtures for database: $database");

            // Construction de la commande de base
            $commandLine = [
                'php',
                'bin/console',
                'doctrine:fixtures:load',
                '--em=' . $database,
                '--group=' . $database,
                '--no-interaction'
            ];

            // Transmission de l'option --append
            if ($input->getOption('append')) {
                $commandLine[] = '--append';
            }

            // Transmission de l'environnement
            if ($env = $input->getOption('env')) {
                $commandLine[] = '--env=' . $env;
            }

            $process = (new Process($commandLine))->setTimeout(600);
            $process->run(function ($type, $buffer) use ($output) {
                $output->write($buffer);
            });

            if (!$process->isSuccessful()) {
                $io->error("Failed to load fixtures for $database");
                $nbError++;
            } else {
                $io->success("Fixtures loaded for $database");
            }
        }

        return $nbError === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}