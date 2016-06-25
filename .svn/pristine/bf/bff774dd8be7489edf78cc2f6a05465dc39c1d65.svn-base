<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-29 14:31:24 */
class UserBankController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='UserBank';

// 	/**
// 	 * 查看详情
// 	 * @param integer $id
// 	 */
// 	public function actionView($id)
// 	{
// 		$this->render('view',array(
// 			'model'=>$this->loadModel($id),
// 		));
// 	}

// 	/**
// 	 * 创建
// 	 */
// 	public function actionCreate()
// 	{
// 		$model=new UserBank;
	
// 		$model->scenario='create';
// 		$this->_Ajax_Verify($model,'user-bank-form');
		
// 		if(isset($_POST['UserBank']))
// 		{
// 			$model->attributes=$_POST['UserBank'];
// 			if($model->save() && $this->log('创建UserBank',ManageLog::admin,ManageLog::create))
// 				$this->back();
// 		}

// 		$this->render('create',array(
// 			'model'=>$model,
// 		));
// 	}

// 	/**
// 	 * 更新
// 	 * @param integer $id
// 	 */
// 	public function actionUpdate($id)
// 	{
// 		$model=$this->loadModel($id);

// 		$model->scenario='update';
// 		$this->_Ajax_Verify($model,'user-bank-form');

// 		if(isset($_POST['UserBank']))
// 		{
// 			$model->attributes=$_POST['UserBank'];
// 			if($model->save() && $this->log('更新UserBank',ManageLog::admin,ManageLog::update))
// 				$this->back();
// 		}

// 		$this->render('update',array(
// 			'model'=>$model,
// 		));
// 	}

// 	/**
// 	 * 删除
// 	 * @param integer $id
// 	 */
// 	public function actionDelete($id)
// 	{
// 		//$this->loadModel($id)->delete();
// 		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>-1)))
// 			$this->log('删除UserBank',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}
	
// 	/**
// 	 * 还原
// 	 * @param integer $id
// 	 */
// 	public function actionRestore($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1')->updateByPk($id,array('status'=>1)))
// 			$this->log('还原UserBank',ManageLog::admin,ManageLog::update);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}
	
// 	/**
// 	 * 审核通过
// 	 * @param integer $id
// 	 */
// 	public function actionPass($id){
		
// 	}
	
// 	/**
// 	 * 审核不通过
// 	 * @param integer $id
// 	 */
// 	public function actionNopass($id){
	
// 	}
	
// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1')->delete())
// 			$this->log('彻底删除UserBank',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

// 	/**
// 	 * 垃圾回收页
// 	 */
// 	public function actionIndex()
// 	{
// 		$criteria=new CDbCriteria;
// 		//$criteria->with=array();
// 		$criteria->addColumnCondition(array('status'=>-1));

// 		$model=new UserBank;
		
// 		$this->render('index',array(
// 			'model'=>$model->search($criteria),
// 		));
// 	}
	
// 	/**
// 	 *管理页
// 	 */
// 	public function actionAdmin()
// 	{
// 		$model=new UserBank('search');
// 		$model->unsetAttributes();  // 删除默认属性
// 		if(isset($_GET['UserBank']))
// 			$model->attributes=$_GET['UserBank'];

// 		$this->render('admin',array(
// 			'model'=>$model,
// 		));
// 	}
	
// 	/**
// 	 * 禁用
// 	 * @param integer $id
// 	 */
// 	public function actionDisable($id){
// 		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
// 			$this->log('禁用UserBank',ManageLog::admin,ManageLog::update);			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
// 	}
	
// 	/**
// 	 * 激活
// 	 * @param integer $id
// 	 */
// 	public function actionStart($id){
// 		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
// 	 		$this->log('激活UserBank',ManageLog::admin,ManageLog::update);
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
// 	}
}
