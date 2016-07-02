<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-01-20 15:12:35 */
class Tmm_reasonController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Reason';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
					'Reason_Admin'=>array('select'=> 'id, username, name, phone'),
				)
			)),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model=new Reason;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'reason-form');
		
		if(isset($_POST['Reason']))
		{
			$model->attributes = $_POST['Reason'];
			if($model->save() && $this->log('创建售后原因',ManageLog::admin,ManageLog::create))
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
		$this->_Ajax_Verify($model,'reason-form');

		if(isset($_POST['Reason']))
		{
			$model->attributes=$_POST['Reason'];
			if($model->save() && $this->log('更新售后原因',ManageLog::admin,ManageLog::update))
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
		if($this->loadModel($id,'`status`=:status',array(':status'=>Reason::status_disable))->updateByPk($id,array('status'=>Reason::status_del)))
			$this->log('删除售后原因',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=:status',array(':status'=>Reason::status_del))->updateByPk($id,array('status'=>Reason::status_disable)))
			$this->log('还原售后原因',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Reason_Admin'=>array('select'=> 'id, username, name, phone'),
		);
		$criteria->addColumnCondition(array('t.status'=>Reason::status_del));

		$model=new Reason;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Reason('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Reason']))
			$model->attributes=$_GET['Reason'];

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
		if($this->loadModel($id,'`status`=:status', array(':status'=>Reason::status_normal))->updateByPk($id,array('status'=>Reason::status_disable)))
			$this->log('禁用售后原因',ManageLog::admin,ManageLog::update);			
		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if($this->loadModel($id,'`status`=:status', array(':status'=>Reason::status_disable))->updateByPk($id, array('status'=>Reason::status_normal)))
	 		$this->log('激活售后原因',ManageLog::admin,ManageLog::update);
		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
	}
}
