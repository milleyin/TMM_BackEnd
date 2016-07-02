<?php
namespace app\admin\controllers;

use AdminModulesController;

class ErrorController extends AdminModulesController
{
    public function actions()
    {
        return array(
            'index'=>array(
                'class'=>'ext.actions.ErrorAction',
                'view'=>'index',
                'layout'=>\Yii::app()->user->isGuest ? '/layouts/column' : $this->layout,
            ),
        );
    }
}