<?php
/**
 * 酒店服务
 * @author Changhai Zhan
 *	创建时间：2015-07-30 16:45:18 */
class Tmm_wifiController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Wifi';
	
	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init()
	{
		parent::init();
		$this->_upload=Yii::app()->params['uploads_items_wifi'];
	}

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array('Wifi_Admin'=>array('select'=>'username,name')),
			)),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model=new Wifi;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'wifi-form');
		
		if(isset($_POST['Wifi']))
		{
			$model->attributes=$_POST['Wifi'];	
			//上传图片
			$uploads=	array('icon');
			$files=$this->upload($model,$uploads);//获取图片
		
			$model->admin_id=Yii::app()->admin->id;
			if($this->upload_error($model, $files, $uploads) && $model->save() && $this->log('创建酒店服务',ManageLog::admin,ManageLog::create))
			{
				$this->upload_save($model,$files,false);
				$this->back();
			}

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
		$this->_Ajax_Verify($model,'wifi-form');

		if(isset($_POST['Wifi']))
		{	
			//上传图片
			$uploads=	array('icon');
			$data=$this->upload_save_data($model, $uploads);//保存原来的
			$model->attributes=$_POST['Wifi'];
			
			$files=$this->upload($model,$uploads);//获取图片
			if($model->validate())//提前验证
			{
				$old_path=$this->upload_update_data($model, $data, $files);//没有上传的重新赋值
				if($model->save(false) && $this->log('更新酒店服务',ManageLog::admin,ManageLog::update)){
					$this->upload_save($model,$files,false);//保存新上传的
					$this->upload_delete($old_path);//删除原来的
					$this->back();
				}
			}
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
		if($this->loadModel($id)->updateByPk($id,array('status'=>-1)))
			$this->log('删除酒店服务',ManageLog::admin,ManageLog::delete);
			
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
			$this->log('还原酒店服务',ManageLog::admin,ManageLog::update);
			
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
// 			$this->log('彻底删除酒店服务',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		//$criteria->with=array();
		$criteria->addColumnCondition(array('t.status'=>-1));
		$criteria->with=array('Wifi_Admin'=>array('select'=>'username,name'));
		$model=new Wifi;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Wifi('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Wifi']))
			$model->attributes=$_GET['Wifi'];

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
			$this->log('禁用酒店服务',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活酒店服务',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
