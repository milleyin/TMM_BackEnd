<?php

class ErrorController extends ApiController
{
    /**
     * csrf 攻击
     * @var boolean
     */
    public $enableCsrfValidation = false;
    
    public function actionIndex()
    {
        if(!! $error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->params['api_error_json']) {
                if ($error['errorCode'] == SYSTEM_FROM_CSRF_ERROR) {
                    $this->send_error_system($error['message'], SYSTEM_FROM_CSRF_ERROR);
                } else {
                    $this->send_error_system($error['message']);
                }
            } else {
                if (Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
                else
                    $this->renderPartial('index', $error);
            }
        }
    }
}