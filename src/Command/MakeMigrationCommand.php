<?php
/*******************************************************************************
 * @name: Command Make Migration for Multiple databases
 * @author: Jgauthi, created at [28july2023], url: <github.com/jgauthi/poc_symfony6_multiple_database>
 * @version: 1.1
 * @Requirements:
    - PHP version >= 8.2+, Symfony 6.3+
    - Doctrine with multiple configuration: https://symfony.com/doc/6.2/doctrine/multiple_entity_managers.html

 *******************************************************************************/
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\{Autowire, AsAlias};
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

#[AsAlias(id: 'maker.maker.make_migration')]
#[AsCommand(
    name: 'make:migration',
    description: 'Enhance the command for support multiple databases.',
)]
class MakeMigrationCommand extends Command
{
    // Entity Manager name (groups should be used the same value)
    public const array LIST_DATABASE = ['main', 'second'];

    /** @var bool[] */
    protected array $requirement;
    protected string $command = 'doctrine:migrations:diff';

    public function __construct(
        KernelInterface $kernel,
        #[Autowire('%kernel.project_dir%')] protected string $projectDir,
    ) {
        parent::__construct();

        $this->requirement = [
            'doctrine_migration' => array_key_exists('DoctrineMigrationsBundle', $kernel->getBundles()),
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$this->requirement['doctrine_migration']) {
            $io->error('The bundle DoctrineMigrationsBundle is inactive.');
            return Command::FAILURE;
        }

        $nbError = 0;
        foreach (static::LIST_DATABASE as $database) {
            $io->title('Creation migration for database: '.$database);

            $commandLine = [
                'php',
                'bin/console',
                $this->command,
                '--no-interaction --em=' . $database,
            ];

            $configFile = "config/migrations/{$database}.yaml";
            if (file_exists($this->projectDir.'/'.$configFile)) {
                $commandLine[] = '--configuration=' . $configFile;
            }
            if ($env = $input->getOption('env')) {
                $commandLine[] = '--env=' . $env;
            }

            $process = (new Process($commandLine))->setTimeout(300);
            $process->run(function ($type, $buffer) use ($io) {
                $buffer = trim($buffer);
                if (!$buffer) {
                    return;
                }
                $io->writeln(trim($buffer));
            });

            if (!$process->isSuccessful()) {
                $io->error('Failed');
                $nbError++;
            }
        }

        return empty($nbError) ? Command::SUCCESS : Command::FAILURE;
    }
}
