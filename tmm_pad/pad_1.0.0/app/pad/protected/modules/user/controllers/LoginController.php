<?php
namespace app\user\controllers;

use UserModulesController;

/**
 * @author Moore Mo
 *    创建时间：2016-06-03 10:12:18 */
class LoginController extends UserModulesController
{
    /**
     * 关注页面
     */
    public function actionIndex()
    {
        $this->renderPartial('index');
    }
}