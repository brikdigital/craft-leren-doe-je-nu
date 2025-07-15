<?php

namespace brikdigital\craftlerendoejenu\elements;

use brikdigital\craftlerendoejenu\elements\db\CourseGroupQuery;
use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Db;
use DateTime;

class CourseGroup extends Element
{
    public const TABLE = '{{%ldjn_course_groups}}';

    public ?int $foreignId = null;
    public ?string $name = null;
    public ?string $subtitle = null;
    public ?string $description = null;
    public ?string $practicalInfo = null;
    public ?float $lowestPrice = null;
    public ?string $bookingUrl = null;
    public ?string $year = null;
    public ?DateTime $startDateTime = null;
    public array $daysOfWeek = [];
    public array $teacherIds = [];
    public array $prices = [];
    public array $locations = [];

    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            $updateColumns = [
                'name' => $this->name ?: null,
                'subtitle' => $this->subtitle ?: null,
                'description' => $this->description ?: null,
                'practicalInfo' => $this->practicalInfo ?: null,
                'lowestPrice' => $this->lowestPrice ?: null,
                'bookingUrl' => $this->bookingUrl ?: null,
                'year' => $this->year ?: null,
                'startDateTime' => $this->startDateTime ?: null,
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
}