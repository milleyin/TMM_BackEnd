<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-06 16:41:52 */
class Tmm_manageLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='ManageLog';

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

// 	/**
// 	 * 删除
// 	 * @param integer $id
// 	 */
// 	public function actionDelete($id)
// 	{
// 		//$this->loadModel($id)->delete();
// 		if($this->loadModel($id)->updateByPk($id,array('status'=>-1)))
// 			$this->log('删除ManageLog',ManageLog::admin,ManageLog::delete);
			
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
// 			$this->log('还原ManageLog',ManageLog::admin,ManageLog::update);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1')->delete())
// 			$this->log('彻底删除ManageLog',ManageLog::admin,ManageLog::delete);
			
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

// 		$model=new ManageLog;
		
// 		$this->render('index',array(
// 			'model'=>$model->search($criteria),
// 		));
// 	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/table.css');	
		$model=new ManageLog('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['ManageLog']))
			$model->attributes=$_GET['ManageLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
// 	/**
// 	 * 禁用
// 	 * @param integer $id
// 	 */
// 	public function actionDisable($id){
// 		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
// 			$this->log('禁用ManageLog',ManageLog::admin,ManageLog::update);			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}
	
// 	/**
// 	 * 激活
// 	 * @param integer $id
// 	 */
// 	public function actionStart($id){
// 		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
// 	 		$this->log('激活ManageLog',ManageLog::admin,ManageLog::update);
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}
}
