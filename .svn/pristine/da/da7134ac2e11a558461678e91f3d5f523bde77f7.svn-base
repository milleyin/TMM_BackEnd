<?php
namespace app\admin\controllers;

use AdminModulesController;

class IndexController extends AdminModulesController
{
	/**
	 * 操作模型名
	 * @var string
	 */
	public $_modelName = 'Admin';
	
	/**
	 * 我的信息
	 */
	public function actionIndex()
	{
		$criteria = new \CDbCriteria;
		$criteria->with = array(
			'Admin_Role',
		);
		$criteria->addColumnCondition(array(
			'`t`.`status`'=>\Admin::_STATUS_NORMAL,
			'`Admin_Role`.`status`'=>\Role::_STATUS_NORMAL,
		));
		
		$this->render('index', array(
			'model'=>$this->loadModelByPk(\Yii::app()->user->id, $criteria),
		));
	}
	
	/**
	 * 更新信息
	 */
	public function actionUpdate()
	{
		$criteria = new \CDbCriteria;
		$criteria->with = array(
			'Admin_Role',
		);
		$criteria->addColumnCondition(array(
			'`t`.`status`'=>\Admin::_STATUS_NORMAL,
			'`Admin_Role`.`status`'=>\Role::_STATUS_NORMAL,
		));
		$model = $this->loadModelByPk(\Yii::app()->user->id, $criteria);
		
		$model->scenario = 'self_update';
		$this->ajaxVerify($model, 'admin-form');
		
		if (isset($_POST['Admin']))
		{
		    $model->attributes = $_POST['Admin'];
		    if ($model->save())
		        $this->redirect(array('index'));
		}
		
		$this->render('update', array(
				'model'=>$model,
		));
	}
	
	/**
	 * 修改密码
	 */
	public function actionPwd()
	{
	    $this->_modelName = 'Password';
	    
		$criteria = new \CDbCriteria;
		$criteria->with = array(
				'Password_Role',
		        'Password_Admin',
		);
		$criteria->addColumnCondition(array(
				'`Password_Admin`.`status`'=>\Admin::_STATUS_NORMAL,
		        '`Password_Admin`.`id`'=>\Yii::app()->user->id,
				'`Password_Role`.`status`'=>\Role::_STATUS_NORMAL,
		        '`t`.`type`'=>\Password::PASSWORD_TYPE_LOGIN,
		));
		$model = $this->loadModel($criteria);
		
		$model->scenario = 'self_update';
		
		if (isset($_POST['Password']))
		{
		    $model->setPassword($_POST['Password']);
		    if ($model->execute())
		        $this->redirect(array('login/out'));
		}
		
		$this->render('pwd', array(
				'model'=>$model,
		));
	}
}