<?php
namespace app\controllers;

use FrontController;

/**
 * 抽奖机会
 * @author Changhai Zhan
 *
 */
class ChanceController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Chance';
    
    /**
     * 首页 抽奖机会
     */
    public function actionIndex()
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Chance_User'
        );
        $criteria->addColumnCondition(array(
            '`t`.`pad_id`'=>\Yii::app()->user->padId,
            '`t`.`store_id`'=>\Yii::app()->user->id,
            '`t`.`date_time`'=>strtotime(date('Y-m-d', time())),
            '`t`.`status`'=>\Chance::_STATUS_NORMAL,
        ));
        $this->errorThrow = false;
        $this->render('index', array(
           'model' => $this->loadModel($criteria),
        ));
    }
}