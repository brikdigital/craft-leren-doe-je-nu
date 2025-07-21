<?php

namespace brikdigital\lerendoejenu;

use brikdigital\lerendoejenu\elements\CourseGroup;
use brikdigital\lerendoejenu\elements\Teacher;
use brikdigital\lerendoejenu\models\Settings;
use brikdigital\lerendoejenu\services\LerenDoeJeNuApiService;
use Craft;
use craft\base\Event;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\web\UrlManager;

/**
 * @property-read LerenDoeJeNuApiService $api
 */
class LerenDoeJeNu extends Plugin
{
    public bool $hasCpSection = true;
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
                $event->types[] = CourseGroup::class;
                $event->types[] = Teacher::class;
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules[$this->handle] = "$this->handle/navigation/index";

                $event->rules["$this->handle/course-groups"] = ['template' => "$this->handle/course-groups/_index.twig"];
                $event->rules["$this->handle/teachers"] = ['template' => "$this->handle/teachers/_index.twig"];
            }
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    public function getCpNavItem(): ?array
    {
        $nav = parent::getCpNavItem();
        $nav['subnav'] = [
            'course-groups' => [
                'label' => Craft::t($this->handle, 'Course groups'),
                'url' => "$this->handle/course-groups"
            ],
            'teachers' => [
                'label' => Craft::t($this->handle, 'Teachers'),
                'url' => "$this->handle/teachers"
            ]
        ];
        return $nav;
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            "$this->handle/settings",
            ['settings' => $this->getSettings()]
        );
    }
}
