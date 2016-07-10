<?php
namespace app\admin\controllers;

use AdminModulesController;

class LoginController extends AdminModulesController
{   
    /**
     * 外部方法名
     * (non-PHPdoc)
     * @see CController::actions()
     */
    public function actions()
    {
        return array(
            //验证码
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor' =>hexdec('0x' . \Helper::getRandCode(6, '0123456789ABCDEF')),
                'foreColor'=> hexdec('0x' . \Helper::getRandCode(6, '0123456789ABCDEF')),
                'maxLength'=>4,
                'minLength'=>4,
                'offset'=>mt_rand(-5, 5),
                'padding'=>mt_rand(-5, 5),
                'width'=>120,
                'fontFile' => \Yii::app()->basePath . '/data/font/' . mt_rand(1, 29) . '.ttf',
                //'testLimit'=>3,//使用3次
            ),
        );
    }
    
    /**
     * 登录
     */
    public function actionIndex()
    {
        if ( !\Yii::app()->user->isGuest)
            $this->redirect(\Yii::app()->homeUrl);
        
        $this->layout = '/layouts/column';
        
        $model = new \AdminLoginForm();
        $model->scenario = 'verifycode';

        if (isset($_POST['AdminLoginForm']))
        {
            $model->attributes = $_POST['AdminLoginForm'];
            if ($model->validate() && $model->login())
                $this->redirect(\Yii::app()->homeUrl);
        }
        
        $this->render('index', array('model'=>$model));
    }
    
    /**
     * 退出当前登录模块
     */
    public function actionOut()
    {
        \Yii::app()->user->logout(false);
        \Yii::app()->user->loginRequired();
    }
}