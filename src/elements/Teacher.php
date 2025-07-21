<?php

namespace brikdigital\lerendoejenu\elements;

use brikdigital\lerendoejenu\elements\db\TeacherQuery;
use brikdigital\lerendoejenu\LerenDoeJeNu;
use Craft;
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

    public static function displayName(): string
    {
        return Craft::t(LerenDoeJeNu::getInstance()->getHandle(), 'Teacher');
    }

    public static function pluralDisplayName(): string
    {
        return Craft::t(LerenDoeJeNu::getInstance()->getHandle(), 'Teachers');
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