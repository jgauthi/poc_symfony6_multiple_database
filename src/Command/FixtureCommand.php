<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fixtures',
    description: 'Load fixtures for all databases',
)]
class FixtureCommand extends Command
{
    // Entity Manager name (groups should be used the same value)
    private const LIST_DATABASE = ['default', 'second'];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $console = 'php '.realpath(__DIR__.'/../../bin/console');
        $command = 'doctrine:fixtures:load';

        // No working with official solution, the arguments in ArrayInput are not recognise!!
        // $symfonyCommand = $this->getApplication()->find($command);
        // $symfonyCommand->run(new ArrayInput(['--em' => 'default', '--group' => 'default', '--no-interaction' => null]), $output);

        foreach (self::LIST_DATABASE as $database) {
            $output->write('Install fixtures for database: '.$database);
            $output->writeln((string) shell_exec("{$console} {$command} --em={$database} --group={$database} --no-interaction"));
        }

        return Command::SUCCESS;
    }
}
