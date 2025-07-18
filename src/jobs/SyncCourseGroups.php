<?php

namespace brikdigital\lerendoejenu\jobs;

use brikdigital\lerendoejenu\elements\CourseGroup;
use brikdigital\lerendoejenu\LerenDoeJeNu;
use Craft;
use craft\i18n\Translation;
use craft\queue\BaseJob;
use DateTime;

class SyncCourseGroups extends BaseJob
{
    protected function defaultDescription(): ?string
    {
        return Translation::prep('app', 'Synchroniseer cursussen');
    }

    public function execute($queue): void
    {
        $count = 0;
        $processedIds = [];

        Craft::info(CourseGroup::find()->all(), 'LALAL');

        LerenDoeJeNu::getInstance()->api->getAll('courseGroups', [
            'sort' => 'id,asc'
        ], function ($response) use (&$count, &$processedIds, $queue) {
            foreach ($response['content'] ?? [] as $courseGroup) {
                $queue->setProgress(
                    round(100 * (++$count / $response['page']['total_elements'])),
                    Translation::prep('app', '{step} of {total}', [
                        'step' => $count,
                        'total' => $response['page']['total_elements'],
                    ])
                );

                // Try to find existing element
                $element = CourseGroup::find()
                    ->foreignId($courseGroup['id'])
                    ->one();
                if (!$element) {
                    $element = new CourseGroup();
                    $element->foreignId = $courseGroup['id'];
                }

                $element->title = $courseGroup['name'] ?: null;
                $element->name = $courseGroup['name'] ?: null;
                $element->subtitle = $courseGroup['subtitle'] ?: null;
                $element->description = $courseGroup['description'] ?: null;
                $element->practicalInfo = $courseGroup['practical_info'] ?: null;
                $element->lowestPrice = $courseGroup['lowest_price'] ?: null;
                $element->bookingUrl = $courseGroup['booking_url'] ?: null;
                $element->year = $courseGroup['year']['name'] ?: null;
                $element->startDateTime = new DateTime($courseGroup['start_date_time']) ?: null;
                $element->daysOfWeek = $courseGroup['days_of_week'] ?: [];
                $element->teacherIds = $courseGroup['teacher_ids'] ?: [];
                $element->prices = $courseGroup['prices'] ?: [];
                $element->locations = $courseGroup['locations'] ?: [];

                Craft::$app->getElements()->saveElement($element);

                $processedIds[] = $element->id;
            }
        });

        $this->deleteCourseGroups($processedIds);
    }

    private function deleteCourseGroups(array $processedIds): void
    {
        $courseGroupsToDelete = CourseGroup::find()
            ->id(array_merge(['not'], $processedIds))
            ->all();

        foreach ($courseGroupsToDelete as $courseGroup) {
            Craft::$app->getElements()->deleteElement($courseGroup, true);
        }
    }
}