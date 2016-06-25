<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *	创建时间：2016-06-01 15:28:04 */
class SelectController extends AdminModulesController
{
	/**
	 * 当前操作模型的名称
	 * @var string
	 */
	public $_modelName = 'Select';
	
	/**
	 * 管理
	 */
	public function actionAdmin()
	{
		$model = new \Select('search');
		$model->Select_Ad = new \Ad('search');
		$model->Select_Pad = new \Pad('search');
		$model->Select_Store = new \Store('search');
		//清除默认值
		$model->unsetAttributes();
		$model->Select_Ad->unsetAttributes();
		$model->Select_Pad->unsetAttributes();
		$model->Select_Store->unsetAttributes();
		if (isset($_GET['Select']))
			$model->attributes = $_GET['Select'];
		if (isset($_GET['Ad']))
		    $model->Select_Ad->attributes = $_GET['Ad'];
		if (isset($_GET['Pad']))
		    $model->Select_Pad->attributes = $_GET['Pad'];
		if (isset($_GET['Store']))
		    $model->Select_Store->attributes = $_GET['Store'];

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
            'Select_Ad'=>array(
                'with'=>array(
            	  'Ad_Upload',
                ),
            ),
            'Select_Pad',
            'Select_Store'=>array(
                'with'=>array(
                    'Store_Area_province',
                    'Store_Area_city',
                    'Store_Area_district',
                ),
            ),
	    );
		$this->render('view', array(
			'model'=>$this->loadModelByPk($id, $criteria),
		));
	}

	/**
	 * 创建
	 * @return Success page "view"
	 */
	public function actionCreate($id)
	{
		$model = new \Select;

		$this->_modelName = 'Ad';
		$model->Select_Ad = $this->loadModelByPk($id, 'status=:status', array(':status'=>\Ad::_STATUS_NORMAL));
		$model->ad_id = $model->Select_Ad->id;
		$model->ad_type = $model->Select_Ad->type;
		$model->scenario = 'create';
		
		if (isset($_POST['Select']))
		{
			$model->attributes = $_POST['Select'];
			if (is_array($model->pad_id))
			{
			    $pad_ids = $model->pad_id;
			    foreach ($pad_ids as $pad_id)
			    {
			        $model->scenario = 'create';
			        $model->isNewRecord = true;
			        $model->id = null;
			        $model->pad_id = $pad_id;
			        $model->save();
			    }
			    $this->redirect(array('admin'));
			}
			else
			{
			    $model->save();
			    $this->redirect(array('view', 'id'=>$model->id));
			}		
		}
	}

	/**
	 * 更新
	 * @param integer $id
	 * @return Success page "view"
	 */
	public function actionUpdate($id)
	{
	    $model = new \Select;
	    
	    $this->_modelName = 'Ad';
	    $model->Select_Ad = $this->loadModelByPk($id, 'status=:status', array(':status'=>\Ad::_STATUS_NORMAL));
	    $model->scenario = 'update';
	    
	    if (isset($_POST['Select']))
	    {
	        $model->attributes = $_POST['Select'];
	        $deletePadIds = array();
	        if ( !is_array($model->pad_id))
	            $model->pad_id = array($model->pad_id);
            $pad_ids = $model->pad_id;
            foreach ($pad_ids as $pad_id)
            {
                $model->pad_id = $pad_id;
                if ($model->validate())
                    $deletePadIds[] = $pad_id;
            }
	        if ( !empty($deletePadIds))
	        {
                $criteria = new \CDbCriteria;
                $criteria->addInCondition('pad_id', $deletePadIds);
                $criteria->addColumnCondition(array(
                	'ad_id'=>$model->Select_Ad->id,
                ));
                \Select::model()->deleteAll($criteria);
                $this->redirect(array('admin'));
	        }
	    }
	}

	/**
	 * 删除
	 * @param integer $id the ID of the model to be deleted
	 * @return Success page "admin"
	 */
	public function actionDelete($id)
	{
	    $this->loadModelByPk($id)->delete();
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
}
