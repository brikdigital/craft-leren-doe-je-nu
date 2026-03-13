<?php

namespace brikdigital\lerendoejenu\migrations;

use Craft;
use brikdigital\lerendoejenu\elements\CourseGroup;
use brikdigital\lerendoejenu\elements\Teacher;
use craft\db\Query;
use craft\migrations\BaseContentRefactorMigration;

class m260313_000000_content_refactor_migration extends BaseContentRefactorMigration
{
    public function safeUp(): bool
    {
        $this->updateElements(
            (new Query())->from('{{%ldjn_course_groups}}'),
            Craft::$app->fields->getLayoutByType(CourseGroup::class)
        );

        $this->updateElements(
            (new Query())->from('{{%ldjn_teachers}}'),
            Craft::$app->fields->getLayoutByType(Teacher::class)
        );

        return true;
    }

    public function safeDown(): bool
    {
        echo "m260114_000000_add_website_age_groups_to_ldjn_course_groups_table cannot be reverted.\n";
        return false;
    }
}