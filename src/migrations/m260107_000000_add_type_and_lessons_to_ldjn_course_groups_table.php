<?php

namespace brikdigital\lerendoejenu\migrations;

use brikdigital\lerendoejenu\elements\CourseGroup;
use craft\db\Migration;

class m260107_000000_add_type_and_lessons_to_ldjn_course_groups_table extends Migration
{
    public function safeUp(): bool
    {
        if (!$this->db->columnExists(CourseGroup::TABLE, 'type')) {
            $this->addColumn(CourseGroup::TABLE, 'type', $this->string()->after('foreignId'));
        }

        if (!$this->db->columnExists(CourseGroup::TABLE, 'lessons')) {
            $this->addColumn(CourseGroup::TABLE, 'lessons', $this->json()->after('locations'));
        }

        return true;
    }

    public function safeDown(): bool
    {
        echo "m260107_000000_add_type_and_lessons_to_ldjn_course_groups_table cannot be reverted.\n";
        return false;
    }
}