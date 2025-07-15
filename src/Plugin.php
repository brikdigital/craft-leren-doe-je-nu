<?php

namespace brikdigital\craftlerendoejenu;

use brikdigital\craftlerendoejenu\elements\Teacher;
use brikdigital\craftlerendoejenu\models\Settings;
use brikdigital\craftlerendoejenu\services\LerenDoeJeNuApiService;
use craft\base\Event;
use craft\base\Model;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Elements;

/**
 * @property-read LerenDoeJeNuApiService $api
 */
class Plugin extends \craft\base\Plugin
{
    public bool $hasCpSettings = true;

    public function init(): void
    {
        parent::init();

        $this->setComponents([
            'api' => LerenDoeJeNuApiService::class,
        ]);

        Event::on(
            Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = Teacher::class;
            }
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return \Craft::$app->getView()->renderTemplate(
            'leren-doe-je-nu/settings',
            ['settings' => $this->getSettings()]
        );
    }
}
