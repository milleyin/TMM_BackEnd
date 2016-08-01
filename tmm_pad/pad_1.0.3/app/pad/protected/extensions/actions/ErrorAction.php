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
        if ( !!$error = Yii::app()->errorHandler->error)
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
            if ($error['errorCode'] == 302) {
                $error['result'] = 0;
                $error['source'] = Yii::app()->request->getUrl();
                $error['location'] = $this->controller->getLastUrl();
                $error['time'] = 8;
            }
            if ((!!$message = json_decode($oldError['message'], true)) && is_array($message)) {
                $error = array_merge($error, $message);
            }
            $this->controller->render($this->view, array('error'=>$error));
        } elseif ( !!$error = $this->controller->getMessage()) {
            $this->controller->layout = $this->layout;
            if ($error['errorCode'] == 302) {
                $error['result'] = 0;
                $error['source'] = Yii::app()->request->getUrl();
                $error['location'] = $this->controller->getLastUrl();
                $error['time'] = 8;
            }
            if ((!!$message = json_decode($error['message'], true)) && is_array($message)) {
                $error = array_merge($error, $message);
            }
            $this->controller->render($this->view, array('error'=>$error));
        }
    }
}