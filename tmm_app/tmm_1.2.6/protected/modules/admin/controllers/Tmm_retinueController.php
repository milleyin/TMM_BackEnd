<?php
/**
 * 随行人员
 * @author Changhai Zhan
 *	创建时间：2015-08-10 14:49:33 */
class Tmm_retinueController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Retinue';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array('Retinue_User'=>array('select'=>'id,phone,nickname'))
			)),
		));
	}

	/**
	 * 创建随行人员
	 */
	public function actionCreate($id)
	{
		$model=new Retinue;
		$this->_class_model='User';
		$model->Retinue_User=$this->loadModel($id,'`status`=1');
		$model->user_id=$model->Retinue_User->id;
		
		$model->scenario='create';
		$this->_Ajax_Verify($model,'retinue-form');

		if(isset($_POST['Retinue']))
		{
			$model->attributes=$_POST['Retinue'];
			$model->user_id=$model->Retinue_User->id;
			
			if($model->save() && $this->log('创建随行人员',ManageLog::admin,ManageLog::create))
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
		$model=$this->loadModel($id,array(
			'with'=>array('Retinue_User'),
			'condition'=>'Retinue_User.status=1 AND t.status=1',
		));

		$model->scenario='update';
		$this->_Ajax_Verify($model,'retinue-form');

		if(isset($_POST['Retinue']))
		{
			$model->attributes=$_POST['Retinue'];
			if($model->save() && $this->log('更新随行人员',ManageLog::admin,ManageLog::update))
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
			$this->log('删除随行人员',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=-1')->updateByPk($id,array('status'=>0)))
			$this->log('还原随行人员',ManageLog::admin,ManageLog::update);
			
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
// 			$this->log('彻底删除随行人员',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex($id='')
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Retinue_User'=>array('select'=>'id,phone,nickname')
		);
		$criteria->addColumnCondition(array('t.status'=>-1));
		if($id != '')
			$criteria->addColumnCondition(array('t.user_id'=>$id));
		$model=new Retinue;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin($id='')
	{
		$model=new Retinue('search');
		$model->unsetAttributes();  // 删除默认属性
		$model->Retinue_User=new User('search');
		$model->Retinue_User->unsetAttributes();  // 删除默认属性
		if($id != '')
			$model->Retinue_User->id=$id;
		if(isset($_GET['Retinue']))
			$model->attributes=$_GET['Retinue'];
		if(isset($_GET['User']))
			$model->Retinue_User->attributes=$_GET['User'];

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
			$this->log('禁用随行人员',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		$model = $this->loadModel($id,'`status`=0');
		if($model->is_main == Retinue::is_main)
		{
			Retinue::model()->updateAll(array('status'=>0),array(
				'condition'=>'user_id=:user_id AND status=1',
				'params'=>array(':user_id'=>$model->user_id),
			));
		}
		if($model->updateByPk($id,array('status'=>1)))
	 		$this->log('激活随行人员',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}	
}
