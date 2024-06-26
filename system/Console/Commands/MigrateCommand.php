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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(name: 'make:migrate', description: 'Create a migrate file')]
class MigrateCommand extends Command
{
    private const string MIGRATIONS_TABLE = 'migrations';
    private Connection $connection;

    public function __construct(Connection $connection, private readonly string $migrationsPath, ?string $name = null)
    {
        parent::__construct($name);
        $this->connection = $connection;
    }

    /**
     * @throws SchemaException
     * @throws Exception
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->createMigrationsTable($io);
        $appliedMigrations = $this->getAppliedMigrations();
        $migrationFiles = $this->getMigrationFiles();
        $migrationsToApply = array_values(array_diff($migrationFiles, $appliedMigrations));

        $schema = new Schema();

        foreach ($migrationsToApply as $migration) {
            $migrationInstance = require BASE_DIR.$this->migrationsPath."/$migration";
            $migrationInstance->up($schema);
            $this->addMigration($migration);
            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }
        }

        return 0;
    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    private function createMigrationsTable($io): void
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
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP',
            ]);
            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);

            $io->success('Таблица миграций успешно создана!');
        } else {
            $io->info('Таблица миграций уже существует!');
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
        $migrationFiles = scandir(BASE_DIR.$this->migrationsPath);

        $filteredFiles = array_filter($migrationFiles, function ($fileName) {
            return ! in_array($fileName, ['.', '..']);
        });

        return array_values($filteredFiles);
    }

    /**
     * @throws Exception
     */
    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert(self::MIGRATIONS_TABLE)
            ->values(['migration' => ':migration'])
            ->setParameter('migration', $migration)
            ->executeQuery();
    }
}
