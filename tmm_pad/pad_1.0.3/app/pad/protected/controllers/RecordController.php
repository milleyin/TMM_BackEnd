<?php
namespace app\controllers;

use FrontController;

/**
 * 抽奖记录控制器
 * @author Changhai Zhan
 *
 */
class RecordController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Record';
    
    /**
     * 首页 奖品记录
     */
    public function actionIndex()
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Record_User',
            'Record_Pad',
            'Record_Store',
        );
        $criteria->addColumnCondition(array(
            '`t`.`pad_id`'=> \Yii::app()->user->padId,
            '`t`.`store_id`'=> \Yii::app()->user->id,
            '`t`.`status`'=> \Record::_STATUS_NORMAL,
            '`Record_Pad`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Record_Store`.`status`'=>\Store::_STATUS_NORMAL,
        ));
        $criteria->order = '`t`.`up_time` desc';
        $criteria->limit = 20;
        $this->errorThrow = false;
        $this->render('index', array(
            'models' => $this->loadModelAll($criteria),
        ));
    }
    
    /**
     * 是否有待选择的奖品
     */
    public function actionSelect()
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Record_Upload',
            'Record_User',
        );
        $criteria->addColumnCondition(array(
            '`t`.`type`' => \Config::Config_TYPE_PAY,                                                         //付费抽奖
            '`t`.`exchange_status`' => \Record::RECORD_EXCHANGE_STATUS_SELECT,  //待选择
            '`t`.`status`' => \Record::_STATUS_NORMAL,                                                  //中奖记录
            '`t`.`pad_id`' => \Yii::app()->user->padId,
            '`t`.`store_id`' => \Yii::app()->user->id,
        ));
        $this->_modelName = 'Record';
        $this->render('select', array(
            'model' => $this->loadModel($criteria),
        ));
    }
    
    /**
     * 放弃奖品
     * @param integer $id
     */
    public function actionQuit($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            'type' => \Config::Config_TYPE_PAY,                                                         //付费抽奖
            'exchange_status' => \Record::RECORD_EXCHANGE_STATUS_SELECT,  //待选择
            'status' => \Record::_STATUS_NORMAL,                                                  //中奖记录
            'pad_id' => \Yii::app()->user->padId,
            'store_id' => \Yii::app()->user->id,
        ));
        $this->_modelName = 'Record';
        $model = $this->loadModelByPk($id, $criteria);
        $model->exchange_status =  \Record::RECORD_EXCHANGE_STATUS_QUIT;
        $this->render('quit', array(
            'result' => $model->save(false),
        ));
    }
    
    /**
     * 选择奖品 
     * 1 查询是否有奖品
     * 2 设置 领取状态
     * 3、扣除库存 如果库存不足 只能放弃
     * @param integer $id
     */
    public function actionConfirm($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Record_Upload',
            'Record_User',
        );
        $criteria->addColumnCondition(array(
            '`t`.`type`' => \Config::Config_TYPE_PAY,                                                         //付费抽奖
            '`t`.`exchange_status`' => \Record::RECORD_EXCHANGE_STATUS_SELECT,  //待选择
            '`t`.`status`' => \Record::_STATUS_NORMAL,                                                  //中奖记录
            '`t`.`pad_id`' => \Yii::app()->user->padId,
            '`t`.`store_id`' => \Yii::app()->user->id,
        ));
        $this->_modelName = 'Record';
        $model = $this->loadModelByPk($id, $criteria);
        //开启事物
        $transaction = $model->dbConnection->beginTransaction();
        try
        {
            $model = \Record::model()->findByPk($model->id, $criteria);
            if ( !$model) {
                throw new \Exception('确认奖品 没有找到待选择的奖品');
            }
            //到店兑换 需要打印 设置未打印
            if ($model->receive_type == \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE) {
                $model->print_status = \Record::RECORD_PRINT_STATUS_NO;
            } else {
                $model->print_status = \Record::RECORD_PRINT_STATUS_NONE;
            }
            //有赞兑换 无需兑换 
            if ($model->receive_type == \Prize::PRIZE_RECEIVE_TYPE_YZ || $model->receive_type == \Prize::PRIZE_RECEIVE_TYPE_NONE) {
                $model->exchange_status = \Record::RECORD_EXCHANGE_STATUS_NONE;
            } else {
                $model->exchange_status = \Record::RECORD_EXCHANGE_STATUS_NO;
            }
            if ( !$model->save(false)) {
                throw new \Exception('确认奖品 改变奖品状态失败');
            }
            //奖品
            $prizeModel = \Prize::model()->findByPk($model->prize_id);
            if ( !$prizeModel) {
                throw new \Exception('确认奖品 没有找到奖品');
            }
            // 如果奖品是有库存的
            if ($prizeModel->number != -1) {
                //减少库存
                if ( !\Prize::model()->updateByPk($prizeModel->id, array('number' => new \CDbExpression('`number`-1')))) {
                    throw new \Exception('减少库存奖品错误');
                }
                $prizeModel = \Prize::model()->findByPk($prizeModel->id);
                //再次验证
                if ($prizeModel->number < 0) {
                    throw new \Exception('减少库存奖品错误');
                }
            }
            //机会 设置
            $chanceModel = \Chance::model()->findByPk($model->chance_id);
            if ( !$chanceModel) {
                throw new \Exception('确认奖品 没有找到抽奖机会');
            }
            if ($chanceModel->number > 0) {
                $chanceModel->status = \Chance::_STATUS_DELETED;
                if ( !$chanceModel->save(false)) {
                    throw new \Exception('确认奖品 机会设置已结束');
                }
            }
             $return = true;
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::log($e->getMessage(), 'error', __METHOD__);
        }
        if (isset($return) && $return) {
            $this->render('confirm', array(
                'model' => $model,
            ));
        } else {
            $this->returnMessage('没有找到相关的数据');
        }
    }
    
    /**
     * 获取打印小票 获取小票的时候 取消抽奖
     * @param integer $id
     */
    public function actionPrint($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            'exchange_status' => \Record::RECORD_EXCHANGE_STATUS_NO,        //未兑换
            'receive_type' => \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE,                    //到店兑换
            'status' => \Record::_STATUS_NORMAL,                                                  //中奖记录
            'print_status' => \Record::RECORD_PRINT_STATUS_NO,                        //未打印
            'pad_id' => \Yii::app()->user->padId,
            'store_id' => \Yii::app()->user->id,
        ));
        $this->_modelName = 'Record';
        $this->render('print', array(
            'model' => $this->loadModelByPk($id, $criteria),
        ));
    }
    
    /**
     * 打印成功
     * @param unknown $id
     */
    public function actionSuccess($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            'exchange_status' => \Record::RECORD_EXCHANGE_STATUS_NO,        //未兑换
            'receive_type' => \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE,                    //到店兑换
            'status' => \Record::_STATUS_NORMAL,                                                  //中奖记录
            'print_status' => \Record::RECORD_PRINT_STATUS_NO,                        //未打印
            'pad_id' => \Yii::app()->user->padId,
            'store_id' => \Yii::app()->user->id,
        ));
        $this->_modelName = 'Record';
        $model = $this->loadModelByPk($id, $criteria);
        $model->print_status = \Record::RECORD_PRINT_STATUS_YES;
        $this->render('success', array(
            'result' => $model->save(false),
        ));
    }
}