<?php
namespace Litchi\Controller;
use Common\Library\Util\SMS;
use Common\Model\SmsModel;
use Think\Controller;

/**
 * Class CardController
 * @package Litchi\Controller
 *
 * @author Moore Mo
 */
class TicketController extends CommonController {
    /**
     * 购买门票首页
     */
    public function index() {
        $this->isFollow();
        $ticketList = M('LzTicket')->where(array('status' => 1))->select();

        $this->assign('ticketList', $ticketList);
        $this->assign('title', '购买门票');
        $this->display('index');
    }

    /**
     * 购买门票详情页
     */
    public function buy() {
        $id = max(intval(I('get.id')), 0);
        if (! $id) {
            $this->error('非法访问');
        }

        $ticketInfo = M('LzTicket')->where(array('id'=> $id, 'status' => 1))->find();

        if (empty($ticketInfo)) {
            $this->error('无此门票信息');
        }

        $this->assign('ticketInfo', $ticketInfo);
        $this->assign('title', $ticketInfo['name']);
        $this->display('buy');
    }

    /**
     * 购买门票
     */
    public function order() {
        $model = M();
        $lzUserModel = M('LzUser');
        $lzOrderModel = D('LzOrder');
        $lzTicketOrderModel = M('LzTicketOrder');

        $mobile = I('post.mobile', '', 'trim');
        $code = I('post.code', '', 'trim');
        $ticketId = max(intval(I('post.id')), 0);
        $number = I('post.number', '', 'trim');
        $total_price = I('post.total_price', '', 'trim');

        if (! $ticketId) {
            $this->error('非法操作');
        }

        if (! isMobile($mobile)) {
            $this->error('手机号 无效');
        }

        if (! (is_numeric($code) && strlen($code) == 6) ) {
            $this->error('短信验证码 无效');
        }

        if (! (is_numeric($number) && $number >= 1) ) {
            $this->error('订单购买数量 无效');
        }

        $ticketInfo = M('LzTicket')->where(array('id'=> $ticketId))->find();

        if (empty($ticketInfo)) {
            $this->error('订单商品 不存在');
        }

        if (! (is_numeric($total_price) && ($number * $ticketInfo['price'] == $total_price)) ) {
            $this->error('订单总价 无效');
        }

        // 查询用户信息
        $userInfo = $lzUserModel->where(array('mobile' => $mobile, 'wechatid' => $this->wechatid))->find();
        if(! $userInfo) {
            $this->error('手机号 无效');
        }

        if (! $this->_checkCode($mobile, $code, SmsModel::litchi_buy_ticket)) {
            $this->error('验证码 不正确');
        }

        // 总订单数据
        $orderData = array(
            'wechatid' => $this->wechatid,
            'mobile' => $mobile,
            'type' => 1, // 1 电子门票
            'ticket_id' => $ticketId,
            'price' => $ticketInfo['price'],
            'number' => $number,
            'total_price' => $total_price,
            'up_time' => time(),
            'add_time' => time(),
            'from_type' => $this->fromType
        );

        // 开户事务
        $model->startTrans();
        try {
            // 创建主订单
            $orderId = $lzOrderModel->add($orderData);
            if (! $orderId) {
                throw new \Exception('创建订单 失败');
            }
            // 创建订单编号
            if ( ! $lzOrderModel->where(array('id' => $orderId))->setField('order_no', $lzOrderModel->getOrderNo($orderId)) ) {
                throw new \Exception("更新订单编号 失败");
            }

            // 创建电子票订单
            for ($i = 0; $i < $number; $i++) {
                // 电子门票订单数据
                $data[] = array(
                    'order_id' => $orderId,
                    'wechatid' => $this->wechatid,
                    'mobile' => $mobile,
                    'type' => 1, // 1自购
                    'ticket_id' => $ticketId,
                    'ticket_price' => $ticketInfo['price'],
                    'add_time' => time(),
                    'from_type' => $this->fromType
                );
            }
            // 批量生成卡券
            $result = $lzTicketOrderModel->addAll($data);
            if (! $result) {
                throw new \Exception('创建订单 失败');
            }

            // 更新用户状态
            $userInfo = $lzUserModel->where(array('mobile' => $mobile, 'wechatid' => $this->wechatid))->find();
            if ($userInfo['status'] != 1) {
                if ( ! $lzUserModel->where(array('mobile' => $mobile, 'wechatid' => $this->wechatid))->setField('status', 1) ) {
                    throw new \Exception("更新用户状态 失败");
                }
            }

            // 提交事务
            $model->commit();
            // 创建订单成功，跳转到支付页面
            $this->redirect('Order/pay', array('id' => $orderId));

        } catch (\Exception $e) {
            $model->rollback();
            $this->error($e->getMessage());
        }

    }

    /**
     * 支付成功页面(购票成功详情)
     */
    public function paySuccess() {
        $orderId = max(intval(I('get.id')), 0);
        if (! $orderId) {
            $this->error('非法访问');
        }

        $lzOrderModel = D('LzOrder');

        // 支付成功的订单信息（电子门票）
        $orderInfo = $lzOrderModel->where(array('id' => $orderId, 'wechatid' => $this->wechatid, 'type' => 1, 'order_status' => $lzOrderModel::order_status_pay_yes))->find();
        if (empty($orderInfo)) {
            $this->error('非法访问');
        }

        // 电子门票订单信息
        $ticketCount = M('LzTicketOrder')->where(array('order_id' => $orderId, 'wechatid' => $this->wechatid))->count();
        if (empty($ticketCount)) {
            $this->error('非法访问');
        }

        // 电子门票信息
        $ticketInfo = M('LzTicket')->where(array('id'=> $orderInfo['ticket_id'], 'status' => 1))->find();

        $this->assign('orderInfo', $orderInfo);
        $this->assign('ticketInfo', $ticketInfo);

        $this->assign('title', '购票成功');
        $this->display('paySuccess');
    }

    /**
     * 我的门票
     */
    public function myTicket() {
        $lzTicketModel = M('LzTicket');
        $ticketOrderList = array();
        $lzOrderModel = D('LzOrder');
        // 总订单id数组
        $orderIdArr = $lzOrderModel->where(array('wechatid' => $this->wechatid, 'order_status' => array('eq', $lzOrderModel::order_status_pay_yes)))->getField('id', true);
        if ($orderIdArr) {
            $orderIds = implode(',', $orderIdArr);

            // 电子门票订单信息
            $ticketOrderList = M('LzTicketOrder')
                ->field('mobile, ticket_id, type, is_enter,count(id) as nums')
                ->where(array('wechatid' => $this->wechatid, 'order_id' => array('in', $orderIds)))
                ->order('is_enter')
                ->group('mobile,ticket_id,type,is_enter')
                ->select();

            foreach ($ticketOrderList as $key => $ticketOrder) {
                // 电子门票信息
                $ticketOrderList[$key]['ticketInfo'] = $lzTicketModel->where(array('id' => $ticketOrder['ticket_id']))->find();
            }

        }

        $this->assign('ticketOrderList', $ticketOrderList);
        $this->assign('title', '我的门票');
        $this->display('myTicket');
    }

    /**
     * 获取手机验证码（购买门票）
     */
    public function sendSmsCode() {
        ! IS_AJAX && $this->error('非法访问');
        $back = new \stdClass();
        $return = false;

        $mobile = I('post.mobile', '', 'trim');
        if (isMobile($mobile) && $this->_register($mobile))
        {
            // 查询用户信息
            $userInfo = M('LzUser')->where(array('mobile' => $mobile, 'wechatid' => $this->wechatid))->find();
            if($userInfo)
            {
                $params_v = array(
                    'sms_id' => $userInfo['id'],
                    'sms_type' =>  SmsModel::send_user,
                    'role_id' => $userInfo['id'],
                    'role_type' =>  SmsModel::send_user,
                    'sms_use' => SmsModel::litchi_buy_ticket,
                );
                $userBuyTicketConf = C('USER_BUY_TICKET');
                if(SMS::is_send($mobile, $params_v, $userBuyTicketConf['number'], $userBuyTicketConf['interval']))
                {
                    $code = rand(100000,999999);
                    $params = array(
                        'sms_id' => $userInfo['id'],
                        'sms_type' =>  SmsModel::send_user,
                        'role_id' => $userInfo['id'],
                        'role_type' =>  SmsModel::send_user,
                        'sms_use' => SmsModel::litchi_buy_ticket,
                        'code' => $code,
                        'sms_source' => SmsModel::source_weixin,
                        'login_address' => '',
                        'sms_error' =>  $userBuyTicketConf['error'],
                        'end_time' => time() + $userBuyTicketConf['time'],
                    );
                    $return = SMS::send($mobile, $params, strtr($userBuyTicketConf['content'], array('{code}'=>$code)));
                }
            }
        }

        if ($return) {
            $back->status = 1;
            $back->prompt = '发送成功';
        } else {
            $back->status = 0;
            $back->prompt = '发送失败';
        }

        $this->ajaxReturn($back);
    }

    /**
     * 注册用户
     * @param $mobile 手机号
     * @return bool
     */
    private function _register($mobile)
    {
        // 查询用户信息
        $lzUserModel = M('LzUser')->where(array('mobile' => $mobile, 'wechatid' => $this->wechatid))->find();

        if($lzUserModel) {
            return true;
        } else {
            $model = M('LzUser');
            $data = array(
                'mobile' => $mobile,
                'wechatid' => $this->wechatid,
                'add_time' => time(),
                'status' => 0,//表示发送短信的用户 没有验证过的
            );
            $uid = $model->add($data);
            if ($uid) {
               return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 验证短信验证码
     * @param $mobile 手机号
     * @param $code 短信验证码
     * @param $use 短信用途
     * @return bool
     */
    private function _checkCode($mobile, $code, $use) {
        // 查询用户信息
        $userInfo = M('LzUser')->where(array('mobile' => $mobile, 'wechatid' => $this->wechatid))->find();
        if(!$userInfo)
            return false;
        $params=array(
            'sms_id' => $userInfo['id'],
            'sms_type' =>  SmsModel::send_user,
            'role_id' => $userInfo['id'],
            'role_type' =>  SmsModel::send_user,
            'sms_use' => $use,
        );
        return SMS::verifycode($mobile, $params, $code);
    }
}