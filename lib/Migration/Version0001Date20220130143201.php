<?php

namespace OCA\Diary\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version0001Date20220130143201 extends SimpleMigrationStep
{
    /**
     * @param IOutput $output
     * @param Closure $schemaClosure
     * @param array $options
     * @return ISchemaWrapper
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options)
    {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if (!$schema->hasTable('diary')) {
            $table = $schema->createTable('diary');
            $table->addColumn('id', 'string', ['length' => 74, 'notnull' => true]);
            $table->addColumn('uid', 'string', ['length' => 64]);
            $table->addColumn('entry_date', 'string', ['length' => 10]);
            $table->addColumn('entry_content', 'text', ['notnull' => false]);
            $table->setPrimaryKey(['id'], 'diary_user_id_date');
        }

        return $schema;
    }

}