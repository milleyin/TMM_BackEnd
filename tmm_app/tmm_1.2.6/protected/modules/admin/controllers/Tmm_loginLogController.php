<?php
/**
 * 
 * @author moore
 *	创建时间：2015-08-06 13:51:52 */
class Tmm_loginLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='LoginLog';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
					'with'=>array(
							'Login_Admin'=>array('select'=>'username,name'),
							'Login_Agent'=>array('select'=>'phone'),
							'Login_StoreUser'=>array('select'=>'phone'),
							'Login_User'=>array('select'=>'phone,nickname')
					)
			)),
		));
	}



	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Login_Admin'=>array('select'=>'username,name'),
				'Login_Agent'=>array('select'=>'phone'),
				'Login_StoreUser'=>array('select'=>'phone'),
				'Login_User'=>array('select'=>'phone,nickname')
		);
		$criteria->addColumnCondition(array('t.status'=>-1));
		
		$model=new LoginLog;	
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new LoginLog('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['LoginLog']))
			$model->attributes=$_GET['LoginLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		if($this->loadModel($id,'`status`!=:status',array(':status'=>LoginLog::status_del))->updateByPk($id,array('status'=>LoginLog::status_del)))
			$this->log('删除登录日志',ManageLog::admin,ManageLog::update);	
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
