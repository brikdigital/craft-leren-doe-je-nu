<?php

namespace brikdigital\lerendoejenu\elements\db;

use brikdigital\lerendoejenu\elements\CourseGroup;
use craft\elements\db\ElementQuery;
use craft\helpers\Db;

class CourseGroupQuery extends ElementQuery
{
    public $foreignId;
    public $name;
    public $subtitle;
    public $description;
    public $practicalInfo;
    public $lowestPrice;
    public $bookingUrl;
    public $year;
    public $startDateTime;

    public function foreignId(int $value): self
    {
        $this->foreignId = $value;
        return $this;
    }

    public function name(string $value): self
    {
        $this->name = $value;
        return $this;
    }

    public function subtitle(string $value): self
    {
        $this->subtitle = $value;
        return $this;
    }

    public function description(string $value): self
    {
        $this->description = $value;
        return $this;
    }

    public function practicalInfo(string $value): self
    {
        $this->practicalInfo = $value;
        return $this;
    }

    public function lowestPrice(float $value): self
    {
        $this->lowestPrice = $value;
        return $this;
    }

    public function bookingUrl(string $value): self
    {
        $this->bookingUrl = $value;
        return $this;
    }

    public function year(string $value): self
    {
        $this->year = $value;
        return $this;
    }

    public function startDateTime(string $value): self
    {
        $this->startDateTime = $value;
        return $this;
    }

    protected function beforePrepare(): bool
    {
        $this->joinElementTable(CourseGroup::TABLE);

        $columns = [];
        $properties = ['foreignId', 'name', 'subtitle', 'description', 'practicalInfo', 'lowestPrice', 'bookingUrl', 'year', 'startDateTime'];
        foreach ($properties as $property) {
            $columns[] = $column = CourseGroup::TABLE . ".$property";
            if ($this->$property) {
                $this->subQuery->andWhere(Db::parseParam($column, $this->$property));
            }
        }

        $this->query->select($columns);

        return parent::beforePrepare();
    }
}