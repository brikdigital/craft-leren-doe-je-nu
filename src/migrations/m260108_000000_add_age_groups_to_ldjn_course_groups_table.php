<?php

namespace brikdigital\lerendoejenu\migrations;

use brikdigital\lerendoejenu\elements\CourseGroup;
use craft\db\Migration;

class m260108_000000_add_age_groups_to_ldjn_course_groups_table extends Migration
{
    public function safeUp(): bool
    {
        if (!$this->db->columnExists(CourseGroup::TABLE, 'ageGroups')) {
            $this->addColumn(CourseGroup::TABLE, 'ageGroups', $this->json()->after('lessons'));
        }

        return true;
    }

    public function safeDown(): bool
    {
        echo "m260108_000000_add_age_groups_to_ldjn_course_groups_table cannot be reverted.\n";
        return false;
    }
}