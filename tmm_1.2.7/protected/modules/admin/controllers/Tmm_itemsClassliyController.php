<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-05 14:03:10 */
class Tmm_itemsClassliyController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='ItemsClassliy';

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
		$model=new ItemsClassliy;
	
		$model->scenario='create';
		
		$this->_Ajax_Verify($model,'items-classliy-form');
		
		if(isset($_POST['ItemsClassliy']))
		{
			$model->attributes=$_POST['ItemsClassliy'];
			if($model->save() && $this->log('创建项目分类',ManageLog::admin,ManageLog::create))
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
		$this->_Ajax_Verify($model,'items-classliy-form');

		if(isset($_POST['ItemsClassliy']))
		{
			$model->attributes=$_POST['ItemsClassliy'];
			if($model->save() && $this->log('更新项目分类',ManageLog::admin,ManageLog::update))
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
		$model=new ItemsClassliy('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['ItemsClassliy']))
			$model->attributes=$_GET['ItemsClassliy'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
}
