<?php

namespace brikdigital\lerendoejenu\controllers;

use brikdigital\lerendoejenu\LerenDoeJeNu;
use craft\web\Controller;

class NavigationController extends Controller
{
    public function actionIndex()
    {
        $subnav = LerenDoeJeNu::getInstance()->getCpNavItem()['subnav'] ?? [];
        $firstNavUrl = $subnav ? reset($subnav)['url'] : null;

        return $this->redirect($firstNavUrl);
    }
}