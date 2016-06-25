<?php
namespace Sakura\Controller;
use Think\Controller;
use Common\Library\Util\SMS;
use Common\Model\SmsModel;

/**
 * 前台主页控制器
 * Class IndexController
 * @package Sakura\Controller
 *
 * @author Moore Mo
 */
class IndexController extends MainController {
    /**
     * 关注公众账号
     */
    public function index() {
        $this->assign('title', '关注公众号');
        $this->display('follow');
    }

    /**
     * 关注公众号
     */
    public function follow() {
        $this->display('follow');
    }

    /**
     * 活动详情
     */
    public function activity() {
        $this->assign('title', '樱花节详情');
        $this->display('activity');
    }

    /**
     * 门票预订
     */
    public function reserve() {
        // 获取所预定的票的类型
        $orderModel = D('Order');
        $type = I('get.type', '');
        if (! in_array($type, $orderModel::$_type)) {
            $this->error('非法操作');
        }
        // 获取公众号中用户的openid
        $wxUserInfo = $this->_getUserOpenId(U('Index/reserve', array('type'=>$type)));
        if (empty($wxUserInfo) || $wxUserInfo['subscribe'] == 0) {
            $this->redirect('index/index');
        }

        // session保存openid
        session('openid', $wxUserInfo['openid']);

        $this->assign('type', $type);
        $this->assign('price', $orderModel::$_price[$type]);
        $this->assign('title', '樱花节门票预订');
        $this->display('reserve');
    }

    /**
     * 支付成功页面
     * @param $id
     * @param $codeUrl
     */
    public function paySuccess($id, $codeUrl) {
        $orderModel = D('Order');
        $orderData = $orderModel->where(array('id' => $id, 'code_url' => $codeUrl))->find();
        if (empty($orderData)) {
            $this->error('非法操作', U('index/activity'));
            die;
        }
        // 获取公众号中用户的openid
        $wxUserInfo = $this->_getUserOpenId(U('Index/paySuccess', array('id' => $id, 'codeUrl' => $codeUrl)));
        if (empty($wxUserInfo) || $wxUserInfo['subscribe'] == 0) {
            $this->redirect('index/index');
        }

        // 查询普通票的数目
        $normalCount = $orderModel->where(array('code_url' => $codeUrl, 'type' => 1, 'order_status' => $orderModel::order_status_pay_yes))->sum('number');
        // 查询夜樱票的数目
        $yyCount = $orderModel->where(array('code_url' => $codeUrl, 'type' => 2, 'order_status' => $orderModel::order_status_pay_yes))->sum('number');

        $userData = M('User')->where(array('id' => $orderData['user_id']))->find();

        // 生成二维码
        $this->_qrcode($codeUrl, $userData['mobile']);

        $info = array(
            'mobile' => $userData['mobile'],
            'normalCount' => $normalCount,
            'yyCount' => $yyCount
        );

        $this->assign('info', $info);
        $this->assign('title', '预订成功');
        $this->display('paySuccess');
    }

    /**
     * 查询订单二维码
     */
    public function queryCode() {
        // 获取公众号中用户的openid
        $wxUserInfo = $this->_getUserOpenId(U('Index/queryCode'));
        if (empty($wxUserInfo) || $wxUserInfo['subscribe'] == 0) {
            $this->redirect('index/index');
        }
        // 根据公众号openid查询用户信息
        $userInfo = M('User')->where(array('openid' => $wxUserInfo['openid'], 'status' => 1))->select();
        if (! empty($userInfo)) {
            // 用户id数组
            $aId = array();
            foreach($userInfo as $user) {
                $aId[] = $user['id'];
            }

            $orderModel = D('Order');
            // 查询未兑换和已兑换的票
            $orderData = M('Order')->where(array('user_id' => array('in', $aId), 'order_status' => array('neq', $orderModel::order_status_pay_not)))->select();
            // 查询门票信息
            $aTicket = array(
            );

            // 门票各类型所购数量

            // 统计票
            if (!empty($orderData)) {
                foreach ($userInfo as $user) {
                    $userId = $user['id'];
                    $aTicket[$userId]['mobile'] = $user['mobile'];
                    $aTicket[$userId]['codeImgUrl'] = C('CODE_UPLOAD')['rootPath'] . $user['mobile'] . ".png";
                    $aTicket[$userId]['normalYesCount'] = 0;
                    $aTicket[$userId]['normalConsumeCount'] = 0;
                    $aTicket[$userId]['yyYesCount'] = 0;
                    $aTicket[$userId]['yyConsumeCount'] = 0;

                    foreach ($orderData as $order) {
                        if ($order['user_id'] == $userId) {
                            // 统计普通票 未消费
                            if (($order['type'] == 1) && ($order['order_status'] == $orderModel::order_status_pay_yes)) {
                                $aTicket[$userId]['normalYesCount'] += $order['number'];
                            }
                            // 统计普通票 已消费
                            if (($order['type'] == 1) && ($order['order_status'] == $orderModel::order_status_consume)) {
                                $aTicket[$userId]['normalConsumeCount'] += $order['number'];
                            }
                            // 统计夜樱票 未消费
                            if (($order['type'] == 2) && ($order['order_status'] == $orderModel::order_status_pay_yes)) {
                                $aTicket[$userId]['yyYesCount'] += $order['number'];
                            }
                            // 统计夜樱票 已消费
                            if (($order['type'] == 2) && ($order['order_status'] == $orderModel::order_status_consume)) {
                                $aTicket[$userId]['yyConsumeCount'] += $order['number'];
                            }
                        }
                    }
                }
            }
        }
        $this->assign('ticketData', $aTicket);
        $this->assign('title', '门票查询');
        $this->display('queryCode');
    }

    /**
     * 查询订单二维码 根据手机
     */
    public function queryCodeMobile() {
        // 获取公众号中用户的openid
        $wxUserInfo = $this->_getUserOpenId(U('Index/queryCodeMobile'));
        if (empty($wxUserInfo) || $wxUserInfo['subscribe'] == 0) {
            $this->redirect('index/index');
        }

        // session保存openid
        session('openid', $wxUserInfo['openid']);

        $orderData = array();
        if (IS_POST) {
            $error = array();
            // 获取表单数据
            $data = array(
                'mobile' => I('post.mobile', '', 'trim'),
                'code' => I('post.code', '', 'trim')
            );

            // 校验数据
            if (! $this->_isMobile($data['mobile'])) {
                $error['mobile'] = '手机号 无效';
            }

            if(! (is_numeric($data['code']) && strlen($data['code']) == 6))
            {
                $error['code'] = '短信验证码 无效';
            }
            // 输出错误
            if(! empty($error))
            {
                $this->error(current($error), U('index/queryCodeMobile'));
                die;
            }
            if (! $this->_checkCode($data['mobile'], $data['code'], SmsModel::use_query_order))
            {
                $error['code'] = '短信验证码 不正确';
                $this->error(current($error), U('index/queryCodeMobile'));
            }

            // 如果没错
            if (empty($error))
            {
                // 查询用户信息
                $userInfo = M('User')->where(array('mobile' => $data['mobile'], 'status' => 1))->find();
                // 如果不为空，查询订单信息
                if(! empty($userInfo))
                {
                    $orderModel = D('Order');
                    // 查询未消费和已消费的票
                    $orderData = M('Order')->where(array('code_url' => $userInfo['code_id'], 'order_status' => array('neq', $orderModel::order_status_pay_not)))->select();
                    // 门票各类型所购数量
                    $info = array(
                        'mobile' => $userInfo['mobile'],
                        'normalYesCount' => 0,
                        'normalConsumeCount' => 0,
                        'yyYesCount' => 0,
                        'yyConsumeCount' => 0
                    );
                    // 统计票
                    if (!empty($orderData)) {
                        foreach ($orderData as $order) {
                            // 统计普通票 未消费
                            if ( ($order['type'] == 1) && ($order['order_status'] == $orderModel::order_status_pay_yes) ) {
                                $info['normalYesCount'] += $order['number'];
                            }
                            // 统计普通票 已消费
                            if ( ($order['type'] == 1) && ($order['order_status'] == $orderModel::order_status_consume) ) {
                                $info['normalConsumeCount'] += $order['number'];
                            }
                            // 统计夜樱票 未消费
                            if ( ($order['type'] == 2) && ($order['order_status'] == $orderModel::order_status_pay_yes) ) {
                                $info['yyYesCount'] += $order['number'];
                            }
                            // 统计夜樱票 已消费
                            if ( ($order['type'] == 2) && ($order['order_status'] == $orderModel::order_status_consume) ) {
                                $info['yyConsumeCount'] += $order['number'];
                            }
                        }
                        // 显示二维码
                        $this->assign('codeImgUrl', C('CODE_UPLOAD')['rootPath'] . $data['mobile'] . ".png");
                        // 显示所预定的门票数信息
                        $this->assign('info', $info);
                    }
                }
                $this->assign('title', '门票查询');
                $this->assign('orderData', $orderData);
                $this->display('queryMobileSuccess');
                die;
            }
        }
        $this->assign('orderData', $orderData);
        $this->assign('title', '门票查询');
        $this->display('queryCodeMobile');
    }

    /**
     * 扫描二维码
     * @param $code
     */
    public function scanCode($code) {
        $code = trim($code);

        $return = array(
            'code' => 0,
            'msg' => '',
            'phone' => '',
            'data' => array()
        );

        // 简单校验二维码
        if (empty($code) || strlen($code) > 12) {
            $return['msg'] = '二维码无效';
        } else {
            $userInfo = M('User')->where(array('code_id' => $code, 'status' => 1))->find();

            if (empty($userInfo)) {
                $return['msg'] = '二维码无效';
            } else {
                $normalCount = 0;
                $yyCount = 0;
                // 查询订单数据（未消费并且小于当前时间的票）
                $orderModel = D('Order');
                $condition = array('code_url' => $code, 'order_status' => $orderModel::order_status_pay_yes, 'create_time' => array('lt', time()));
                $orderData = $orderModel->where($condition)->select();
                if (!empty($orderData)) {
                    // 统计票数
                    foreach ($orderData as $order) {
                        // 统计普通票 未消费
                        if ( ($order['type'] == 1) && ($order['order_status'] == $orderModel::order_status_pay_yes) ) {
                            $normalCount += $order['number'];
                        }
                        // 统计夜樱票 未消费
                        if ( ($order['type'] == 2) && ($order['order_status'] == $orderModel::order_status_pay_yes) ) {
                            $yyCount += $order['number'];
                        }
                    }
                    // 设置成已消费
                    $orderModel->where($condition)->setField(array('order_status' => $orderModel::order_status_consume, 'exchange_time' => time()));
                    // 要返回的json数据
                    $return['code'] = 1;
                    $return['msg'] = 'SUCCESS';
                    $return['phone'] = $userInfo['mobile'];
                    $return['data'] = array(
                        array(
                            'type' => 1,
                            'number' => $normalCount
                        ),
                        array(
                            'type' => 2,
                            'number' => $yyCount
                        )
                    );
                } else {
                    $return['msg'] = '此手机未预订门票或已出票';
                }

            }
        }
        echo json_encode($return);
        die;
    }

    /**
     * 确认消费
     */
    public function confirm() {
        $this->display('confirm');
    }

    /**
     * 获取手机验证码
     */
    public function getSmsCode()
    {
        $return = false;
        if (IS_AJAX)
        {
            $mobile = I('post.mobile', '', 'trim');
            if ($this->_isMobile($mobile))
            {
                $openid = session('openid');
                // 查询用户信息
                $model = M('User')->where(array('mobile' => $mobile, 'openid' => $openid, 'status' => 1))->find();
                if(! empty($model))
                {
                    $params_v = array(
                        'sms_id' => $model['id'],
                        'sms_type' =>  SmsModel::send_user,
                        'role_id' => $model['id'],
                        'role_type' =>  SmsModel::send_user,
                        'sms_use' => SmsModel::use_query_order
                    );
                    $userQueryOrderConf = C('USER_QUERY_ORDER');
                    if(SMS::is_send($mobile, $params_v, $userQueryOrderConf['number'], $userQueryOrderConf['interval']))
                    {
                        $code = rand(100000,999999);
                        $params = array(
                            'sms_id' => $model['id'],
                            'sms_type' =>  SmsModel::send_user,
                            'role_id' => $model['id'],
                            'role_type' =>  SmsModel::send_user,
                            'sms_use' => SmsModel::use_query_order,
                            'code'=>$code,
                            'sms_source'=> SmsModel::source_weixin,
                            'login_address'=>'',
                            'sms_error'=>  $userQueryOrderConf['error'],
                            'end_time'=> time() + $userQueryOrderConf['time'],
                        );
                        $return = SMS::send($mobile, $params, strtr($userQueryOrderConf['content'], array('{code}'=>$code)));
                    }
                } else {
                    echo 2;
                    die;
                }
            }
        }
        echo $return ? 1 : 0;
        die;
    }



    /**
     * 通知Boss,每天的票出售情况
     */
    public function YH845b7e3e626a3dafdd74e56a40561731() {

        $info = array(
            'daySellCount' => 0, // 今日已售票数
            'daySellNormalCount' => 0, // 今日已售普通票数
            'daySellYYCount' => 0, // 今日已售夜樱票数
            'dayConsumeCount' => 0, // 今日已消费的票数
            'dayConsumeNormalCount' => 0, // 今日已兑换普通票数
            'dayConsumeYYCount' => 0, // 今日已兑换夜樱票数
            'daySellTotalPrice' => 0.00, // 今日售票金额
            'daySellNormalTotalPrice' => 0.00, // 今日已售普通票金额
            'daySellYYTotalPrice' => 0.00, // 今日已售夜樱票金额
            'dayConsumeTotalPrice' => 0.00, // 今日已消费的金额
            'dayConsumeNormalTotalPrice' => 0.00, // 今日已兑换普通票金额
            'dayConsumeYYTotalPrice' => 0.00, // 今日已兑换夜樱票金额
            'sellCount' => 0, // 至今总售票数
            'consumeCount' => 0, // 总兑票数
            'sellTotalPrice' => 0.00, // 总计金额
            'consumeTotalPrice' => 0.00, // 总兑票金额
        );


        $orderModel = D('Order');
        // 今天的时间 00:00:00 -- 23:59:59
        $condition['create_time'] = array('between',array(strtotime(date('Y-m-d', time()).' 00:00:00'),strtotime(date('Y-m-d', time()).' 23:59:59')));
        $condition['order_status'] = array('neq', $orderModel::order_status_pay_not);
        //  今日售票的数据
        $dayData = $orderModel->where($condition)->field('type,number,total_price,order_status')->select();
        foreach($dayData as $vo) {
            $info['daySellCount'] += $vo['number'];// 今日已售票数

            $info['daySellTotalPrice'] += $vo['total_price']; // 今日售票金额
            // 今日已售-普通票
            if ($vo['type'] == $orderModel::$_type[0]) {
                $info['daySellNormalCount'] += $vo['number']; // 今日已售普通票数
                $info['daySellNormalTotalPrice'] += $vo['total_price']; // 今日已售普通票金额
            }
            // 今日已售-夜樱票 数
            if ($vo['type'] == $orderModel::$_type[1]) {
                $info['daySellYYCount'] += $vo['number']; // 今日已售夜樱票数
                $info['daySellYYTotalPrice'] += $vo['total_price']; // 今日已售夜樱票金额
            }

            // 今日已消费的（已兑换）
            if ($vo['order_status'] == $orderModel::order_status_consume) {
                $info['dayConsumeCount'] += $vo['number']; // 今日已消费的票数
                $info['dayConsumeTotalPrice'] += $vo['total_price']; // 今日已消费的金额

                // 今日已兑换-普通票 数
                if ($vo['type'] == $orderModel::$_type[0]) {
                    $info['dayConsumeNormalCount'] += $vo['number']; // 今日已兑换普通票数
                    $info['dayConsumeNormalTotalPrice'] += $vo['total_price']; // 今日已兑换普通票金额
                }
                // 今日已兑换-夜樱票 数
                if ($vo['type'] == $orderModel::$_type[1]) {
                    $info['dayConsumeYYCount'] += $vo['number']; // 今日已兑换夜樱票数
                    $info['dayConsumeYYTotalPrice'] += $vo['total_price']; // 今日已兑换夜樱票金额
                }

            }

        }


        // 总共售票的数据(已支付，已兑换的，不包括未支付的)
        $allData = $orderModel->where(array('order_status' => array('neq', $orderModel::order_status_pay_not)))->field('number,total_price,order_status')->select();
        foreach($allData as $v) {
            $info['sellCount'] += $v['number'];// 至今总售票数
            $info['sellTotalPrice'] += $v['total_price']; // 总计金额
            // 总已消费（已兑换的）
            if ($v['order_status'] == $orderModel::order_status_consume) {
                $info['consumeCount'] += $v['number'];// 至今总售票数
                $info['consumeTotalPrice'] += $v['total_price']; // 总计金额
            }
        }

        // 最后格式化金钱
        $info['daySellTotalPrice'] = $this->number_format($info['daySellTotalPrice']);
        $info['daySellNormalTotalPrice'] = $this->number_format($info['daySellNormalTotalPrice']);
        $info['daySellYYTotalPrice'] = $this->number_format($info['daySellYYTotalPrice']);
        $info['dayConsumeTotalPrice'] = $this->number_format($info['dayConsumeTotalPrice']);
        $info['dayConsumeNormalTotalPrice'] = $this->number_format($info['dayConsumeNormalTotalPrice']);
        $info['dayConsumeYYTotalPrice'] = $this->number_format($info['dayConsumeYYTotalPrice']);
        $info['sellTotalPrice'] = $this->number_format($info['sellTotalPrice']);
        $info['consumeTotalPrice'] = $this->number_format($info['consumeTotalPrice']);

        $content = "今日售票：{$info['daySellCount']}张，普通票：{$info['daySellNormalCount']}张，夜樱票：{$info['daySellYYCount']}张。"
            . "今日兑票：{$info['dayConsumeCount']}张，普通票：{$info['dayConsumeNormalCount']}张，夜樱票：{$info['dayConsumeYYCount']}张。"
            . "今日售票金额：{$info['daySellTotalPrice']}元，普通票：{$info['daySellNormalTotalPrice']}元，夜樱票：{$info['daySellYYTotalPrice']}元。"
            . "今日兑票金额：{$info['dayConsumeTotalPrice']}元，普通票：{$info['dayConsumeNormalTotalPrice']}元，夜樱票：{$info['dayConsumeYYTotalPrice']}元。"
            . "至今总售票：{$info['sellCount']}张，总兑票：{$info['consumeCount']}张。"
            . "总计金额：{$info['sellTotalPrice']}元，总兑票金额：{$info['consumeTotalPrice']}元。【樱花节】";

        if (IS_AJAX) {
            $result1 = SMS::notice(C('YH_NOTICE_PHONE')['phone_one'], $content);
            $result2 = SMS::notice(C('YH_NOTICE_PHONE')['phone_two'], $content);
            $result3 = SMS::notice(C('YH_NOTICE_PHONE')['phone_three'], $content);
            if ($result1 || $result2 || $result3) {
                // 发送成功
                echo 1;
                die;
            } else {
                // 发送失败
                echo 0;
                die;
            }

        }

        $this->assign('info', $info);
        $this->display('notice');
    }

    /**
     * 生成二维码，并保存到本地目录上
     * @param $text 二维码的内容
     * @param $mobile 手机号
     */
    private function _qrcode($text, $mobile) {
        $dir = C('CODE_UPLOAD')['rootPath'];
        $filename = $dir . $mobile . ".png";
        if (!is_dir(C('CODE_UPLOAD')['rootPath'])) {
            // 不存在目录则创建并设置权限为777
            mkdir($dir, 0777, true);
        } else {
            // 如果文件已经存在则删除，为了防止清空数据时，重新生成的二维码还是原来的内容
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        import('Common.Phpqrcode.phpqrcode', APP_PATH, '.php');
        \QRcode::png($text, $filename, QR_ECLEVEL_L, 5, 0, true);

        $this->assign('codeImgUrl', $filename);
    }
}