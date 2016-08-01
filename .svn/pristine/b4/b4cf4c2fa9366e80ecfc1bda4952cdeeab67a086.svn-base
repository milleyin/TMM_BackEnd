<?php

class CallbackModule extends WebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        Yii::app()->theme = 'html';
        // import the module-level models and components
        $this->setImport(array(
            $this->getId() . '.models.*',
            $this->getId() . '.components.*',
        ));
        Yii::app()->setComponents(array(
            'errorHandler'=>array(
                'class'=>'CErrorHandler',
                'errorAction'=>YII_DEBUG ? null : '/' . $this->getId() . '/error/index',
            ),
        ), false);
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action)) {
            return true;
        } else {
            return false;
        }
    }
}
