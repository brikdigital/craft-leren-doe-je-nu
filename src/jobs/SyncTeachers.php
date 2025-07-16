<?php

namespace brikdigital\craftlerendoejenu\jobs;

use brikdigital\craftlerendoejenu\elements\Teacher;
use brikdigital\craftlerendoejenu\Plugin;
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

        Plugin::getInstance()->api->getAll('teachers', [
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

                $element->firstName = $teacher['first_name'];
                $element->infix = $teacher['infix'];
                $element->lastName = $teacher['last_name'];
                $element->infoText = $teacher['info_text'];
                $element->imageUrl = $teacher['image_url'];

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