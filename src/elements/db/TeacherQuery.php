<?php

namespace brikdigital\lerendoejenu\elements\db;

use brikdigital\lerendoejenu\elements\Teacher;
use craft\elements\db\ElementQuery;
use craft\helpers\Db;

class TeacherQuery extends ElementQuery
{
    public $foreignId;
    public $firstName;
    public $infix;
    public $lastName;
    public $infoText;
    public $imageUrl;

    public function foreignId(int $foreignId): self
    {
        $this->foreignId = $foreignId;
        return $this;
    }

    public function firstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function infix(string $infix): self
    {
        $this->infix = $infix;
        return $this;
    }

    public function lastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function infoText(string $infoText): self
    {
        $this->infoText = $infoText;
        return $this;
    }

    public function imageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    protected function beforePrepare(): bool
    {
        $this->joinElementTable(Teacher::TABLE);

        $columns = [];
        $properties = ['foreignId', 'firstName', 'infix', 'lastName', 'infoText', 'imageUrl'];
        foreach ($properties as $property) {
            $columns[] = $column = Teacher::TABLE . ".$property";
            if ($this->$property) {
                $this->subQuery->andWhere(Db::parseParam($column, $this->$property));
            }
        }

        $this->query->select($columns);

        return parent::beforePrepare();
    }
}