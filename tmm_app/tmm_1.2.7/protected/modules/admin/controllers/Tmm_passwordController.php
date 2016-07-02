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
	 * 选择密码类型
	 * @param unknown $id
	 * @param unknown $role_type
	 * @throws CHttpException
	 */
	public function actionSelect($id, $role_type=Password::role_type_admin)
	{
		if (! isset(Password::$_role_type[$role_type]))
			throw new CHttpException(404,'角色类型或密码类型不存在');
		
		$model = new Password;
		$model->role_type = $role_type;

		$model->scenario = 'select';
		$this->_Ajax_Verify($model, 'password-form');
		
		if (isset($_POST['Password']))
		{
			$model->attributes = $_POST['Password'];
			if ($model->validate())
			{
				$pwd = Password::existPwd($id, $role_type, $model->password_type);
				if ($pwd)
					$this->redirect(array('update','id'=>$pwd->id));
				else
					$this->redirect(array('create','id'=>$id, 'role_type'=>$role_type, 'type'=>$model->password_type));
			}
		}
		
		$this->render('select', array(
				'model'=>$model,
		));
	}
	
	/**
	 * 创建 密码
	 */
	public function actionCreate($id, $role_type=Password::role_type_admin, $type=Password::password_type_pay)
	{	
		if (! isset(Password::$_role_type[$role_type],Password::$_password_type[$type]))
			throw new CHttpException(404,'角色类型或密码类型不存在');
		
		if (! Password::existRole($id, $role_type))
			throw new CHttpException(404,'角色不存在');
		//角色
		$role_who = array(
				'role_id'=>$id,
				'role_type'=>$role_type,
		);
		//操作者
		$manage_role = array(
				'manage_id'=>Yii::app()->admin->id,
				'manage_type'=>Password::manage_type_admin
		);
		// 创建数据模型
		$model = Password::createPwd($type,$role_who,array(),$manage_role);
		if (! $model)
			throw new CHttpException(404,'没有设置当前的数据模型！');
		
		$this->_Ajax_Verify($model, 'password-form');
		
		if (isset($_POST['Password']))
		{
			$model = Password::createPwd($type,$role_who,$_POST['Password'],$manage_role);
			if ($model && $model->validate())
			{
				$transaction = $model->dbConnection->beginTransaction();
				try
				{
					$return = Password::executeCreateUpdatePwd($model);
					if (! $return)
						throw new Exception("密码加密错误");
					$this->log('设置'.$model::$_role_type[$role_type].'角色'.$model::$_password_type[$model->password_type], ManageLog::admin, ManageLog::create);
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return) && $return)
					$this->back();
			}
		}

		$this->render('create', array(
			'model'=>$model,
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

		$model->scenario = isset(Password::$_password_type_update[$model->password_type])? 
										Password::$_password_type_update[$model->password_type] :'update';	
		$this->_Ajax_Verify($model,'password-form');
		
		//var_dump($model->safeAttributeNames);
		if(isset($_POST['Password']))
		{
			$model->attributes = $_POST['Password'];
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
