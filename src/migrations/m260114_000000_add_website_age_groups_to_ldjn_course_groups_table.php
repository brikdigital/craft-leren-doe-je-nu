<?php

namespace brikdigital\lerendoejenu\migrations;

use brikdigital\lerendoejenu\elements\CourseGroup;
use craft\db\Migration;

class m260114_000000_add_website_age_groups_to_ldjn_course_groups_table extends Migration
{
    public function safeUp(): bool
    {
        if (!$this->db->columnExists(CourseGroup::TABLE, 'websiteAgeGroups')) {
            $this->addColumn(CourseGroup::TABLE, 'websiteAgeGroups', $this->json()->after('ageGroups'));
        }

        return true;
    }

    public function safeDown(): bool
    {
        echo "m260114_000000_add_website_age_groups_to_ldjn_course_groups_table cannot be reverted.\n";
        return false;
    }
}