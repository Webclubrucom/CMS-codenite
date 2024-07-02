<?php

declare(strict_types=1);

namespace System\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use System\Core\Helpers\Config;
use Throwable;

#[AsCommand(name: 'make:migrate', description: 'Запуск миграций', aliases: ['run:migrate', 'migrate'])]
class MigrateCommand extends Command
{
    private const string MIGRATIONS_TABLE = 'migrations';

    private Connection $connection;

    public function __construct(Connection $connection, ?string $name = null)
    {
        parent::__construct($name);
        $this->connection = $connection;
    }

    protected function configure(): void
    {
        $this->addArgument('option', InputArgument::OPTIONAL, 'Использование опции для действий с миграциями');
    }

    /**
     * @throws SchemaException
     * @throws Exception
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = microtime(true);
        $option = $input->getArgument('option');

        $io = new SymfonyStyle($input, $output);

        $this->connection->setAutoCommit(false);
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $migrationFiles = $this->getMigrationFiles();
        if ($option == 'reset') {
            $io->info('Отменяемые миграции.');
            $migrationsToApply = $appliedMigrations;
        } else {
            $io->info('Выполняемые миграции.');
            $migrationsToApply = array_values(array_diff($migrationFiles, $appliedMigrations));
        }

        if ($migrationsToApply) {
            $schema = new Schema();
            $sqlArray = [];

            $batch = $this->getMaxBatch();

            foreach ($migrationsToApply as $migration) {
                $sql = '';
                $migrationInstance = require BASE_DIR.Config::get('PATH_MIGRATIONS')."/$migration";
                if ($option != 'reset') {
                    $migrationInstance->up($schema);
                    $this->addMigration($migration, $batch);
                } else {
                    $schemaManager = $this->connection->createSchemaManager();
                    if ($schemaManager->tablesExist(self::MIGRATIONS_TABLE)) {
                        $schemaManager->dropTable(self::MIGRATIONS_TABLE);
                    }
                    $migrationInstance->down($schemaManager);
                }

                $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

                ProgressBar::setFormatDefinition('custom', ' %filename% %bar% %time% %message%');
                $progressBar = new ProgressBar($output);
                $progressBar->setFormat('custom');
                $progressBar->setBarCharacter('<comment>-</comment>');
                $progressBar->setBarWidth(2000);
                $progressBar->setMessage('Выполняется');
                $progressBar->setMessage('0','time');
                $progressBar->setMessage(str_replace('.php', '', $migration), 'filename');
                $progressBar->start();

                if ($sqlArray) {
                    $index = count($sqlArray) - 1;
                    if ($this->connection->executeQuery($sqlArray[$index])) {
                        for ($i = 0; $i < 100; $i++) {
                            $progressBar->advance();
                        }
                    }

                }

                $progressBar->setMessage('<info>Завершено</info>');
                $time = microtime(true) - $start;
                $time = (string)floor($time * 1000);

                $progressBar->setMessage($time.'ms','time');
                $progressBar->finish();

            }
            $io->newLine(3);
            return Command::SUCCESS;
        }

        $io->warning('Нет миграций для выполнения!');

        return Command::FAILURE;

    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (! $schemaManager->tablesExist(self::MIGRATIONS_TABLE)) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATIONS_TABLE);
            $table->addColumn('id', Types::INTEGER, [
                'unsigned' => true,
                'autoincrement' => true,
            ]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('batch', Types::INTEGER);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP',
            ]);
            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);
        }
    }

    /**
     * @throws Exception
     */
    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('migration')
            ->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles(): array
    {
        if (! file_exists(BASE_DIR.Config::get('PATH_MIGRATIONS'))) {
            mkdir(BASE_DIR.Config::get('PATH_MIGRATIONS'), 0777, true);
        }
        $migrationFiles = scandir(BASE_DIR.Config::get('PATH_MIGRATIONS'));

        $filteredFiles = array_filter($migrationFiles, function ($fileName) {
            return ! in_array($fileName, ['.', '..']);
        });

        return array_values($filteredFiles);
    }

    /**
     * @throws Exception
     */
    private function addMigration(string $migration, int $batch): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->insert(self::MIGRATIONS_TABLE)
            ->values(['migration' => ':migration', 'batch' => $batch])
            ->setParameter('migration', $migration)
            ->executeQuery();
    }

    /**
     * @throws Exception
     */
    private function getMaxBatch(): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $batchDb = $queryBuilder
            ->select('batch')
            ->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchFirstColumn();

        if ($batchDb) {
            return max($batchDb) + 1;
        }

        return 1;
    }
}
