<?php

class Wx_errorController extends AppController
{
    public function actionIndex()
    {
        if(!! $error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->renderPartial('index', $error);
        }

    }
}