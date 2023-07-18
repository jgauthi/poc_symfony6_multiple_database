<?php
/*******************************************************************************
 * @name: Command Fixture for Multiple databases
 * @author: Jgauthi, created at [28july2023], url: <github.com/jgauthi/poc_symfony6_multiple_database>
 * @version: 1.0
 * @Requirements:
    - PHP version >= 8.2+, Symfony 6.2+
    - Doctrine with multiple configuration: https://symfony.com/doc/6.2/doctrine/multiple_entity_managers.html
    - DoctrineMigrationsMultipleDatabaseBundle

 *******************************************************************************/
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:fixtures',
    description: 'Load fixtures for all databases (only for dev environnement)',
)]
class FixtureCommand extends Command
{
    // Entity Manager name (groups should be used the same value)
    public const LIST_DATABASE = ['main', 'second'];

    /** @var bool[] */
    private array $requirement;

    public function __construct(KernelInterface $kernel, ?string $name = null)
    {
        parent::__construct($name);

        $this->requirement = [
            'doctrine_fixture' => array_key_exists('DoctrineFixturesBundle', $kernel->getBundles()),
            'doctrine_migration' => array_key_exists('DoctrineMigrationsBundle', $kernel->getBundles()),
            'doctrine_multiple_migration' => array_key_exists('DoctrineMigrationsMultipleDatabaseBundle', $kernel->getBundles()),
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$this->requirement['doctrine_fixture']) {
            $io->error('The bundle DoctrineFixturesBundle is inactive or the APP_ENV value is not dev');

            return Command::INVALID;
        } elseif (!$this->requirement['doctrine_migration']) {
            $io->error('The bundle DoctrineMigrationsBundle is inactive.');

            return Command::INVALID;
        } elseif (!$this->requirement['doctrine_multiple_migration']) {
            $io->error('The bundle DoctrineMigrationsMultipleDatabaseBundle is inactive');

            return Command::INVALID;
        }

        $console = 'php '.realpath(__DIR__.'/../../bin/console');
        $command = 'doctrine:fixtures:load';

        // No working with official solution, the arguments in ArrayInput are not recognise!!
        // $symfonyCommand = $this->getApplication()->find($command);
        // $symfonyCommand->run(new ArrayInput(['--em' => 'default', '--group' => 'default', '--no-interaction' => null]), $output);

        foreach (self::LIST_DATABASE as $database) {
            $io->title('Install fixtures for database: '.$database);
            $io->writeln((string) shell_exec("{$console} {$command} --em={$database} --group={$database} --no-interaction"));
        }

        return Command::SUCCESS;
    }
}
