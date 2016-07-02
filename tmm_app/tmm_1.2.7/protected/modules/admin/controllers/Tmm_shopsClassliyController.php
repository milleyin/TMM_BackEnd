<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-21 09:58:04 */
class Tmm_shopsClassliyController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='ShopsClassliy';

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
		$model=new ShopsClassliy;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'shops-classliy-form');
		
		if(isset($_POST['ShopsClassliy']))
		{
			$model->attributes=$_POST['ShopsClassliy'];
			if($model->save() && $this->log('创建商品分类',ManageLog::admin,ManageLog::create))
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
		$this->_Ajax_Verify($model,'shops-classliy-form');
		
		if(isset($_POST['ShopsClassliy']))
		{
			$model->attributes=$_POST['ShopsClassliy'];
			if($model->save() && $this->log('更新商品分类',ManageLog::admin,ManageLog::update))
				$this->back();
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new ShopsClassliy('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['ShopsClassliy']))
			$model->attributes=$_GET['ShopsClassliy'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

}
