<?php
/*******************************************************************************
 * @name: Command Make Migration for Multiple databases
 * @author: Jgauthi, created at [28july2023], url: <github.com/jgauthi/poc_symfony6_multiple_database>
 * @version: 1.0
 * @Requirements:
    - PHP version >= 8.2+, Symfony 6.2+
    - Doctrine with multiple configuration: https://symfony.com/doc/6.2/doctrine/multiple_entity_managers.html

 *******************************************************************************/
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{ArrayInput, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'make:migration',
    description: 'Enhance the command for support multiple databases.',
)]
class MakeMigrationCommand extends Command
{
    /** @var bool[] */
    private array $requirement;

    public function __construct(KernelInterface $kernel, ?string $name = null)
    {
        parent::__construct($name);

        $this->requirement = [
            'doctrine_migration' => array_key_exists('DoctrineMigrationsBundle', $kernel->getBundles()),
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$this->getApplication()) {
            return Command::INVALID;
        } elseif (!$this->requirement['doctrine_migration']) {
            $io->error('The bundle DoctrineMigrationsBundle is inactive or the APP_ENV value is not dev.');

            return Command::INVALID;
        }

        foreach (FixtureCommand::LIST_DATABASE as $database) {
            try {
                $io->title('Creation migration for database: '.$database);
                $symfonyCommand = $this->getApplication()->find('doctrine:migrations:diff');
                $symfonyCommand->run(new ArrayInput(['--em' => $database]), $output);
            } catch (\Throwable $exception) {
                $io->writeln($exception->getMessage());
            }
        }

        return Command::INVALID;
    }
}
