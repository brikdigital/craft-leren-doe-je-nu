<?php

namespace brikdigital\lerendoejenu\console\controllers;

use brikdigital\lerendoejenu\jobs\SyncCourseGroups;
use brikdigital\lerendoejenu\jobs\SyncTeachers;
use craft\console\Controller;
use craft\helpers\Queue;
use yii\console\ExitCode;

class SyncController extends Controller
{
    public function actionCourseGroups()
    {
        Queue::push(new SyncCourseGroups());
        return ExitCode::OK;
    }

    public function actionTeachers()
    {
        Queue::push(new SyncTeachers());
        return ExitCode::OK;
    }
}