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
            $updateColumns = [
                'firstName' => $this->firstName ?: null,
                'infix' => $this->infix ?: null,
                'lastName' => $this->lastName ?: null,
                'infoText' => $this->infoText ?: null,
                'imageUrl' => $this->imageUrl ?: null,
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
        return new TeacherQuery(static::class);
    }
}