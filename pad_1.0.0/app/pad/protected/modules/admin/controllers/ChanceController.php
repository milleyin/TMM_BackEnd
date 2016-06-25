<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *	创建时间：2016-06-01 15:23:59 */
class ChanceController extends AdminModulesController
{
	/**
	 * 当前操作模型的名称
	 * @var string
	 */
	public $_modelName = 'Chance';
	
	/**
	 * 管理
	 */
	public function actionAdmin()
	{        
		$model = new \Chance('search');
		$model->Chance_User = new \User('search');
		$model->Chance_Store = new \Store('search');
		$model->Chance_Pad = new \Pad('search');
		//清除默认值
		$model->unsetAttributes();
		$model->Chance_User->unsetAttributes();
		$model->Chance_Store->unsetAttributes();
		$model->Chance_Pad->unsetAttributes();
		if (isset($_GET['Chance']))
			$model->attributes = $_GET['Chance'];
		if (isset($_GET['User']))
		    $model->Chance_User->attributes = $_GET['User'];
		if (isset($_GET['Store']))
		    $model->Chance_Store->attributes = $_GET['Store'];
		if (isset($_GET['Pad']))
		    $model->Chance_Pad->attributes = $_GET['Pad'];
		
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
		$criteria = new \CDbCriteria;
		$criteria->with = array(
		    'Chance_Pad',
		    'Chance_Store'=>array(
                'with'=>array(
                    'Store_Area_province',
                    'Store_Area_city',
                    'Store_Area_district',
                ),
		    ),
		    'Chance_User',
		);
		$this->render('view', array(
			'model'=>$this->loadModelByPk($id, $criteria),
		));
	}
}
