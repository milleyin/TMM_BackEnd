<?php
namespace app\admin\controllers;

use AdminModulesController;

class ErrorController extends AdminModulesController
{
    /**
     * 无需验证csrf
     * @var unknown
     */
    public $enableCsrfValidation = false;
    /**
     * 外部方法
     * (non-PHPdoc)
     * @see CController::actions()
     */
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