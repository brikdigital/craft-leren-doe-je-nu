<?php

namespace brikdigital\craftlerendoejenu\console\controllers;

use brikdigital\craftlerendoejenu\jobs\SyncTeachers;
use craft\console\Controller;
use craft\helpers\Queue;
use yii\console\ExitCode;

class SyncController extends Controller
{
    public function actionTeachers()
    {
        Queue::push(new SyncTeachers());
        return ExitCode::OK;
    }
}