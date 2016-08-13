<?php
namespace app\user\controllers;

use UserModulesController;

/**
 * Class RecordController
 * @package app\user\controllers
 *
 * @author Moore Mo
 */
class RecordController extends UserModulesController
{
//     /**
//      * 奖品兑换
//      * @param int $id 体验店id
//      */
//     public function actionIndex($id) {
//         $criteria = new \CDbCriteria;
//         $criteria->with = array(
//             'Record_Upload'
//         );
//         $criteria->addColumnCondition(array(
//             '`t`.`user_id`' => \Yii::app()->user->id,
//             '`t`.`store_id`' => $id,
//             '`t`.`receive_type`' => \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE,
//             '`t`.`exchange_status`' => \Record::RECORD_EXCHANGE_STATUS_NO,
//             '`t`.`status`' => \Record::_STATUS_NORMAL
//         ));
//         $criteria->order = '`t`.`add_time` desc';
        
//         $this->_modelName = 'Record';
//         $recordModel = $this->loadModelAll($criteria);

//         $this->render('index', array(
//             'model' => $recordModel,
//         ));
//     }
    
    /**
     * url 跳转链接
     * @param int $id 记录ID
     */
    public function actionUrl($id) 
    {
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            '`t`.`user_id`' => \Yii::app()->user->id,
            '`t`.`receive_type`' => \Prize::PRIZE_RECEIVE_TYPE_YZ,
            '`t`.`exchange_status`' => \Record::RECORD_EXCHANGE_STATUS_NONE,
            '`t`.`status`' => \Record::_STATUS_NORMAL
        ));
        $this->_modelName = 'Record';
        $model = $this->loadModelByPk($id, $criteria);
        $this->redirect($model->url);
    }

//     /**
//      * 兑换奖品
//      * @param $id 中奖记录id
//      */
//     public function actionExchange($id) {
//         $criteria = new \CDbCriteria;
//         $criteria->addColumnCondition(array(
//             '`user_id`' => \Yii::app()->user->id,
//             '`receive_type`' => \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE,
//             '`exchange_status`' => \Record::RECORD_EXCHANGE_STATUS_NO,
//             '`status`' => \Record::_STATUS_NORMAL
//         ));

//         $model = \Record::model()->findByPk($id, $criteria);
//         $back = new \stdClass();

//         if ($model) {
//             // 更新中奖记录的状态为 兑换中
//             if (\Record::model()->updateByPk($id, array('exchange_status' => \Record::RECORD_EXCHANGE_STATUS_YES, 'exchange_time' => time()))) {
//                 $isOk = true;
//             }
//         }

//         if (isset($isOk) && $isOk) {
//             $back->status = 1;
//             $back->prompt = '兑换成功';
//         } else {
//             $back->status = 0;
//             $back->prompt = '兑换失败';
//         }

//         $this->ajaxReturn($back);
//     }
}