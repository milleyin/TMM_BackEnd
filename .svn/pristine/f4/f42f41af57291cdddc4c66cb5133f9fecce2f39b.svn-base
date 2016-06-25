<?php
namespace Sakura\Model;
use Think\Model;
/**
 * 订单模型
 * Class OrderModel
 * @package Sakura\Model
 *
 * @author Moore Mo
 */
class OrderModel extends Model {
	
	/**
	 * 订单中的价格类型
	 * @var unknown
	 */
	public static $_type = array(1, 2);
	/**
	 * 订单中的价格类型
	 * @var unknown
	 */
	public static $__type = array(1=>'普通票', 2=>'夜樱票');
	/**
	 * 价格类型的价格
	 * @var unknown
	 */
	public static $_price = array(1=>40, 2=>20);
	/**
	 * 订单状态 未支付
	 * @var unknown
	 */
	const order_status_pay_not = 0;
	/**
	 * 订单状态 已支付
	 * @var unknown
	 */
	const order_status_pay_yes = 1;
	/**
	 * 订单状态 已消费
	 * @var unknown
	 */
	const order_status_consume = 2;
	/**
	 * 解释字段名称
	 * @var unknown
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
        array('create_time', 'time', 1, 'function'),
    );
	
    /**
     * 获取订单号 最高32位
     * @param unknown $id
     * @return string
     */
    public static function getOrderNo($id)
    {
        return 'YH' . date('Ymd') . $id . time();
    	//return \WxPayConfig::MCHID.$id.time();
    	
    }
    
    /**
     * 附加值
     * @param unknown $orderID
     * @param unknown $userID
     * @return string
     */
    public static function getAttach($orderID,$userID)
    {
    	return md5($orderID . 'TMM' . $userID);
    }
}