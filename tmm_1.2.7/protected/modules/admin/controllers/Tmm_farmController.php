<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-23 10:31:39 */
class Tmm_farmController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Farm';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model=new Farm;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'farm-form');
		
		if(isset($_POST['Farm']))
		{
			$model->attributes=$_POST['Farm'];
			if($model->save() && $this->log('创建Farm',ManageLog::admin,ManageLog::create))
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
		$this->_Ajax_Verify($model,'farm-form');

		if(isset($_POST['Farm']))
		{
			$model->attributes=$_POST['Farm'];
			if($model->save() && $this->log('更新Farm',ManageLog::admin,ManageLog::update))
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
			$this->log('删除Farm',ManageLog::admin,ManageLog::delete);
			
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
			$this->log('还原Farm',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 审核通过
	 * @param integer $id
	 */
	public function actionPass($id){
		
	}
	
	/**
	 * 审核不通过
	 * @param integer $id
	 */
	public function actionNopass($id){
	
	}
	
	/**
	 * 彻底删除
	 * @param integer $id
	 */
	public function actionClear($id)
	{
		if($this->loadModel($id,'`status`=-1')->delete())
			$this->log('彻底删除Farm',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		//$criteria->with=array();
		$criteria->addColumnCondition(array('status'=>-1));

		$model=new Farm;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Farm('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Farm']))
			$model->attributes=$_GET['Farm'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id){
		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
			$this->log('禁用Farm',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活Farm',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
	}
}
