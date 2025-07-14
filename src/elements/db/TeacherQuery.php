<?php

namespace brikdigital\craftlerendoejenu\elements\db;

use brikdigital\craftlerendoejenu\elements\Teacher;
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

        $this->query->select([
            Teacher::TABLE . '.foreignId',
            Teacher::TABLE . '.firstName',
            Teacher::TABLE . '.infix',
            Teacher::TABLE . '.lastName',
            Teacher::TABLE . '.infoText',
            Teacher::TABLE . '.imageUrl',
        ]);

        foreach (get_object_vars($this) as $key => $value) {
            if ($value) {
                $this->subQuery->andWhere(Db::parseParam(Teacher::TABLE . ".$key", $value));
            }
        }

        return parent::beforePrepare();
    }
}