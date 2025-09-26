<?php

namespace brikdigital\lerendoejenu\migrations;

use brikdigital\lerendoejenu\elements\CourseGroup;
use craft\db\Migration;

class m250926_000000_add_number_of_lessons_to_ldjn_course_groups_table extends Migration
{
    public function safeUp(): bool
    {
        if (!$this->db->columnExists(CourseGroup::TABLE, 'numberOfLessons')) {
            $this->addColumn(CourseGroup::TABLE, 'numberOfLessons', $this->integer()->after('lowestPrice'));
        }

        return true;
    }

    public function safeDown(): bool
    {
        echo "m250926_000000_add_number_of_lessons_to_ldjn_course_groups_table cannot be reverted.\n";
        return false;
    }
}