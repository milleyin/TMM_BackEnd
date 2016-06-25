<?php
/**
 * 微信 发起支付方法
 * @author Changhai Zhan
 *
 */
class PaywxOrderAction extends CAction
{
	/**
	 * 执行的方法
	 * @param unknown $id	订单id
	 * @param string $page		支付成功页面
	 * @param string $error		支付成功页面
	 * @param number $type 支付类型
	 * @throws Exception
	 */
	public function run($id, $page='', $error='', $type=0)
	{
		if (! ( $type == 0 || $type == 1))
			die;
		require_once(Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Api.php');
		require_once(Yii::app()->basePath . '/extensions/Wxpay/unit/WxPay.JsApiPay.php');
		require_once(Yii::app()->basePath . '/extensions/Wxpay/unit/WxPay.AppApiPay.php');
		
		$criteria =new CDbCriteria;
		$criteria->with=array('Order_User');
		$criteria->addCondition('`t`.`order_price` > 0');												//订单价格大于零
		$criteria->addCondition('`t`.`pay_status`=:pay_status_not OR `t`.`pay_status`=:pay_status_paying');
		$criteria->params[':pay_status_not'] = Order::pay_status_not;						//没有支付
		$criteria->params[':pay_status_paying'] = Order::pay_status_paying;			//支付中
		$criteria->addCondition('`t`.`pay_type`=:pay_type_none OR `t`.`pay_type`=:pay_type_alipay');
		$criteria->params[':pay_type_none'] = Order::pay_type_none;					//没有支付
		$criteria->params[':pay_type_alipay'] = Order::pay_type_wxpay;					//微信支付
		//标准条件
		$criteria->addColumnCondition(array(
				'Order_User.status'=>User::status_suc,												//用户是安全
 				't.user_id'=>Yii::app()->api->id,															//用户的
				't.order_status'=>Order::order_status_store_yes,								//订单的状态
				't.status_go'=>Order::status_go_yes,													//是否出游
				't.centre_status'=>Order::centre_status_yes,										//是否可以支付
				't.status'=>Order::status_yes,																//有效的订单
		));
		$model = Order::model()->findByPk($id, $criteria);
		if ($model && isset($model->Order_User))
		{
			$return = array();
			if ($type == 0)
			{
				//①、获取用户openid
				$tools = new JsApiPay();
				//'oju7-ssw8xibwVuwOM9p7tp2S2_8';
				$openId = $tools->GetOpenid($id, $page, $error);
			}
			else
			{
				WxPayDataBase::$key = WxPayConfig::APP_KEY;
				//① 统一下单 APP
				$tools = new AppApiPay();
			}
			//开启事物
			$transaction = $model->dbConnection->beginTransaction();
			try
			{
				if (Order::model()->findByPk($model->id, $criteria))
				{
					//②、统一下单
					$input = new WxPayUnifiedOrder();
					//订单简要描述
					$input->SetBody('田觅觅订单：' . $model->order_no );
					//订单附加数据
					$input->SetAttach($model->order_no);
					//订单号
					$input->SetOut_trade_no($model->order_no);
					//订单总价
					$input->SetTotal_fee($model->order_price * 100);
					//$input->SetTotal_fee("1");
					//订单开始时间
					$input->SetTime_start(date("YmdHis"));
					//结束时间
					$input->SetTime_expire(date("YmdHis", time() + 600));
					//商品标记
					//$input->SetGoods_tag('test');
					//支付方式
					if ($type == 0)
					{
						//回调链接
						$input->SetNotify_url(Yii::app()->request->getHostInfo() . Yii::app()->createUrl('/api/callback/wxpay'));
						//设置交易类型
						$input->SetTrade_type("JSAPI");
						//设置$openId
						$input->SetOpenid($openId);
						$order = WxPayApi::unifiedOrder($input);
						$jsApiParameters = $tools->GetJsApiParameters($order);
						//支付数据
						$return['wxpay'] = $jsApiParameters;											
						$return['model'] = $model;
						$return['page'] = $page;
						$return['error'] = $error;
					}
					else
					{
						//回调链接
						$input->SetNotify_url(Yii::app()->request->getHostInfo() . Yii::app()->createUrl('/api/callback/wxpayapp'));
						//设置交易类型
						$input->SetTrade_type("APP");
						//同意下单
						$order = WxPayApiApp::unifiedOrder($input);
						$appApiParameters = $tools->GetAppApiParameters($order);
						//支付数据
						$return['wxpay'] = $appApiParameters;
						$return['page'] = $page;
						$return['error'] = $error;
					}
				}
				else
					throw new Exception("支付订单 没有找到订单");
				//更新为支付中…… 微信支付
				if ( !Order::pay_status_paying($model->id, Order::pay_type_wxpay))
					throw new Exception("支付订单 保存改变订单状态失败");
				$this->controller->log('微信支付订单', ManageLog::user, ManageLog::update);
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$return = array();
				$this->controller->error_log($e->getMessage(), ErrorLog::user, ErrorLog::update, ErrorLog::rollback, __METHOD__);
			}
			if (!empty($return))
			{
				if ($type == 0)
				{
					Yii::log($jsApiParameters, 'info', __METHOD__);
					$this->controller->renderPartial('paywx', $return);
				}
				else
				{
					Yii::log(json_encode($appApiParameters), 'info', __METHOD__);
					$this->controller->send($return);
				}
			}
			elseif ($type == 1)
				$this->controller->send_error(DATA_NULL);
		}
		else if ($type ==1)
			$this->controller->send_error(DATA_NULL);
		$this->controller->renderPartial('paywx', array('wxpay'=>'', 'model'=>'' ,'page'=>$page, 'error'=>$error));
	}
}