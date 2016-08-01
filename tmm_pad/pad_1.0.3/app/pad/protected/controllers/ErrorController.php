<?php
namespace app\controllers;

use FrontController;

/**
 * 
 * @author Changhai Zhan
 *
 */
class ErrorController extends FrontController
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
                'layout'=>'//layouts/jsonerror',
            ),
        );
    }
}