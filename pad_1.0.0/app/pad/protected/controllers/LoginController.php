<?php
namespace app\controllers;

use FrontController;

/**
 * 登录相关
 * @author Changhai Zhan
 *
 */
class LoginController extends FrontController
{
    /**
     * 登录页面
     */
	public function actionIndex()
	{
   		if ( !\Yii::app()->user->isGuest)
		  $this->redirect(\Yii::app()->homeUrl);
		$model = new \LoginForm;
		$model->scenario = 'login';

		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login())
				$this->redirect(\Yii::app()->homeUrl);
		}
		
		$this->render('index', array('model'=>$model));
	}
}