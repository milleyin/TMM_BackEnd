<?php
namespace app\callback\controllers;

use CallbackModulesController;

/**
 * 微信支付回调
 * @author Changhai Zhan
 *
 */
class WxpayController extends CallbackModulesController
{
    /**
     * 回调
     */
    public function actionIndex()
    {
        error_reporting(0);
        require_once (\Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Api.php');
        require_once (\Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Notify.php');
        require_once (\Yii::app()->basePath . '/extensions/Wxpay/PayNotifyCallBack.php');
        //微信支付回调
        $notify = new \PayNotifyCallBack();
        $notify->Handle(true);
    }
    
    /**
     * 回调成功的逻辑
     * 1、只给当天的抽奖机会
     *         //订单号
        $data['out_trade_no'] = $result['out_trade_no'];
        //微信订单号
        $data['trade_no'] = $result['transaction_id'];
        //支付价格
        $data['total_fee'] = $result['cash_fee'] / 100;
        //支付账户
        $data['buyer_email'] = $result['openid'];
     * @param unknown $result
     * @return boolean
     */
    public function success($result)
    {
        //测试
        $test = false;
        //查询订单是否有效
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'OrderFood_Order'
        );
        //条件
        $criteria->addCondition('`t`.`order_status`=:yes OR `t`.`order_status`=:no');
        $criteria->params[':no'] = \OrderFood::ORDER_ORDER_STATUS_NO;      //未支付
        $criteria->params[':yes'] = \OrderFood::ORDER_ORDER_STATUS_YES;     //已支付
        $criteria->addColumnCondition(array(
            'OrderFood_Order.order_no' => $result['out_trade_no'],                      //订单号
        ));
        $this->_modelName = 'OrderFood';
        $model = $this->loadModel($criteria);
        //开启事物
        $transaction = $model->dbConnection->beginTransaction();
        try
        {
            $model = \OrderFood::model()->findByPk($model->id, $criteria);
            if ( !$model) {
                throw new \Exception("回调订单 没有找到订单");
            }
            if ($test) {
                $cash_fee = $model->OrderFood_Order->money;                         //支付金额相等
            } else {
                $cash_fee = $result['cash_fee'];
            }
            if ($model->OrderFood_Order->money != $cash_fee) {
                throw new \Exception("回调订单 支付金额不相等");
            }
            if ($model->order_status == \OrderFood::ORDER_ORDER_STATUS_YES) {
                //成功 获取抽奖机会
                $return = true;
            } else {
                //已支付
                $model->order_status = \OrderFood::ORDER_ORDER_STATUS_YES;
                //支付金额
                $model->OrderFood_Order->trade_money = $cash_fee;
                //支付微信订单号
                $model->OrderFood_Order->trade_no = $result['transaction_id'];
                //支付账号
                $model->OrderFood_Order->trade_id = $result['openid'];
                $modelUser = \User::model()->find('openid=:openid', array(':openid' => $result['openid']));
                if ($modelUser) {
                    $trade_name = $modelUser->name;
                } else {
                    $trade_name = $result['openid'];
                }
                //支付昵称
                $model->OrderFood_Order->trade_name = $trade_name;
                //微信支付
                $model->OrderFood_Order->trade_type = \Order::ORDER_TRADE_TYPE_WX;
                //支付时间
                $model->OrderFood_Order->trade_time = time();
                //已支付
                $model->OrderFood_Order->pay_status = \Order::ORDER_PAY_STATUS_YES;
                if ( !$model->save(false)) {
                    throw new \Exception("回调订单 保存抢菜订单表失败");
                }
                if ( !$model->OrderFood_Order->save(false)) {
                    throw new \Exception("回调订单 保存订单主表失败");
                }
                //付款成功 添加抽奖机会
                $criteria = new \CDbCriteria;
                $criteria->addColumnCondition(array(
                    'pad_id' => $model->pad_id,
                    'type' => \Config::Config_TYPE_PAY,
                ));
                $modelConfig = \Config::model()->find($criteria);
                if ( !$modelConfig) {
                    throw new \Exception("回调订单 没有找到奖品配置");
                }
                $data = array(
                    'user_id' => $model->user_id,
                    'store_id' => $model->store_id,
                    'pad_id' => $model->pad_id,
                    'config_id' => $modelConfig->id,
                    'type' => \Config::Config_TYPE_PAY,
                    'count' => $modelConfig->number,
                    'number' => $modelConfig->number,
                    'date_time' => strtotime(date('Y-m-d', time())),
                    'status' => \Chance::_STATUS_NORMAL,
                );
                if ( (!!$modelChance = \Chance::model()->createChance($data, \Config::Config_TYPE_FREE)) &&
                    $modelChance->updateChance($modelChance, $model->pad_id)
                ) {
                    //成功 获取抽奖机会
                    $return = true;
                } else {
                    throw new \Exception("回调订单 获取机会失败");
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::log($e->getMessage(), 'error', __METHOD__);
        }
        if (isset($return) && $result) {
            return true;
        }
        return false;
    }
}