<?php

namespace brikdigital\craftlerendoejenu\elements;

use brikdigital\craftlerendoejenu\elements\db\TeacherQuery;
use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Db;

class Teacher extends Element
{
    public const TABLE = '{{%ldjn_teachers}}';

    public ?int $foreignId = null;
    public ?string $firstName = null;
    public ?string $infix = null;
    public ?string $lastName = null;
    public ?string $infoText = null;
    public ?string $imageUrl = null;

    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            Db::upsert(self::TABLE, [
                'id' => $this->id,
                'foreignId' => $this->foreignId,
            ], [
                'firstName' => $this->firstName,
                'infix' => $this->infix,
                'lastName' => $this->lastName,
                'infoText' => $this->infoText,
                'imageUrl' => $this->imageUrl,
            ]);
        }

        parent::afterSave($isNew);
    }

    public static function find(): ElementQueryInterface
    {
        return new TeacherQuery(static::class);
    }
}