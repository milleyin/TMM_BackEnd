<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-10 10:55:12 */
class Tmm_billsController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Bills';

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
// 	 *管理页
// 	 */
// 	public function actionAdmin()
// 	{
// 		$model=new Bills('search');
// 		$model->unsetAttributes();  // 删除默认属性
// 		if(isset($_GET['Bills']))
// 			$model->attributes=$_GET['Bills'];

// 		$this->render('admin',array(
// 			'model'=>$model,
// 		));
// 	}

// 	/**
// 	 * 提现列表
// 	 */
// 	public function actionCash_list(){

// 		if(isset($_GET['cash_id']) || isset($_GET['bills_count']) ){
// 			$model=new Bills('search');
// 			$model->unsetAttributes();  // 删除默认属性
// 			if(isset($_GET['Bills']))
// 				$model->attributes=$_GET['Bills'];

// 			$this->render('cash_list',array(
// 				'model'=>$model,
// 				'cash_id'=>isset($_GET['cash_id'])?$_GET['cash_id']:'',
// 				'bills_count'=>isset($_GET['bills_count'])?$_GET['bills_count']:'',
// 			));
// 		}
// 	}

// 	/**
// 	 * 总金额
// 	 */
// 	public function actionBills_count(){
// 		$model=new Bills('search');
// 		$model->unsetAttributes();  // 删除默认属性
// 		if(isset($_GET['Bills']))
// 			$model->attributes=$_GET['Bills'];

// 		$this->render('bills_count',array(
// 			'model'=>$model,
// 		));
// 	}
// 	/**
// 	 * 禁用
// 	 * @param integer $id
// 	 */
// 	public function actionDisable($id){
// 		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
// 			$this->log('禁用Bills',ManageLog::admin,ManageLog::update);			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
// 	}
	
// 	/**
// 	 * 激活
// 	 * @param integer $id
// 	 */
// 	public function actionStart($id){
// 		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
// 	 		$this->log('激活Bills',ManageLog::admin,ManageLog::update);
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
// 	}
}
