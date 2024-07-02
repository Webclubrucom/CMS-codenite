<?php

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
return new class
{
	/**
	* Run the migrations.
	*/
	public function up(Schema $schema): void
	{
		$table = $schema->createTable('users');
		$table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);
		$table->addColumn('name', Types::STRING, ['length' => 255, 'notNull' => false]);
        $table->addColumn('email', Types::STRING, ['length' => 100, 'notNull' => false]);
        $table->addUniqueIndex(['email']);
        $table->addColumn('phone', Types::STRING, ['length' => 100, 'notNull' => false]);
        $table->addColumn('remember_token', Types::STRING);
        $table->addColumn('email_verified_at', Types::DATETIME_IMMUTABLE, ['notNull' => false]);
		$table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
		$table->addColumn('updated_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
		$table->setPrimaryKey(['id']);
	}

	/**
	* Reverse the migrations.
	*/
	public function down(AbstractSchemaManager $schema): void
	{
		$schema->dropTable('users');
	}
};
