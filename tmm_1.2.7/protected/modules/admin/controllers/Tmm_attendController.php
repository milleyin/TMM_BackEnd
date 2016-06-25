<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-02-25 18:30:04 */
class Tmm_attendController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Attend';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
					'Attend_Shops',
					'Attend_Actives',
					'Attend_User_Founder',
					'Attend_User',
					'Attend_Attend_P',
				),
			)),
		));
	}
	
// 	/**
// 	 * 更新
// 	 * @param integer $id
// 	 */
// 	public function actionUpdate($id)
// 	{
// 		$model=$this->loadModel($id);

// 		$model->scenario='update';
// 		$this->_Ajax_Verify($model,'attend-form');

// 		if(isset($_POST['Attend']))
// 		{
// 			$model->attributes=$_POST['Attend'];
// 			if($model->save() && $this->log('更新Attend',ManageLog::admin,ManageLog::update))
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
// 			$this->log('删除Attend',ManageLog::admin,ManageLog::delete);
			
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
// 			$this->log('还原Attend',ManageLog::admin,ManageLog::update);
			
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

// 		$model=new Attend;
		
// 		$this->render('index',array(
// 			'model'=>$model->search($criteria),
// 		));
// 	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Attend('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Attend']))
			$model->attributes=$_GET['Attend'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
// 	/**
// 	 * 禁用
// 	 * @param integer $id
// 	 */
// 	public function actionDisable($id)
// 	{
// 		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
// 			$this->log('禁用Attend',ManageLog::admin,ManageLog::update);	
		
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
						
// 	}
	
// 	/**
// 	 * 激活
// 	 * @param integer $id
// 	 */
// 	public function actionStart($id)
// 	{
// 		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
// 	 		$this->log('激活Attend',ManageLog::admin,ManageLog::update);
	 		
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
// 	}
}
