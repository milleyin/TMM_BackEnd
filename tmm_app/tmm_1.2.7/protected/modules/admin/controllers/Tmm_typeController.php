<?php
/**
 * 类型值
 * @author Changhai Zhan
 *	创建时间：2016-03-16 11:12:25 */
class Tmm_typeController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Type';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
					'with'=>array('Type_Admin'),
			)),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model = new Type;
		//管理员
		$model->role_type = Type::role_type_admin;
		
		$model->scenario = 'create';
		$this->_Ajax_Verify($model, 'type-form');
		
		if (isset($_POST['Type']))
		{
			$model->attributes = $_POST['Type'];
			if ($model->save() && $this->log('创建类型值', ManageLog::admin, ManageLog::create))
				$this->back();
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
		$model=$this->loadModel($id);

		$model->scenario='update';
		$this->_Ajax_Verify($model,'type-form');

		if(isset($_POST['Type']))
		{
			$model->attributes=$_POST['Type'];
			if($model->save() && $this->log('更新类型值',ManageLog::admin,ManageLog::update))
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
		if($this->loadModel($id,'`status`=:status',array(':status'=>Type::status_dis))->updateByPk($id,array('status'=>Type::status_del)))
			$this->log('删除类型值',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=:status', array(':status'=>Type::status_del))->updateByPk($id,array('status'=>Type::status_dis)))
			$this->log('还原类型值',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array(
				'Type_Admin'
		);
		$criteria->addColumnCondition(array('`t`.`status`'=>Type::status_del));

		$model=new Type;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Type('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Type']))
			$model->attributes=$_GET['Type'];

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
		if($this->loadModel($id,'`status`=:status',array(':status'=>Type::status_suc))->updateByPk($id,array('status'=>Type::status_dis)))
			$this->log('禁用类型值',ManageLog::admin,ManageLog::update);	
		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));						
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if($this->loadModel($id, '`status`=:status',array(':status'=>Type::status_dis))->updateByPk($id,array('status'=>Type::status_suc)))
	 		$this->log('激活类型值',ManageLog::admin,ManageLog::update);
	 		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));		
	}
}
