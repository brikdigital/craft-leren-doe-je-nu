<?php

namespace brikdigital\craftlerendoejenu\migrations;

use brikdigital\craftlerendoejenu\elements\Teacher;
use Craft;
use craft\db\Migration;
use craft\db\Table;

/**
 * Install migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        if ($this->createTables()) {
            $this->addForeignKeys();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTables();
        return true;
    }

    private function createTables(): bool
    {
        $tablesCreated = false;

        if (!Craft::$app->getDb()->tableExists(Teacher::TABLE)) {
            $this->createTable(Teacher::TABLE, [
                'id' => $this->primaryKey(),
                'foreignId' => $this->bigInteger()->unsigned()->notNull(),

                'firstName' => $this->string(),
                'infix' => $this->string(),
                'lastName' => $this->string(),
                'infoText' => $this->text(),
                'imageUrl' => $this->string(),

                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

            $tablesCreated = true;
        }

        return $tablesCreated;
    }

    private function addForeignKeys()
    {
        $this->addForeignKey(null, Teacher::TABLE, ['id'], Table::ELEMENTS, ['id'], 'CASCADE');
    }

    private function dropTables(): void
    {
        $this->dropTableIfExists(Teacher::TABLE);
    }
}
