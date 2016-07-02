<?php
/**
 * 
 * @author Changhai Zhan
 *
 */
class ErrorAction extends CAction
{
    /**
     * 布局
     * @var string
     */
    public $layout;
    /**
     * 视图
     * @var string
     */
    public $view;
    
    /**
     * 执行的方法
     */
    public function run()
    {
        if ( !!$error=Yii::app()->errorHandler->error)
        {
            $this->controller->layout = $this->layout;
            if ( !YII_DEBUG)
            {
                $oldError = $error;
                $error = array();
                $error['code'] = $oldError['code'];
                $error['errorCode'] = $oldError['errorCode'];
                $error['message'] = $oldError['message'];
            }
            $this->controller->render($this->view, array('error'=>$error));
        }
    }
}