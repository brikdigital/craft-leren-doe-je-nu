<?php

namespace brikdigital\lerendoejenu\migrations;

use brikdigital\lerendoejenu\elements\CourseGroup;
use brikdigital\lerendoejenu\elements\Teacher;
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

        if (!Craft::$app->getDb()->tableExists(CourseGroup::TABLE)) {
            $this->createTable(CourseGroup::TABLE, [
                'id' => $this->primaryKey(),
                'foreignId' => $this->bigInteger()->unsigned()->unique()->notNull(),

                'name' => $this->string(),
                'subtitle' => $this->string(),
                'description' => $this->text(),
                'practicalInfo' => $this->text(),
                'lowestPrice' => $this->float(),
                'bookingUrl' => $this->string(),
                'year' => $this->string(),
                'startDateTime' => $this->dateTime(),
                'daysOfWeek' => $this->json(),
                'teacherIds' => $this->json(),
                'prices' => $this->json(),
                'locations' => $this->json(),

                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

            $tablesCreated = true;
        }

        if (!Craft::$app->getDb()->tableExists(Teacher::TABLE)) {
            $this->createTable(Teacher::TABLE, [
                'id' => $this->primaryKey(),
                'foreignId' => $this->bigInteger()->unsigned()->unique()->notNull(),

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

    private function addForeignKeys(): void
    {
        $this->addForeignKey(null, CourseGroup::TABLE, ['id'], Table::ELEMENTS, ['id'], 'CASCADE');
        $this->addForeignKey(null, Teacher::TABLE, ['id'], Table::ELEMENTS, ['id'], 'CASCADE');
    }

    private function dropTables(): void
    {
        $this->dropTableIfExists(CourseGroup::TABLE);
        $this->dropTableIfExists(Teacher::TABLE);
    }
}
