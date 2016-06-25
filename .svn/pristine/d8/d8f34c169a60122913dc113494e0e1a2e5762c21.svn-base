<?php
namespace Common\Model;
use Think\Model;

/**
 * SMS模型类
 * Class SmsModel
 * @package Common\Model
 */
class SmsModel extends Model {
    /*************************************************谁发短信*********************************************************/
    /**
     * 其他
     * @var integer
     */
    const send_other=0;
    /**
     * 管理员
     * @var integer
     */
    const send_admin=1;
    /**
     * 用户
     * @var integer
     */
    const send_user=2;
    /*************************************************发给谁短信*********************************************************/

    /**
     * 其他
     * @var integer
     */
    const sms_other=0;
    /**
     * 管理员
     * @var integer
     */
    const sms_admin=1;
    /**
     * 用户
     * @var integer
     */
    const sms_user=2;
    /*************************************************短信用途*********************************************************/
    /**
     * 创建订单
     * @var integer
     */
    const use_create_order = 1;
    /**
     * 查询订单
     * @var integer
     */
    const use_query_order = 2;
    /**
     * 通知订单
     * @var integer
     */
    const use_notice_order = 3;

    /**
     * 购买门票
     * @var integer
     */
    const litchi_buy_ticket = 4;

    /**
     * 购买卡券
     * @var integer
     */
    const litchi_buy_card = 5;

    /**
     * 通知订单
     * @var integer
     */
    const litchi_use_notice_order = 6;

    /**
     * 来源
     * @var integer
     */
    const source_pc = 1;
    /**
     * 来源
     * @var integer
     */
    const source_app=2;
    /**
     * 来源
     * @var integer
     */
    const source_weixin=3;

    /**
     * code使用了的状态
     */
    const is_code = 1;

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('phone', 'require', '联系人手机号码必须填写'),
        array('phone', '/^1[34578][0-9]{9}$/', '手机号码格式不正确！', '0', 'regex', 1),
    );

}