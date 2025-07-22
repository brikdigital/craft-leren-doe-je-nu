<?php

namespace brikdigital\lerendoejenu\fields;

use brikdigital\lerendoejenu\elements\CourseGroup;
use brikdigital\lerendoejenu\LerenDoeJeNu;
use Craft;
use craft\fields\BaseRelationField;

class CourseGroups extends BaseRelationField
{
    public static function displayName(): string
    {
        return Craft::t(LerenDoeJeNu::getInstance()->getHandle(), 'Course group');
    }

    public static function elementType(): string
    {
        return CourseGroup::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t(LerenDoeJeNu::getInstance()->getHandle(), 'Select a course group');
    }
}