<?php

namespace brikdigital\lerendoejenu\models;

use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

class Settings extends Model
{
    public ?string $apiUrl = null;
    public ?string $apiKey = null;

    protected function defineRules(): array
    {
        return [
            [['apiUrl', 'apiKey'], 'required'],
        ];
    }

    protected function defineBehaviors(): array
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => [
                    'apiUrl',
                    'apiKey',
                ],
            ],
        ];
    }
}