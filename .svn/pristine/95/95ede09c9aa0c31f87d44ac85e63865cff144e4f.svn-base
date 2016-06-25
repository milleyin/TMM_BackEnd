<?php
namespace Sakura\Controller;
use Think\Controller;
use Think\Model;
use Common\Library\Util\SMS;
use Common\Model\SmsModel;

/**
 * 订单控制器
 * Class OrderController
 * @package Sakura\Controller
 *
 * @author Moore Mo
 */
class OrderController extends MainController {
	/**
	 * 订单支付
	 * @param $id
	 */
	public function pay($id){
		import('Common.Wxpay.lib.WxPay#Api', APP_PATH, '.php');
		import('Common.Wxpay.unit.WxPay#JsApiPay', APP_PATH, '.php');

		$userModel = D('User');
		$orderModel = D('Order');
		// 查询用户信息
		$orderData = $orderModel->where(array('id' => $id, 'status'=>1, 'order_status'=>$orderModel::order_status_pay_not))->find();
		if ($orderData)
		{
			$userData = $userModel->where(array('id' => $orderData['user_id'], 'status'=>1))->find();
			if ($userData)
			{
				//①、获取用户openid
				$tools = new \JsApiPay();
				$openId = $tools->GetOpenid($id);

				if ($userData['openid'] =='')
				{
					$userData['openid'] = $openId;
					$userModel->save($userData);
				}
				//②、统一下单
				$input = new \WxPayUnifiedOrder();
				//订单简要描述
				$input->SetBody($userData['mobile'] . ' ' . $orderModel::$__type[$orderData['type']] . ' * ' . $orderData['number'] . '总计：' . $orderData['total_price']);
				//订单附加数据
				$input->SetAttach($orderModel::getAttach($orderData['id'],$userData['id']));
				//订单号
				$input->SetOut_trade_no($orderData['order_no']);
				//订单总价
				$input->SetTotal_fee($orderData['total_price']*100);
				//订单开始时间
				$input->SetTime_start(date("YmdHis"));
				//结束时间
				$input->SetTime_expire(date("YmdHis", time() + 600));
				//商品标记
				//$input->SetGoods_tag('test');
				//回调链接
				$input->SetNotify_url(U('Notify/wxpay', '', true, true));
				$input->SetTrade_type("JSAPI");
				$input->SetOpenid($openId);
				$order = \WxPayApi::unifiedOrder($input);
				$jsApiParameters = $tools->GetJsApiParameters($order);
				$this->assign('jsApiParameters', $jsApiParameters);
				$this->assign('orderData', array('codeUrl'=>$orderData['code_url'], 'id'=>$id));
				$this->display();
			}
		}
	}

    /**
     * 增加订单
     */
    public function add() 
    {
        if (IS_POST) 
        {
			// 取出openid
			$openid = session('openid');
        	$error = array();
        	//获取数据
        	$orderData = array(
        			'mobile' => I('post.mobile', '', 'trim'),
        			'type' => I('post.type', '', 'trim'),
        			'number' => I('post.number', '', 'trim'),
        			'code' => I('post.code', '', 'trim'),
        			'total_price' => I('post.total_price', '', 'trim'),
        	);
			//数据过滤
        	$orderModel = D('Order');
        	$userModel = D('User');
        	if (! (in_array($orderData['type'], $orderModel::$_type) && isset($orderModel::$_price[$orderData['type']])))
        	{
        		$error['type'] = '订单类型 不存在';
        	}
        	if (! $this->_isMobile($orderData['mobile']))
        	{
        		$error['mobile'] = '手机号 无效';
        	}
			if (! (is_numeric($orderData['number']) && $orderData['number'] >= 1 && $orderData['number'] <= 1000))
			{
				$error['number'] = '订单购买数量 无效';
			}
			if(! (is_numeric($orderData['total_price']) &&  isset($orderModel::$_price[$orderData['type']]) && $orderData['number'] * $orderModel::$_price[$orderData['type']] == $orderData['total_price'] ))
			{
				$error['number'] = '订单总价 无效';
			}
			if(! (is_numeric($orderData['code']) && strlen($orderData['code']) == 6))
			{
				$error['code'] = '短信验证码 无效';
			}
			//看看有没有错误
			if (empty($error))
			{
				// 查询用户信息
				$model = $userModel->where(array('mobile' => $orderData['mobile'], 'openid' => $openid))->find();
				if(! $model)
				{
					$error['mobile'] = '手机号 无效';
				}
			}
			//输出错误
			if (! empty($error))
			{
				$this->error(current($error));
			}
			if (! $this->_checkCode($orderData['mobile'],$orderData['code'], SmsModel::use_create_order))
			{
				$error['code'] = '验证码 不正确';
				$this->error(current($error));
			}
			//添加订单 
            // 开户事务
            $userModel->startTrans();
            try
            {
	            $model = $userModel->where(array('mobile' => $orderData['mobile'], 'openid' => $openid))->find();
	            if($model)
	            {
	            	$order = array(
	            			'user_id'=>$model['id'],
	            			'type'=>$orderData['type'],
	            			'price'=>$orderModel::$_price[$orderData['type']],
	            			'number'=>$orderData['number'],
	            			'total_price'=>$orderData['total_price'],
	            			'code_url'=>$model['code_id'],
	            			'order_status'=>$orderModel::order_status_pay_not, //未支付
	            			'create_time'=>time(),
	            			'status'=>1,
	            	);
	            	if ($orderModel->create($order) &&  ($orderID = $orderModel->add()))
	            	{
	            	    // 创建订单编号
	            	    if ( ! $orderModel->where(array('id' => $orderID))->setField('order_no', $orderModel::getOrderNo($orderID)) ) {
	            	        throw new \Exception("更新订单编号 失败");
	            	    }
	            	    
	            		if ($model['status'] == 1)
	            			$return = true;
	            		else
	            		{
		            		$model['status'] = 1;
		            		if ($userModel->save($model))
		            			$return = true;
		            		else 
		            			throw new \Exception("更新用户信息 失败");
	            		}
	            	}else 
	            		throw new \Exception("创建订单 失败");
	            }else 
	            	throw new \Exception("手机号 无效");
	            $userModel->commit();
            }
            catch(\Exception $e)
            {
            	$userModel->rollBack();
            	$this->error($e->getMessage());
            }
            if (isset($return) && $return)
				$this->redirect('Order/pay', array('id'=>$orderID));
				exit;
        }

        $this->display();
    }

	/**
	 * 获取手机验证码
	 */
    public function sendSmsCode()
    {
    	$return = false;
    	if (IS_POST)
    	{
    		$mobile = I('post.mobile', '', 'trim');
    		if ($this->_isMobile($mobile) && $this->_register($mobile))
    		{
				// 取出openid
				$openid = session('openid');
    			// 查询用户信息
    			$model = M('User')->where(array('mobile' => $mobile, 'openid' => $openid))->find();
    			if($model)
    			{
			        $params_v = array(
			            'sms_id' => $model['id'],
			            'sms_type' =>  SmsModel::send_user,
			            'role_id' => $model['id'],
			            'role_type' =>  SmsModel::send_user,
			            'sms_use' => SmsModel::use_create_order,
			        );
	        		$userCreateOrderConf = C('USER_CREATE_ORDER');
			        if(SMS::is_send($mobile, $params_v, $userCreateOrderConf['number'], $userCreateOrderConf['interval']))
			        {
			            $code = rand(100000,999999);
			            $params = array(
			                'sms_id' => $model['id'],
			                'sms_type' =>  SmsModel::send_user,
			                'role_id' => $model['id'],
			                'role_type' =>  SmsModel::send_user,
			                'sms_use' => SmsModel::use_create_order,
			                'code'=>$code,
			                'sms_source'=> SmsModel::source_weixin,
			                'login_address'=>'',
			                'sms_error'=>  $userCreateOrderConf['error'],
			                'end_time'=> time() + $userCreateOrderConf['time'],
			            );
			            $return = SMS::send($mobile, $params, strtr($userCreateOrderConf['content'], array('{code}'=>$code)));
			        }
        		}
    		}
    	}
    	echo $return ? 1 : 0;
    }

	/**
	 * 注册用户
	 * @param $mobile 手机号
	 * @return bool
	 */
	private function _register($mobile)
	{
		// 取出openid
		$openid = session('openid');
		// 查询用户信息
		$openIdModel = M('User')->where(array('mobile' => $mobile, 'openid' => $openid))->find();

		if($openIdModel)
			return true;
		else
		{
			$model = D('User');
			$data = array(
				'mobile'=>$mobile,
				'openid'=>$openid,
				'create_time'=>time(),
				'status'=>0,//表示发送短信的用户 没有验证过的
			);
			if ($model->create($data))
			{
				$uid = $model->add();
				if ($uid) {
					// 同一个手机使用相同一个code，先查询出
					$mobileModel = M('User')->where(array('mobile' => $mobile))->find();
					if (!empty($mobileModel) && $mobileModel['code_id']) {
						$codeId = $mobileModel['code_id'];
					} else {
						$codeId = $this->_getCodeId($uid);
					}

					return $model-> where(array('id'=> $uid))->setField('code_id', $codeId);
				}
				return false;
			}
		}
		return false;
	}

	/**
	 * 获取用户唯一订单消费码
	 * @param $mobile
	 * @param string $id
	 * @return null|string
	 */
	private function _getQrcode($mobile,$id='')
	{
		if ($id == '')
		{
			$model = D('User')->where(array('mobile' => $mobile))->find();
			if ($model)
				$id = $model['id'];
			else
				return null;
		}
		return sha1($mobile.md5($mobile).$id);
	}

	/**
	 * 获取用户唯一订单消费码 12位
	 * @param $id 用户id
	 * @return string
	 */
	private function _getCodeId($id) {
		return substr($id.date('ymd').mt_rand(100000,999999),0,12);
	}
}