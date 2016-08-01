<?php
namespace app\callback\controllers;

use CallbackModulesController;

/**
 * 回调模块错误控制器
 * @author Changhai Zhan
 *
 */
class ErrorController extends CallbackModulesController
{
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
                'layout'=>$this->layout,
            ),
        );
    }
}