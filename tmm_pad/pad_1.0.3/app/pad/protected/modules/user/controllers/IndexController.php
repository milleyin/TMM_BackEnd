<?php
namespace app\user\controllers;

use UserModulesController;

/**
 * Class IndexController
 * @package app\user\controllers
 *
 * @author Moore Mo
 */
class IndexController extends UserModulesController
{
    /**
     * 当前操作模型
     * @var unknown
     */
    public $_modelName = 'Chance';
    /**
     * 7-27
     * 用户扫描二维码获取抽奖机会
     */
    public function actionIndex($id)
    {
        //展示屏 体验店 是否正常 展示屏禁用 不能获取抽奖机会等其他操作
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Pad_Store',
            'Pad_Config',
        );
        $criteria->addColumnCondition(array(
            '`t`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Pad_Store`.`status`'=>\Store::_STATUS_NORMAL,
        ));
        $this->_modelName = 'Pad';
        $model = $this->loadModelByPk($id, $criteria);
        //开启事物
        $transaction = $model->dbConnection->beginTransaction();
        try
        {
            $model = \Pad::model()->findByPk($id, $criteria);
            if ( !$model) {
                throw new \Exception('找不到展示屏');
            }
            //是否有抽奖机会 有继续抽奖
            if ( !!$modelChance = \Chance::model()->getChance($model->id, $model->Pad_Config->type)) {
                if ($modelChance->updateChance($modelChance, $model->id)) {
                    //成功 获取抽奖机会
                    $return = true;
                }
                //展示屏抽奖配置 正常 可以获取抽奖机会 免费的
            } elseif ($model->Pad_Config->status == \Config::_STATUS_NORMAL && $model->Pad_Config->type == \Config::Config_TYPE_FREE) {
                if ($model->Pad_Config->chance_number == -1 || ($model->Pad_Config->chance_number > \Chance::model()->countChance($model->id, \Config::Config_TYPE_FREE))) {
                    if ( !!$modelChance = \Chance::model()->createChance($model, \Config::Config_TYPE_FREE)) {
                        if ($modelChance->updateChance($modelChance, $model->id)) {
                            //成功 获取抽奖机会
                            $return = true;
                        }
                    }
                }
                //展示屏抽奖配置 正常 可以获取抽奖机会 付费的 创建订单 回调后在创建抽奖机会
            } elseif ($model->Pad_Config->status == \Config::_STATUS_NORMAL && $model->Pad_Config->type == \Config::Config_TYPE_PAY) {
                if ($model->Pad_Config->chance_number == -1 || ($model->Pad_Config->chance_number > \Chance::model()->countChance($model->id, \Config::Config_TYPE_PAY))) {
                    //查询是否存在 已经下单的订单 8分钟之内的
                    if ( !!$modelOrderFood = \OrderFood::model()->getValidOrder($model->Pad_Config->money, $model->Pad_Config->pad_id, 60 * 8)) {
                        $modelOrder = $modelOrderFood->OrderFood_Order;
                        $order = true;
                    } else {
                        //创建订单
                        $dataOrder = array(
                            'type' => \Order::ORDER_TYPE_FOOD,
                            'money' => $model->Pad_Config->money,
                        );
                        if ( !!$modelOrder = \Order::model()->createOrder($dataOrder)) {
                            $dataOrderFood = array(
                                'store_id' => $model->Pad_Config->store_id,
                                'pad_id' => $model->Pad_Config->pad_id,
                                'money' =>  $model->Pad_Config->money,
                            );
                            if ( !!$modelOrderFood = \OrderFood::model()->createOrderFood($dataOrderFood, $modelOrder->id)) {
                                $order = true;
                            } else {
                                throw new \Exception("创建抢菜订单失败");
                            }
                        } else {
                            throw new \Exception("创建主订单失败");
                        }
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::log($e->getMessage(), 'error', __METHOD__);
        }
        if (isset($order) && $order) {
            //订单详情页面
            $this->render('order', array(
                'modelOrder' => $modelOrder,
                'modelOrderFood' => $modelOrderFood
            ));
        } elseif (isset($return) && $return) {
            $this->render('index', array('model' => $modelChance));
        } else {
            $this->returnMessage('没有找到相关相数据');
        }
    }
    
    /**
     * 支付订单
     * @param unknown $modelOrder
     * @param unknown $modelOrderFood
     * @throws Exception
     */
    public function actionPay($id)
    {
        //测试
        $test = false;
        require_once(\Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Api.php');
        require_once(\Yii::app()->basePath . '/extensions/Wxpay/unit/WxPay.JsApiPay.php');
        //查询订单是否有效
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'OrderFood_Order'
        );
        $criteria->addCondition('`t`.`add_time` >= :time');                                //支付订单未失效
        $criteria->params[':time'] = time() - 6 * 95;                                           // 9分半钟
        $criteria->addCondition('`t`.`money` > 0');                                            //订单价格大于零
        //标准条件
        $criteria->addColumnCondition(array(
            '`t`.`user_id`'=>\Yii::app()->user->id,                                                  //用户的
            '`t`.`order_status`' => \OrderFood::ORDER_ORDER_STATUS_NO,     //未支付
        ));
        $this->_modelName = 'OrderFood';
        $model = $this->loadModelByPk($id, $criteria);
        //开启事物
        $transaction = $model->dbConnection->beginTransaction();
        try
        {
            $model = \OrderFood::model()->findByPk($id, $criteria);
            if ( !$model) {
                throw new \Exception("支付订单 没有找到订单");
            }
            //展示屏 有效 付费抽奖
            $criteria = new \CDbCriteria;
            $criteria->with = array(
                'Pad_Store',
                'Pad_Config',
            );
            $criteria->addColumnCondition(array(
                '`t`.`status`'=>\Pad::_STATUS_NORMAL,
                '`Pad_Store`.`status`'=>\Store::_STATUS_NORMAL,
                '`Pad_Config`.`type`' => \Config::Config_TYPE_PAY,                         //付费抽奖
            ));
            $modelPad = \Pad::model()->findByPk($model->pad_id, $criteria);
            if ( !$modelPad) {
                throw new \Exception("支付订单 没有找到展示屏");
            }
            //是否有抽奖机会 有继续抽奖
            if ( !!$modelChance = \Chance::model()->getChance($modelPad->id, \Config::Config_TYPE_PAY)) {
                if ($modelChance->updateChance($modelChance, $modelPad->id)) {
                    //成功 获取抽奖机会
                    $returnChance = true;
                }
               //展示屏抽奖配置 正常&机会数量&价格相等 可以支付
            } elseif (
                $modelPad->Pad_Config->status == \Config::_STATUS_NORMAL && 
                $modelPad->Pad_Config->money == $model->OrderFood_Order->money && 
                ($modelPad->Pad_Config->chance_number == -1 || ($modelPad->Pad_Config->chance_number > \Chance::model()->countChance($modelPad->id, \Config::Config_TYPE_PAY)))
            ) {
                //①、获取用户openid
                $tools = new \JsApiPay();
                //OpenId
                $openId = $this->module->openid;
                //②、统一下单
                $input = new \WxPayUnifiedOrder();
                //订单简要描述
                $input->SetBody('田觅觅“觅镜”付费抽奖');
                //订单附加数据
                $input->SetAttach($model->OrderFood_Order->order_no);
                //订单号
                $input->SetOut_trade_no($model->OrderFood_Order->order_no);
                //订单总价
                if ($test) {
                    $input->SetTotal_fee(1);
                } else {
                    $input->SetTotal_fee($model->OrderFood_Order->money);
                }
                //订单开始时间
                $input->SetTime_start(date("YmdHis"));
                //结束时间
                $input->SetTime_expire(date("YmdHis", time() + 60 * 11));
                //商品标记
                //$input->SetGoods_tag('test');
                //回调链接
                $input->SetNotify_url($this->createAbsoluteUrl('/callback/wxpay/index'));
                //设置交易类型
                $input->SetTrade_type("JSAPI");
                //设置$openId
                $input->SetOpenid($openId);
                $order = \WxPayApi::unifiedOrder($input);
                //\Yii::log(var_export($order, true), 'info', __METHOD__);
                $jsApiParameters = $tools->GetJsApiParameters($order);
                //支付数据
                $return['wxpay'] = $jsApiParameters;
                $return['model'] = $model;
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::log($e->getMessage(), 'error', __METHOD__);
        }
        if (isset($returnChance) && $returnChance) {
            $this->redirect(array('index', 'id' => $model->pad_id));
        } elseif (isset($return) && $return) {
            //\Yii::log(var_export($return, true), 'info', __METHOD__);
            $this->render('pay', $return);
        } elseif (isset($model) && $model) {
            $this->redirect(array('index', 'id' => $model->pad_id));
        } else {
            $this->returnMessage('找不到相关数据');
        }
    }
    
    /**
     * 支付成功 查看抽奖机会次数
     * @param integer $id Order/OrderFood id
     */
    public function actionSuccess($id)
    {
        //查看订单
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'OrderFood_Order'
        );
        $criteria->addColumnCondition(array(
            '`t`.`order_status`' => \OrderFood::ORDER_ORDER_STATUS_YES, //已支付
        ));
        $this->_modelName = 'OrderFood';
        $modelOrder = $this->loadModelByPk($id, $criteria);
        //查看机会
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            '`user_id`' => \Yii::app()->user->id,
            '`pad_id`' => $modelOrder->pad_id,
            '`date_time`' => strtotime(date('Y-m-d', time())),
            '`type`' => \Config::Config_TYPE_PAY,
            'status' => \Config::_STATUS_NORMAL
        ));
        $this->_modelName = 'Chance';
        $this->render('success', array(
            'model' => $this->loadModel($criteria),
        ));
    }
}