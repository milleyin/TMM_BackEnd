<?php
/**
 * 管理员控制器
 * @author Changhai Zhan
 *
 */
class Tmm_adminController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Admin';
	
	/**
	 * 显示自己的信息
	 */
	public function actionOwn()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(Yii::app()->admin->id,array(
                            'with'=>array('Admin_Admin'=>array('select'=>'name,username'))	
		))));
	}
	
	/**
	 * 修改密码（自己）
	 */
	public function actionPwd()
	{
		$model=$this->loadModel(Yii::app()->admin->id);
		$model->scenario='pwd';
		$this->_Ajax_Verify($model,'admin-form');
		
		if(isset($_POST['Admin']))
		{
			$model->attributes=$_POST['Admin'];
 			$model->password=$model::pwdEncrypt($model->new_pwd);
			if($model->save() && $this->log('修改自己的密码管理员',ManageLog::admin,ManageLog::update))
				$this->success("修改密码成功，请重新登陆！",'/admin/tmm_login/out',3);
				//$this->redirect(array('own'));
		}
		$this->render('pwd',array(
				'model'=>$model,
		));
	}

	/**
	 * 修改自己的信息
	 */
	public function actionModify()
	{
		$model=$this->loadModel(Yii::app()->admin->id);
		$model->scenario='modify';
		$this->_Ajax_Verify($model,'admin-form');
		if(isset($_POST['Admin']))
		{
			$model->attributes=$_POST['Admin'];
			if($model->save() && $this->log('修改自己的信息管理员',ManageLog::admin,ManageLog::update))
				$this->redirect(array('own'));
		}
		$this->render('modify',array(
				'model'=>$model,
		));
	}
	
	
		/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
					'with'=>array('Admin_Admin'=>array('select'=>'name,username'))
		))));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model=new Admin;
		
		$model->scenario='create';
		$this->_Ajax_Verify($model,'admin-form');	
		//var_dump($model->safeAttributeNames);
                
		if(isset($_POST['Admin']))
		{
			$model->attributes=$_POST['Admin'];
			$model->password=$model::pwdEncrypt($model->_pwd);
			$model->admin_id=Yii::app()->admin->id;
			if($model->save() && $this->log('创建管理员',ManageLog::admin,ManageLog::create))
				$this->back();
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		$model->scenario='update';
		$this->_Ajax_Verify($model,'admin-form');

		if(isset($_POST['Admin']))
		{
			$model->attributes=$_POST['Admin'];
			if($model->_pwd !='')
				$model->password=$model::pwdEncrypt($model->_pwd);
			if($model->save() && $this->log('更新管理员',ManageLog::admin,ManageLog::update))
				$this->back();
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		//$this->loadModel($id)->delete();
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>-1)))
			$this->log('删除管理员',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=-1')->updateByPk($id,array('status'=>1)))
			$this->log('还原管理员',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1')->delete())
// 			$this->log('彻底删除管理员',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array('Admin_Admin'=>array('select'=>'username,name'));
		$criteria->addColumnCondition(array('t.status'=>-1));
		
		$model=new Admin;
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Admin('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Admin']))
			$model->attributes=$_GET['Admin'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
			$this->log('禁用管理员',ManageLog::admin,ManageLog::update);			
		$this->back();
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活管理员',ManageLog::admin,ManageLog::update);
		$this->back();
	}
}
