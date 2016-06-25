<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *	创建时间：2016-06-01 15:25:55 */
class PasswordController extends AdminModulesController
{
	/**
	 * 当前操作模型的名称
	 * @var string
	 */
	public $_modelName = 'Password';
	
	/**
	 * 管理
	 */
	public function actionAdmin()
	{
		$model = new \Password('search');
		//清除默认值
		$model->unsetAttributes();
		if (isset($_GET['Password']))
			$model->attributes = $_GET['Password'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}
	
	/**
	 * 查看
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model'=>$this->loadModelByPk($id),
		));
	}

	/**
	 * 创建
	 * @return Success page "view"
	 */
	public function actionCreate()
	{
		$model = new \Password;

		$model->scenario = 'create';
		$this->ajaxVerify($model, 'password-form');

		if (isset($_POST['Password']))
		{
			$model->attributes = $_POST['Password'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 * @return Success page "view"
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModelByPk($id);

		$model->scenario = 'update';
		$this->ajaxVerify($model, 'password-form');

		if (isset($_POST['Password']))
		{
			$model->attributes = $_POST['Password'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * 删除
	 * @param integer $id the ID of the model to be deleted
	 * @return Success page "admin"
	 */
	public function actionDelete($id)
	{
		$this->loadModelByPk($id, '`status`=:status', array(':status'=>\Password::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Password::_STATUS_DELETED));
		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * 禁用
	 * @param integer $id the ID of the model to be deleted
	 * @return Success page "admin"
	 */
	public function actionDisable($id)
	{
		$this->loadModelByPk($id, '`status`=:status', array(':status'=>\Password::_STATUS_NORMAL))->updateByPk($id, array('status'=>\Password::_STATUS_DISABLE));
		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * 激活
	 * @param integer $id the ID of the model to be deleted
	 * @return Success page "admin"
	 */
	public function actionStart($id)
	{
		$this->loadModelByPk($id, '`status`=:status', array(':status'=>\Password::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Password::_STATUS_NORMAL));
		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * 还原
	 * @param integer $id the ID of the model to be deleted
	 * @return Success page "admin"
	 */
	public function actionRestore($id)
	{
		$this->loadModelByPk($id, '`status`=:status', array(':status'=>\Password::_STATUS_DELETED))->updateByPk($id, array('status'=>\Password::_STATUS_DISABLE));
		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * 清除记录
	 * @param integer $id the ID of the model to be deleted
	 * @return Success page "admin"
	 */
	public function actionClear($id)
	{
		$this->loadModelByPk($id, '`status`=:status', array(':status'=>\Password::_STATUS_DELETED))->delete();
		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
}
