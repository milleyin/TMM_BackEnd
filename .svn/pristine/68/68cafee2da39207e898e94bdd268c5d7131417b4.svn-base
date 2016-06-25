<?php

class CallbackController extends ApiController
{
	/**
	 * 支付回调接口
	 */
	public function actionAlipay()
	{
		//self::globalErrorRecord('Alipay',json_encode($_POST) ,'api');
		require_once(Yii::app()->basePath."/extensions/alipay/alipay.config.php");
		require_once(Yii::app()->basePath."/extensions/alipay/lib/alipay_notify.class.php");

		if(isset($_POST['sign']))
		{
			//计算得出通知验证结果
			$alipayNotify = new AlipayNotify($alipay_config);
			$verify_result = $alipayNotify->verifyNotify();

			if($verify_result) {//验证成功
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//请在这里加上商户的业务逻辑程序代
			
				//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
				//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
							
				if($_POST['trade_status'] == 'TRADE_FINISHED') {
					//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//如果有做过处理，不执行商户的业务程序
			
					//注意：
					//该种交易状态只在两种情况下出现
					//1、开通了普通即时到账，买家付款成功后。
					//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
	
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
					$return=$this->notify_data($_POST); //没有处理过
				}
				else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
					$return=$this->notify_data($_POST); //处理过
					//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//如果有做过处理，不执行商户的业务程序
					//注意：
					//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
			
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
				}
			
				//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
				//成功写入日志
				if(isset($return) && $return)
				{
					self::globalErrorRecord('AlipayNotifySuccess',json_encode($_POST) ,'api');
					echo "success";		//请不要修改或删除
				}
				else 
				{
					//失败写入日志
					self::globalErrorRecord('AlipayNotifyFail',json_encode($_POST) ,'api');
					//验证失败
					echo "fail";
				}
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
			else {
				//失败写入日志
				self::globalErrorRecord('AlipayNotifyFail',json_encode($_POST) ,'api');
				//验证失败
				echo "fail";
			
				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			}
		}else 
			echo "fail";
	}

	public function actionTest($order_no,$toal_fee='')
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'order_no'=>$order_no,
			'id'=>$order_no,
		),'OR');
		$order=Order::model()->find($criteria);
		if($order)
		{
			$data = array(
				'out_trade_no'=>$order->order_no,
				'trade_no'=>rand(100000,99999999),
				'total_fee'=>$order->order_price,
				'buyer_email'=>rand(100000,99999999).'@qq.com',
			);
			$return=$this->notify_data($data);
			if(isset($return) && $return)
				echo 'ok';
			else 
				echo 'no';
		}else 
		 	echo $order_no;
	}

	/**
	 * 处理订单
	 * @param unknown $data	post 数据
	 * @param unknown $online 在线支付 钱包支付
	 */
	public function notify_data($data,$online=true)
	{	
		//商户订单号====系统唯一订单号
		$out_trade_no = $data['out_trade_no'];
		//查询订单信息 查看订单是否存在 是否有效
		
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'order_no'=>$out_trade_no,																		//订单号
			'status'=>Order::status_yes,																		//有效订单
			'status_go'=>Order::status_go_yes,															//出游状态 确认出游
		));
		//订单状态
		$criteria->addCondition('order_status=:store_yes OR order_status=:user_pay');
		$criteria->params[':store_yes']=Order::order_status_store_yes;					//待付款
		$criteria->params[':user_pay']=Order::order_status_user_pay;						//已支付
		//订单类型
		$criteria->addCondition('order_type=:dot OR order_type=:thrand OR order_type=:actives_tour OR order_type=:full');
		$criteria->params[':dot'] = Order::order_type_dot;										//多个点
		$criteria->params[':thrand'] = Order::order_type_thrand;								//一条线
		$criteria->params[':actives_tour'] = Order::order_type_actives_tour;			//活动（旅游）
		$criteria->params[':full'] = Order::order_type_actives_tour_full;					//活动（旅游）
		//支付状态
		$criteria->addCondition('pay_status=:pay_status_not OR pay_status=:pay_status_paying OR pay_status=:pay_status_yes');
		$criteria->params[':pay_status_not'] = Order::pay_status_not;						//未支付
		$criteria->params[':pay_status_paying'] = Order::pay_status_paying;			//支付中
		$criteria->params[':pay_status_yes'] = Order::pay_status_yes;						//已支付
		//查询订单
		$model = Order::model()->find($criteria);
		if(! $model)																										//订单不存
			return false;
		//开启事物
		$transaction = $model->dbConnection->beginTransaction();
		try{
			//查询订单是否存在
			$order = Order::model()->find($criteria);
			if(! $order)
				throw new Exception("支付回调订单不存在 ".$out_trade_no);
			if($order->order_status == Order::order_status_store_yes && 				//待付款
					($order->pay_status==Order::pay_status_not || 								//没有支付
						$order->pay_status==Order::pay_status_paying)							//支付中的
				)																												//订单状态 待支付
			{
				//支付的操作
				$return = $this->pay($order,$data,$criteria,$online);
				if($return)
					self::globalErrorRecord('AlipayNotify--success','回调信息====== 没处理 保存成功' ,'api');
				else
					throw new Exception("回调信息====== 没处理 保存失败");
			}
			elseif($order->order_status == Order::order_status_user_pay && $order->pay_status==Order::pay_status_yes)
			{			
				//已经支付的操作
				$return = true;
				self::globalErrorRecord('AlipayNotify--success','回调信息====== 已处理 保存成功' ,'api');
			}
			else
			{
				$return = false; //失败
				self::globalErrorRecord('AlipayNotify--success','回调信息======订单状态异常'.$order->order_no ,'api');
				throw new Exception("回调信息======订单状态异常");
			}
			$transaction->commit();
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			self::globalErrorRecord('AlipayNotify--success','回调订单事物回滚：'.$e->getMessage(),'api');
		}
		return $return;
	}
	
	/**
	 * 支付的操作
	 * @param object $model 订单对象
	 * @param array $data 支付数据
	 * @param boolean $online 是否在线支付 
	 */
	public function pay($model,$data,$criteria,$online)
	{
		//未支付状态
		if ($model->pay_status==Order::pay_status_not || $model->pay_status==Order::pay_status_paying)
		{
			//支付宝交易号====支付宝唯一订单号
			$trade_no = $data['trade_no'];
			$attributes=array(
				'order_status'=>Order::order_status_user_pay,			//订单状态 已付款
				'pay_status'=>Order::pay_status_yes,						//支付状态 已支付
				'trade_no'=>$trade_no,												//支付宝交易号
				'pay_price'=>$data['total_fee'],									//支付金额
				'price'=>	$data['total_fee'],										//实际支付金额
				'trade_name'=>$data['buyer_email'],							//支付人的账号
				'pay_type'=>Order::pay_type_alipay,							//支付类型 支付宝
				'pay_time'=>time(),													//支付时间
				'centre_status'=>Order::centre_status_not,				//不可支付
			);
			if (! $online)
				$attributes['pay_type'] = Order::pay_type_account;	//钱包支付
			$return = Order::model()->updateByPk($model->id, $attributes,$criteria);	//更新
			if ($return)
			{
				if ($model->order_type == Order::order_type_dot)										//点
				{
					//更新项目的消费码
					$valid = OrderItems::model()->updateAll(array('is_barcode'=>OrderItems::is_barcode_valid),'order_id=:order_id',array(':order_id'=>$model->id));
					$valid = $valid ? '成功' : '失败';
					self::globalErrorRecord('AlipayNotify--success','回调信息====将扫描二维码的值改为可用'.$valid ,'api');
					
					// 项目免费====消费码设为无效
					$items_is_barcode = OrderItems::exclude_items_code($model->id);
					if (! empty($items_is_barcode))
					{
						if (OrderItems::set_is_barcode($items_is_barcode))
							self::globalErrorRecord('AlipayNotify--success','回调信息保存成功 项目免费====消费码设为无效码 OrderItems ID:'.implode(',', $items_is_barcode) ,'api');
					}
					//在线支付
					if ($online)
					{
						//交易记录 支付宝在线支付
						$user = User::model()->findByPk($model->user_id);
						$account_log = Account::moneyRecordAlipayRmb(
								$data['total_fee'],
								array('account_id'=>$model->user_id,'account_type'=>Account::user),
								array('info_id'=>$model->id,'name'=>'支付宝在线支付','address'=>'','info'=>
									$user->nickname.'（'.$user->phone.'）'."<br>\n".
									'觅境订单 '.$model->order_no."<br>\n".
									'支付金额 '.$data['total_fee']."<br>\n".
									'出游日期 '.date('Y-m-d',$model->go_time)."<br>\n".
									date('Y-m-d H:i:s').' 支付宝在线支付成功（手机支付）'
								)
						);
						if (! $account_log)
							throw new Exception("添加交易记录（点 在线支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
					}
					else
					{
						//交易记录 钱包支付金额
						$user = User::model()->findByPk($model->user_id);
						$account_log = Account::moneyDeductOrderPayRmb(
								$data['total_fee'],
								array('account_id'=>$model->user_id,'account_type'=>Account::user),
								array('info_id'=>$model->id,'name'=>'钱包支付','address'=>'','info'=>
									$user->nickname.'（'.$user->phone.'）'."<br>\n".
									'觅境订单 '.$model->order_no."<br>\n".
									'支付金额 '.$data['total_fee']."<br>\n".
									'出游日期 '.date('Y-m-d',$model->go_time)."<br>\n".
									date('Y-m-d H:i:s').' 钱包支付成功'
								)
						);
						if (! $account_log)
							throw new Exception("添加交易记录（点 钱包支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
					}
				}
				else if ($model->order_type == Order::order_type_thrand)							//线
				{
					//更新项目的消费码
					$valid=OrderItems::model()->updateAll(array('is_barcode'=>OrderItems::is_barcode_valid),'order_id=:order_id',array(':order_id'=>$model->id));
					$valid = $valid ? '成功' : '失败';
					self::globalErrorRecord('AlipayNotify--success','回调信息====将扫描二维码的值改为可用'.$valid ,'api');
					//无效码
					$not_is_barcode = OrderItemsFare::exclude_items_code($model->id);
					if(! empty($not_is_barcode))
					{
						if ( OrderItems::set_is_barcode($not_is_barcode))
							self::globalErrorRecord('AlipayNotify--success','回调信息保存成功 无效码 OrderItems ID:'.implode(',', $not_is_barcode) ,'api');		
					}
					// 项目免费====消费码设为无效
					$items_is_barcode = OrderItems::exclude_items_code($model->id);
					if (! empty($items_is_barcode))
					{
						if (OrderItems::set_is_barcode($items_is_barcode))
							self::globalErrorRecord('AlipayNotify--success','回调信息保存成功 项目免费====消费码设为无效码 OrderItems ID:'.implode(',', $items_is_barcode) ,'api');
					}
					// 在线支付
					if ($online)
					{
						//交易记录 支付宝在线支付
						$user = User::model()->findByPk($model->user_id);
						$account_log = Account::moneyRecordAlipayRmb(
								$data['total_fee'],
								array('account_id'=>$model->user_id,'account_type'=>Account::user),
								array('info_id'=>$model->id,'name'=>'支付宝在线支付','address'=>'','info'=>
										$user->nickname.'（'.$user->phone.'）'."<br>\n".
										'觅境订单 '.$model->order_no."<br>\n ".
										'支付金额 '.$data['total_fee']."<br>\n ".
										'出游日期 '.date('Y-m-d',$model->go_time)."<br>\n ".
										date('Y-m-d H:i:s').' 支付宝在线支付成功（手机支付）'
								)
						);
						if(! $account_log)
							throw new Exception("添加交易记录（线路 在线支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
					}
					else
					{
						//交易记录 钱包支付金额
						$user = User::model()->findByPk($model->user_id);
						$account_log = Account::moneyDeductOrderPayRmb(
								$data['total_fee'],
								array('account_id'=>$model->user_id,'account_type'=>Account::user),
								array('info_id'=>$model->id,'name'=>'钱包支付','address'=>'','info'=>
										$user->nickname.'（'.$user->phone.'）'."<br>\n".
										'觅境订单 '.$model->order_no."<br>\n".
										'支付金额 '.$data['total_fee']."<br>\n".
										'出游日期 '.date('Y-m-d',$model->go_time)."<br>\n".
										date('Y-m-d H:i:s').' 钱包支付成功'
								)
						);
						if(! $account_log)
							throw new Exception("添加交易记录（线 钱包支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
					}
				}
				else if ($model->order_type==Order::order_type_actives_tour)
				{
					$criteria_tour=new CDbCriteria;
					$criteria_tour->with=array(
						'OrderActives_Actives'
					);
					$actives = OrderActives::model()->findByPk($model->order_organizer_id,$criteria_tour);
					if ($actives && isset($actives->OrderActives_Actives) && $actives->OrderActives_Actives)
					{
						//支付订单统计数量
						if (! Actives::actives_order_count($actives->actives_id))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)订单 支付订单统计数量 保存失败' ,'api');
							return false;
						}
						//支付订单数量
						if (! OrderActives::actives_pay_count($actives->id))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)订单 支付订单数量 保存失败' ,'api');
							return false;
						}
						//实际出游数量
						if (! OrderActives::actives_go_count($actives->id,$model->user_go_count))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)订单 实际出游数量 保存失败' ,'api');
							return false;
						}
						//支付总额总计
						if (! OrderActives::actives_price_count($actives->id,$data['total_fee']))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)订单 支付总额总计 保存失败' ,'api');
							return false;
						}
						//实际支付总额
						if (! OrderActives::actives_total($actives->id,$data['total_fee']))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)订单 实际支付总额保存失败' ,'api');
							return false;
						}
						//添加购买的数量 价格 
						if (! Order::update_actives_tour_itmes_fare($model->id))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)订单 添加购买的数量 价格保存失败' ,'api');
							return false;
						}
						//消费码有效的
						$valid = OrderItems::model()->updateAll(array('is_barcode'=>OrderItems::is_barcode_valid),'order_organizer_id=:order_organizer_id',array(':order_organizer_id'=>$actives->id));
						$valid = $valid ? '成功' : '失败';
						self::globalErrorRecord('AlipayNotify--success','回调信息====将扫描二维码的值改为可用'.$valid ,'api');

						//无效码
						$not_is_barcode = OrderItemsFare::exclude_items_code(0,$actives->id);
						if(! empty($not_is_barcode))
						{
							if ( OrderItems::set_is_barcode($not_is_barcode))
								self::globalErrorRecord('AlipayNotify--success','回调信息保存成功 无效码 OrderItems ID:'.implode(',', $not_is_barcode) ,'api');
						}
						// 项目免费====消费码设为无效
						$items_is_barcode = OrderItems::exclude_items_code(0,$actives->id);
						if(! empty($items_is_barcode))
						{
							if(OrderItems::set_is_barcode($items_is_barcode))
								self::globalErrorRecord('AlipayNotify--success','回调信息保存成功 项目免费====消费码设为无效码 OrderItems ID:'.implode(',', $items_is_barcode) ,'api');
						}
						//出游时间 继承 活动的出游时间
						$model->go_time = $actives->OrderActives_Actives->go_time;
						if ($online)
						{
							$user = User::model()->findByPk($model->user_id);
							//交易记录 支付宝在线支付
							$account_log = Account::moneyRecordAlipayRmb(
									$data['total_fee'],
									array('account_id'=>$model->user_id,'account_type'=>Account::user),
									array('info_id'=>$model->id,'name'=>'支付宝在线支付','address'=>'','info'=>
											$user->nickname.'（'.$user->phone.'）'."\n <br>".
											'觅趣订单 '.$model->order_no."\n <br>".
											'支付金额 '.$data['total_fee']."\n <br>".
											'出游日期 '.date('Y-m-d',$model->go_time)."\n <br>".
											date('Y-m-d H:i:s').' 支付宝在线支付成功（手机支付）'
									)
							);
							if (! $account_log)
								throw new Exception("添加交易记录（活动（旅游） 在线支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
						}
						else
						{
							//交易记录 钱包支付金额
							$user = User::model()->findByPk($model->user_id);
							$account_log = Account::moneyDeductOrderPayRmb(
									$data['total_fee'],
									array('account_id'=>$model->user_id,'account_type'=>Account::user),
									array('info_id'=>$model->id,'name'=>'钱包支付','address'=>'','info'=>
											$user->nickname.'（'.$user->phone.'）'."\n <br>".
											'觅趣订单 '.$model->order_no."\n <br>".
											'支付金额 '.$data['total_fee']."\n <br>".
											'出游日期 '.date('Y-m-d',$model->go_time)."\n <br>".
											date('Y-m-d H:i:s').' 钱包支付成功'
									)
							);
							if (! $account_log)
								throw new Exception("添加交易记录（活动（旅游） 钱包支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
						}
					}
					else
					{
						self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)订单 没有找到活动或活动总订单' ,'api');
						return false;					
					}
				}
				else if ($model->order_type == Order::order_type_actives_tour_full)
				{
					$criteria_tour=new CDbCriteria;
					$criteria_tour->with=array(
							'OrderActives_Actives'
					);
					$actives = OrderActives::model()->findByPk($model->order_organizer_id,$criteria_tour);
					if ($actives && isset($actives->OrderActives_Actives) && $actives->OrderActives_Actives)
					{
						//支付订单统计数量
						if (! Actives::actives_order_count($actives->actives_id))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)代付订单 支付订单统计数量 保存失败' ,'api');
							return false;
						}
						//支付订单数量
						if (! OrderActives::actives_pay_count($actives->id))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)代付订单 支付订单数量 保存失败' ,'api');
							return false;
						}
						//实际出游数量
						if (! OrderActives::actives_go_count($actives->id,$model->user_go_count))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)代付订单 实际出游数量 保存失败' ,'api');
							return false;
						}
						//支付总额总计
						if (! OrderActives::actives_price_count($actives->id,$data['total_fee']))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)代付订单 支付总额总计 保存失败' ,'api');
							return false;
						}
						//实际支付总额
						if (! OrderActives::actives_total($actives->id,$data['total_fee']))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)代付订单 实际支付总额保存失败' ,'api');
							return false;
						}
						//添加购买的数量 价格
						if (! Order::update_actives_tour_itmes_fare($model->id))
						{
							self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)代付订单 添加购买的数量 价格保存失败' ,'api');
							return false;
						}
						//消费码有效的
						$valid = OrderItems::model()->updateAll(array('is_barcode'=>OrderItems::is_barcode_valid),'order_organizer_id=:order_organizer_id',array(':order_organizer_id'=>$actives->id));
						$valid = $valid ? '成功' : '失败';
						self::globalErrorRecord('AlipayNotify--success','回调信息====将扫描二维码的值改为可用'.$valid ,'api');
					
						//无效码
						$not_is_barcode = OrderItemsFare::exclude_items_code(0,$actives->id);
						if(! empty($not_is_barcode))
						{
							if ( OrderItems::set_is_barcode($not_is_barcode))
								self::globalErrorRecord('AlipayNotify--success','回调信息保存成功 无效码 OrderItems ID:'.implode(',', $not_is_barcode) ,'api');
						}
						// 项目免费====消费码设为无效
						$items_is_barcode = OrderItems::exclude_items_code(0,$actives->id);
						if(! empty($items_is_barcode))
						{
							if( OrderItems::set_is_barcode($items_is_barcode))
								self::globalErrorRecord('AlipayNotify--success','回调信息保存成功 项目免费====消费码设为无效码 OrderItems ID:'.implode(',', $items_is_barcode) ,'api');
						}
						//出游时间 继承 活动的出游时间
						$model->go_time = $actives->OrderActives_Actives->go_time;
						if ($online)
						{
							$user = User::model()->findByPk($model->user_id);
							//交易记录 支付宝在线支付
							$account_log = Account::moneyRecordAlipayRmb(
									$data['total_fee'],
									array('account_id'=>$model->user_id,'account_type'=>Account::user),
									array('info_id'=>$model->id,'name'=>'支付宝在线支付','address'=>'','info'=>
											$user->nickname.'（'.$user->phone.'）'."\n <br>".
											'觅趣（代付）订单 '.$model->order_no."\n <br>".
											'支付金额 '.$data['total_fee']."\n <br>".
											'出游日期 '.date('Y-m-d',$model->go_time)."\n <br>".
											date('Y-m-d H:i:s').' 支付宝在线支付成功（手机支付）'
									)
							);
							if (! $account_log)
								throw new Exception("添加交易记录（活动（旅游）代付 在线支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
						}
						else
						{
							//交易记录 钱包支付金额
							$user = User::model()->findByPk($model->user_id);
							$account_log = Account::moneyDeductOrderPayRmb(
									$data['total_fee'],
									array('account_id'=>$model->user_id,'account_type'=>Account::user),
									array('info_id'=>$model->id,'name'=>'钱包支付','address'=>'','info'=>
											$user->nickname.'（'.$user->phone.'）'."\n <br>".
											'觅趣（代付）订单 '.$model->order_no."\n <br>".
											'支付金额 '.$data['total_fee']."\n <br>".
											'出游日期 '.date('Y-m-d',$model->go_time)."\n <br>".
											date('Y-m-d H:i:s').' 钱包支付成功'
									)
							);
							if (! $account_log)
								throw new Exception("添加交易记录（活动（旅游）代付 钱包支付错误）".$model->order_no.' json ['.json_encode(array(Account::$create_error,AccountLog::$create_error)).']');
						}
					}
					else
					{
						self::globalErrorRecord('AlipayNotify--fail','回调信息====活动(旅游)（代付）订单 没有找到活动或活动总订单' ,'api');
						return false;
					}
				}
				else
				{
					self::globalErrorRecord('AlipayNotify--fail','回调信息====没有找到订单' ,'api');
					return false;			
				}

				self::globalErrorRecord('AlipayNotify--success','回调信息====保存成功' ,'api');			
				
				//发送短信
				$model_sms=new SmsLoginForm;
				$time    = date('Y-m-d',$model->go_time);
				//支付，回调成功，发短信通过用户
				$sms_status = $model_sms->notify_pay_sms($model->user_id,$model->order_no,$time);
				$sms_content = $sms_status ? '成功' : '失败';
				self::globalErrorRecord('AlipayNotify--success','回调信息====通知用户'.$sms_content ,'api');
				return true;
			}else{
				//发邮件  ====通知管理员  回调成功，处理失败
				self::globalErrorRecord('AlipayNotify--fail','回调信息====保存失败' ,'api');
				return false;
			}
		}else 
			return true;
	}
	
	/**
	 * 钱包支付
	 * @param unknown $id
	 */
	public function actionAccount($id)
	{	
		if(isset($_POST['Password']))
		{			
			$result = Password::validatePwd( 
					Yii::app()->api->id,
					Password::role_type_user,
					Password::password_type_pay,
					$_POST['Password']
			);
			$return = Password::getValidatePwdResult($result);
			if(isset($result['value']) && $result['value'] == Password::password_pass)
				$this->account($id,$return);
			else
				$this->send($return);
		}
		
		$this->send_csrf();
	}
	
	/**
	 * 钱包支付执行函数
	 * @param unknown $id
	 * @param unknown $return
	 */
	public function account($id,$return)
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
				'order_no'=>$id,
				'id'=>$id,
		),'OR');
		$criteria->addColumnCondition(array(
				'user_id'=>Yii::app()->api->id,
				'centre_status'=>Order::centre_status_yes,  //可支付
				'pay_status'=>Order::pay_status_not,           //未支付
				'status'=>Order::status_yes							//有效订单
		));
		$order = Order::model()->find($criteria);
		if($order)
		{
			//获取用户钱包
			$user_account = Account::getAccount(Yii::app()->api->id,Account::user,Account::money_type_rmb);
			if($user_account && $user_account->status == Account::status_normal && bccomp($user_account->money,$order->order_price,2) >=0)
			{
				$data = array(
						'out_trade_no'=>$order->order_no,
						'trade_no'=>$order->order_no,
						'total_fee'=>$order->order_price,
						'buyer_email'=>Yii::app()->api->id,
				);
				if($this->notify_data($data,false))
				{
					$return['status']=array(
							'name'=>'支付成功',
							'value'=>1,
							'info'=>'支付成功',
							'type'=>array(
								'name'=>Order::$_order_type[$order->order_type],
								'value'=>$order->order_type,
								'link'=> Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/view',array('id'=>$order->id)),
							),
					);
				}
				else
				{
					$return['status']=array(
							'name'=>'支付失败',
							'value'=>-1,
							'info'=>'余额不足或订单不存在',
					);
				}
			}else
				$return['status']=array(
						'name'=>'支付失败',
						'value'=>-1,
						'info'=>'余额不足',
				);
		}
		else
		{
			$return['status']=array(
					'name'=>'支付失败',
					'value'=>-1,
					'info'=>'支付订单不存在',
			);
		}
		$this->send($return);
	}
}