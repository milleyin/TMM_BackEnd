<?php
namespace Litchi\Controller;
use Think\Controller;
use Think\Model;

/**
 * 订单控制器
 * Class OrderController
 * @package Litchi\Controller
 *
 * @author Moore Mo
 */
class OrderController extends Controller {
	/**
	 * @var String 微信id
	 */
	private $wechatid;

	public function _initialize() {
		$this->wechatid = A('Common/Weixin')->getOpenid();
		//$this->wechatid = 'o_CytuIwPE8e5yHoLQgPzXgRePTM';
	}

	/**
	 * 订单支付
	 * @param $id
	 */
	public function pay($id){
		import('Common.Wxpay.lib.WxPay#Api', APP_PATH, '.php');
		import('Common.Wxpay.unit.WxPay#JsApiPay', APP_PATH, '.php');


		$lzUserModel = M('LzUser');
		$lzOrderModel = D('LzOrder');
		// 查询总订单信息
		$orderInfo = $lzOrderModel->where(array('id' => $id, 'wechatid' => $this->wechatid, 'order_status' => $lzOrderModel::order_status_pay_not))->find();
		if ($orderInfo)
		{
			$userInfo = $lzUserModel->where(array('mobile' => $orderInfo['mobile'], 'wechatid' => $this->wechatid))->find();
			$typeArr = array(1=>'电子门票', 2=>'卡券', 3=>'物流运费');
			//①、获取用户openid
			$tools = new \JsApiPay();

			//②、统一下单
			$input = new \WxPayUnifiedOrder();
			//订单简要描述
			$input->SetBody($orderInfo['mobile'] . ' ' . $typeArr[$orderInfo['type']] . ' * ' . $orderInfo['number'] . '总计：' . $orderInfo['total_price']);
			//订单附加数据
			$input->SetAttach($lzOrderModel->getAttach($orderInfo['id'], $userInfo['id']));
			//订单号
			$input->SetOut_trade_no($orderInfo['order_no']);
			//订单总价
			$input->SetTotal_fee($orderInfo['total_price']*100);
//			$input->SetTotal_fee('1');
			//订单开始时间
			$input->SetTime_start(date("YmdHis"));
			//结束时间
			$input->SetTime_expire(date("YmdHis", time() + 600));
			//商品标记
			//$input->SetGoods_tag('test');
			//回调链接
			$input->SetNotify_url(U('PayNotify/wxpay', '', true, true));
			$input->SetTrade_type("JSAPI");
			$input->SetOpenid($this->wechatid);
			$order = \WxPayApi::unifiedOrder($input);
			//dump($order);die;
			$jsApiParameters = $tools->GetJsApiParameters($order);
			$this->assign('jsApiParameters', $jsApiParameters);
			$this->assign('orderInfo', $orderInfo);
			$this->assign('title', '订单支付');
			$this->display('pay');
		}
	}
}