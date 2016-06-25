<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-12-24 11:34:21 */
class Tmm_passwordController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Password';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
						//归属代理商
						'Password_Agent'=>array('select'=>'phone'),
						//归属商家
						'Password_StoreUser'=>array('select'=>'phone'),
						//归属用户
						'Password_User'=>array('select'=>'phone'),
						//操作人 管理员
						'Password_Admin_Manage'=>array('select'=>'username'),
						//操作人 代理商
						'Password_Agent_Manage'=>array('select'=>'phone'),
						//操作人 商家
						'Password_StoreUser_Manage'=>array('select'=>'phone'),
						//操作人 用户
						'Password_User_Manage'=>array('select'=>'phone'),
				)
			)),
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id,array(
				'with'=>array(
						//归属代理商
						'Password_Agent'=>array('select'=>'phone'),
						//归属商家
						'Password_StoreUser'=>array('select'=>'phone'),
						//归属用户
						'Password_User'=>array('select'=>'phone'),
						//操作人 管理员
						'Password_Admin_Manage'=>array('select'=>'username'),
						//操作人 代理商
						'Password_Agent_Manage'=>array('select'=>'phone'),
						//操作人 商家
						'Password_StoreUser_Manage'=>array('select'=>'phone'),
						//操作人 用户
						'Password_User_Manage'=>array('select'=>'phone'),
				)
		));

		$model->scenario=isset(Password::$_password_type_update[$model->password_type])? 
										Password::$_password_type_update[$model->password_type] :'update';	
		$this->_Ajax_Verify($model,'password-form');
		
		//var_dump($model->safeAttributeNames);
		if(isset($_POST['Password']))
		{
			$model->attributes=$_POST['Password'];
			$model->manage_id = Yii::app()->admin->id;
			$model->manage_type = Password::manage_type_admin;
			if($model->validate())
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					$return = Password::executeCreateUpdatePwd($model);
					if(! $return)
						throw new Exception("密码加密错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return) && $return)
				{
					$this->log('更新'.$model::$_role_type[$model->role_type].'角色'.$model::$_password_type[$model->password_type],ManageLog::admin,ManageLog::update);
					$this->back();
				}
			}
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Password('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Password']))
			$model->attributes=$_GET['Password'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * 重置错误
	 * @param integer $id
	 */
	public function actionReset($id)
	{
		if($this->loadModel($id)->updateByPk($id,array('use_error'=>0,'up_time'=>time())))
			$this->log('重置密码单次错误次数',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
