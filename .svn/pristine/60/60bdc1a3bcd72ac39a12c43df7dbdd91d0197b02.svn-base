<?php
namespace Litchi\Model;
use Think\Model;
/**
 * 订单模型
 * Class LzOrderModel
 * @package Litchi\Model
 *
 * @author Moore Mo
 */
class LzOrderModel extends Model {
	/**
	 * 订单状态 未支付
	 */
	const order_status_pay_not = 0;
	/**
	 * 订单状态 已支付
	 */
	const order_status_pay_yes = 1;
	/**
	 * 订单状态 已消费
	 */
	const order_status_consume = 2;
    /**
     * 解释字段名称
     * @var array
     */
	public static $_order_status = array(
			self::order_status_pay_not => '未支付',
			self::order_status_pay_yes => '已支付',
			self::order_status_consume => '已消费',
	);
	
    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time', 'time', 1, 'function'),
    );

    /**
     * 获取订单号 最高32位
     * @param $id
     * @return string
     */
    public function getOrderNo($id) {
        return 'LZ' . date('Ymd') . $id . time();
    }

    /**
     * 附加值
     * @param $orderID
     * @param $userID
     * @return string
     */
    public function getAttach($orderID, $userID) {
        return md5($orderID . 'TMM' . 'LZ' . $userID);
    }
}