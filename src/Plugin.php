<?php

namespace brikdigital\craftlerendoejenu;

use brikdigital\craftlerendoejenu\models\Settings;
use brikdigital\craftlerendoejenu\services\LerenDoeJeNuApiService;
use craft\base\Model;

class Plugin extends \craft\base\Plugin
{
    public bool $hasCpSettings = true;

    public function init(): void
    {
        parent::init();

        $this->setComponents([
            'api' => LerenDoeJeNuApiService::class,
        ]);
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
