<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *	创建时间：2016-06-01 15:28:46 */
class UploadController extends AdminModulesController
{
	/**
	 * 当前操作模型的名称
	 * @var string
	 */
	public $_modelName = 'Upload';
	
	/**
	 * 管理
	 */
	public function actionAdmin()
	{
		$model = new \Upload('search');
		//清除默认值
		$model->unsetAttributes();
		if (isset($_GET['Upload']))
			$model->attributes = $_GET['Upload'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}
	
	/**
	 * 查看
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model'=>$this->loadModelByPk($id),
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 * @return Success page "view"
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModelByPk($id);

		if ($model->upload_type == $model::UPLOAD_UPLOAD_TYPE_IMAGE)
		  $model->scenario = 'update_image';
		elseif ($model->upload_type == $model::UPLOAD_UPLOAD_TYPE_VIDEO)
		    $model->scenario = 'update_video';
		elseif ($model->upload_type == $model::UPLOAD_UPLOAD_TYPE_FILE)
		    $model->scenario = 'update_file';
		else
		    $model->scenario = 'update_image';
				
		$this->ajaxVerify($model, 'upload-form');

		if (isset($_POST['Upload']))
		{
			$model->attributes = $_POST['Upload'];
			if ($model->save())
				$this->redirect($this->getLastUrl());
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}
}
