<?php

declare(strict_types=1);

namespace System\Console\Generators;

use Symfony\Component\Console\Command\Command;
use System\Core\Helpers\Config;

trait GeneratorMigrationTrait
{
    protected function createFileMigration($io, $name): int
    {
        $path = BASE_DIR.Config::get('PATH_MIGRATIONS');
        $nameFile = date("Y_m_d_H_i_s_").$name;
        $nameArray = explode('_', $name);

        if ($nameArray[0] == 'create') {
            if (end($nameArray) == 'table') {
                $table = implode('_', array_diff($nameArray, array($nameArray[0], end($nameArray))));
            } else {
                $table = implode('_', array_diff($nameArray, array($nameArray[0])));
            }
            $exec = $this->contentUpDownCreateTable($table);
        } else if ($nameArray[0] == 'add') {
            $to = array_search('to', $nameArray);
            for ($i = 0; $i <= $to; $i++) {
                unset($nameArray[$i]);
            }
            if (end($nameArray) == 'table') {
                $table = implode('_', array_diff($nameArray, array(end($nameArray))));
            } else {
                $table = implode('_', $nameArray);
            }
            $exec = $this->contentUpDownAddTable($table);
        } else if ($nameArray[0] == 'alter') {
            $to = array_search('to', $nameArray);
            for ($i = 0; $i <= $to; $i++) {
                unset($nameArray[$i]);
            }
            if (end($nameArray) == 'table') {
                $table = implode('_', array_diff($nameArray, array(end($nameArray))));
            } else {
                $table = implode('_', $nameArray);
            }
            $exec = $this->contentUpDownAlterTable($table);
        } else {
            $exec = $this->contentUpDown();
        }

        $content = $this->mainContentMigration($exec);

        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (! is_file($path.'/'.$nameFile.'.php')) {
            file_put_contents($path.'/'.$nameFile.'.php', $content);
            $io->success('Файл миграции успешно создан!');
            return Command::SUCCESS;
        } else {
            $io->error('Файл миграции уже существует!');
            return Command::INVALID;
        }
    }

    private function mainContentMigration($exec): string
    {
        $content = "<?php".PHP_EOL.PHP_EOL;
        $content .= "use Doctrine\\DBAL\\Schema\\AbstractSchemaManager;".PHP_EOL.PHP_EOL;
        $content .= "use Doctrine\\DBAL\\Schema\\Schema;".PHP_EOL;
        $content .= "use Doctrine\\DBAL\\Types\\Types;".PHP_EOL;
        $content .= "return new class".PHP_EOL;
        $content .= "{".PHP_EOL;
        $content .= "\t/**".PHP_EOL;
        $content .= "\t* Run the migrations.".PHP_EOL;
        $content .= "\t*/".PHP_EOL;
        $content .= "\tpublic function up(Schema \$schema): void".PHP_EOL;
        $content .= "\t{".PHP_EOL;
        $content .= $exec['up'].PHP_EOL;
        $content .= "\t}".PHP_EOL.PHP_EOL;
        $content .= "\t/**".PHP_EOL;
        $content .= "\t* Reverse the migrations.".PHP_EOL;
        $content .= "\t*/".PHP_EOL;
        $content .= "\tpublic function down(AbstractSchemaManager \$schema): void".PHP_EOL;
        $content .= "\t{".PHP_EOL;
        $content .= $exec['down'].PHP_EOL;
        $content .= "\t}".PHP_EOL;
        $content .= "};".PHP_EOL;

        return $content;
    }

    private function contentUpDown(): array
    {
        return [
            'up' => "\t\t",
            'down' => "\t\t"
        ];
    }

    private function contentUpDownCreateTable($table): array
    {
        $commandUp = "\t\t\$table = \$schema->createTable('".$table."');".PHP_EOL;
        $commandUp .= "\t\t\$table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);".PHP_EOL;
        $commandUp .= "\t\t\$table->addColumn('name', Types::STRING, ['length' => 255]);".PHP_EOL.PHP_EOL;
        $commandUp .= "\t\t\$table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);".PHP_EOL;
        $commandUp .= "\t\t\$table->addColumn('updated_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);".PHP_EOL;
        $commandUp .= "\t\t\$table->setPrimaryKey(['id']);";

        $commandDown = "\t\t\$schema->dropTable('".$table."');";

        return [
            'up' => $commandUp,
            'down' => $commandDown
        ];
    }

    private function contentUpDownAddTable($table): array
    {
        return [
            'up' => "\t\t",
            'down' => "\t\t"
        ];
    }

    private function contentUpDownAlterTable($table): array
    {
        return [
            'up' => "\t\t",
            'down' => "\t\t"
        ];
    }


}