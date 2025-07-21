<?php

namespace brikdigital\lerendoejenu\jobs;

use brikdigital\lerendoejenu\elements\Teacher;
use brikdigital\lerendoejenu\LerenDoeJeNu;
use Craft;
use craft\i18n\Translation;
use craft\queue\BaseJob;

class SyncTeachers extends BaseJob
{
    protected function defaultDescription(): ?string
    {
        return Translation::prep('app', 'Synchroniseer docenten');
    }

    public function execute($queue): void
    {
        $count = 0;
        $processedIds = [];

        LerenDoeJeNu::getInstance()->api->getAll('teachers', [
            'sort' => 'teacher_id,asc'
        ], function ($response) use (&$count, &$processedIds, $queue) {
            foreach ($response['content'] ?? [] as $teacher) {
                $queue->setProgress(
                    round(100 * (++$count / $response['page']['total_elements'])),
                    Translation::prep('app', '{step} of {total}', [
                        'step' => $count,
                        'total' => $response['page']['total_elements'],
                    ])
                );

                // Try to find existing element
                $element = Teacher::find()
                    ->foreignId($teacher['user_id'])
                    ->one();
                if (!$element) {
                    $element = new Teacher();
                    $element->foreignId = $teacher['user_id'];
                }

                $element->firstName = $teacher['first_name'] ?: null;
                $element->infix = $teacher['infix'] ?: null;
                $element->lastName = $teacher['last_name'] ?: null;
                $element->infoText = $teacher['info_text'] ?: null;
                $element->imageUrl = $teacher['image_url'] ?: null;

                $element->title = implode(' ', [$element->firstName, $element->infix, $element->lastName]);
                Craft::$app->getElements()->saveElement($element);

                $processedIds[] = $element->id;
            }
        });

        $this->deleteTeachers($processedIds);
    }

    private function deleteTeachers(array $processedIds): void
    {
        $teachersToDelete = Teacher::find()
            ->id(array_merge(['not'], $processedIds))
            ->all();

        foreach ($teachersToDelete as $teacher) {
            Craft::$app->getElements()->deleteElement($teacher, true);
        }
    }
}