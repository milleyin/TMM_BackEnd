<?php
namespace app\controllers;

use FrontController;

/**
 * 商品控制器
 * @author Changhai Zhan
 *
 */
class ShopController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Shop';
    
	/**
	 * 首页
	 */
	public function actionIndex()
	{
	    $criteria = new \CDbCriteria;
	    $criteria->with = array(
	       'Shop_Upload'
	    );
	    $criteria->addColumnCondition(array(
	            '`t`.`status`'=>\Shop::_STATUS_NORMAL,
	            '`t`.`pad_id`'=> \Yii::app()->user->padId,
	            '`t`.`store_id`'=> \Yii::app()->user->id,
	    ));
	    
	    $this->render('index', array(
	    	'models' => $this->loadModelAll($criteria),
	    ));
	}
}