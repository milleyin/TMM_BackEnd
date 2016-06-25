<?php
namespace app\controllers;

use FrontController;

/**
 * 抽奖配置
 * @author Changhai Zhan
 *
 */
class ConfigController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Config';
    
	/**
	 * 首页 转盘
	 */
	public function actionIndex()
	{
	    $criteria = new \CDbCriteria;
	    $criteria->with = array(
	    	'Config_Upload',
	    );
	    $criteria->addColumnCondition(array(
	    	'`t`.`pad_id`'=>\Yii::app()->user->padId,
	        '`t`.`store_id`'=>\Yii::app()->user->id,
	    ));
	    
	    $this->render('index', array(
	        'model'=>$this->loadModel($criteria),
	    ));
	}	
}