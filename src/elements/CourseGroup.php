<?php

namespace brikdigital\lerendoejenu\elements;

use brikdigital\lerendoejenu\elements\db\CourseGroupQuery;
use brikdigital\lerendoejenu\LerenDoeJeNu;
use Craft;
use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Db;
use DateTime;

class CourseGroup extends Element
{
    public const TABLE = '{{%ldjn_course_groups}}';

    public ?int $foreignId = null;
    public ?string $subtitle = null;
    public ?string $description = null;
    public ?string $practicalInfo = null;
    public ?float $lowestPrice = null;
    public ?string $bookingUrl = null;
    public ?string $year = null;
    public ?DateTime $startDateTime = null;
    public ?DateTime $subscribeUntil = null;
    public array $daysOfWeek = [];
    public array $teacherIds = [];
    public array $prices = [];
    public array $locations = [];

    public static function displayName(): string
    {
        return Craft::t(LerenDoeJeNu::getInstance()->getHandle(), 'Course group');
    }

    public static function pluralDisplayName(): string
    {
        return Craft::t(LerenDoeJeNu::getInstance()->getHandle(), 'Course groups');
    }

    // TODO: Remove after Craft 5 update
    public static function hasContent(): bool
    {
        return true;
    }

    public static function hasTitles(): bool
    {
        return true;
    }

    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            $updateColumns = [
                'subtitle' => $this->subtitle ?: null,
                'description' => $this->description ?: null,
                'practicalInfo' => $this->practicalInfo ?: null,
                'lowestPrice' => $this->lowestPrice ?: null,
                'bookingUrl' => $this->bookingUrl ?: null,
                'year' => $this->year ?: null,
                'startDateTime' => Db::prepareValueForDb($this->startDateTime) ?: null,
                'subscribeUntil' => Db::prepareValueForDb($this->subscribeUntil) ?: null,
                'daysOfWeek' => $this->daysOfWeek ?: null,
                'teacherIds' => $this->teacherIds ?: null,
                'prices' => $this->prices ?: null,
                'locations' => $this->locations ?: null,
            ];

            Db::upsert(self::TABLE, array_merge($updateColumns, [
                'id' => $this->id,
                'foreignId' => $this->foreignId,
            ]), $updateColumns);
        }

        parent::afterSave($isNew);
    }

    public static function find(): ElementQueryInterface
    {
        return new CourseGroupQuery(static::class);
    }

    protected static function defineSources(string $context): array
    {
        return [
            [
                'key' => '*',
                'label' => Craft::t('app', 'All entries'),
            ],
        ];
    }
}