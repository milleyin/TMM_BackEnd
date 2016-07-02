<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-24 14:30:02 */
class Tmm_farmOuterController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='FarmOuter';
	
	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init()
	{
		parent::init();
		$this->_upload=Yii::app()->params['uploads_items_farm_outer'];
	}

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
	public function actionCreate($id)
	{
		$model=new FarmOuter;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'farm-outer-form');
		
		$this->_class_model='Dot';
		$model->FarmOuter_Dot=$this->loadModel($id,array(
			'with'=>array(
					'Dot_Shops'=>array('condition'=>'Dot_Shops.status != -1')
			))
		);
		
		if(isset($_POST['FarmOuter']))
		{
			$model->attributes=$_POST['FarmOuter'];
			//上传图片
			$uploads=	array('img');
			$files=$this->upload($model,$uploads);//获取图片
			$model->dot_id=$model->FarmOuter_Dot->id;
			$model->status=FarmOuter::status_offline;
			if($this->upload_error($model, $files, $uploads) && $model->save() && $this->log('创建农产品外链',ManageLog::admin,ManageLog::create))
			{
				$this->upload_save($model,$files,true,4,array('pc','app','share'));
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
		$model=$this->loadModel($id,'status=:status',array(':status'=>FarmOuter::status_offline));

		$model->scenario='update';
		$this->_Ajax_Verify($model,'farm-outer-form');

		if(isset($_POST['FarmOuter']))
		{
			//上传图片
			$uploads=	array('img');
			$data=$this->upload_save_data($model, $uploads);//保存原来的
			$model->attributes=$_POST['FarmOuter'];
				
			$files=$this->upload($model,$uploads);//获取图片
			if($model->validate())//提前验证
			{
				$old_path=$this->upload_update_data($model, $data, $files);//没有上传的重新赋值
				if($model->save(false) && $this->log('更新农产品外链',ManageLog::admin,ManageLog::update)){
					$this->upload_save($model,$files,true,4,array('pc','app','share'));//保存新上传的
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
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>-1)))
			$this->log('删除农产品外链',ManageLog::admin,ManageLog::delete);
			
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
			$this->log('还原农产品外链',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array('FarmOuter_Shops');
		$criteria->addColumnCondition(array('t.status'=>-1));

		$model=new FarmOuter;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new FarmOuter('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['FarmOuter']))
			$model->attributes=$_GET['FarmOuter'];

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
			$this->log('禁用农产品外链',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活农产品外链',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
	}
}
