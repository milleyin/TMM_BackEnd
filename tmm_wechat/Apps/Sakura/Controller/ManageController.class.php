<?php
namespace Sakura\Controller;
use Think\Controller;

/**
 * 管理控制器
 * Class ManageController
 * @package Sakura\Controller
 *
 * @author Moore Mo
 */
class ManageController extends MainController {

    /**
     * 根据手机号查询购票信息
     */
    public function YH1f1b666990747edfd1874cd1c61861af() {
        $info = array();
        $flag = true;

        $orderModel = D('Order');

        $mobile = I('get.mobile', '', 'trim');

        if (empty($mobile) || !$this->_isMobile($mobile)) {
            $flag = false;
            //$this->error('请输入正确的手机号码');
        } else {
            // 判断用户是否注册过
            $userInfo = M('User')->where(array('mobile' => $mobile))->find();
            if (empty($userInfo)) {
                $flag = false;
                //$this->error('此用户未预订过票');
            }
        }

        if ($flag) {
            // 根据二维码的code_id查询该手机的所有票
            $orderList = $orderModel->where(array('code_url' => $userInfo['code_id']))->select();

            // 统计该手机所购买的票（未兑换、已兑换、未支付）
            if (! empty($orderList)) {

                $info = array(
                    array(
                        'mobile' => $mobile,
                        'normalCount' => 0,
                        'yyCount' => 0,
                        'create_time' => date('Y-m-d H:i:s', time()),
                        'exchange_time' => '',
                        'status' => '1'
                    ),
                    array(
                        'mobile' => $mobile,
                        'normalCount' => 0,
                        'yyCount' => 0,
                        'create_time' => date('Y-m-d H:i:s', time()),
                        'exchange_time' => '',
                        'status' => '2'
                    ),
                    array(
                        'mobile' => $mobile,
                        'normalCount' => 0,
                        'yyCount' => 0,
                        'create_time' => date('Y-m-d H:i:s', time()),
                        'exchange_time' => '',
                        'status' => '0'
                    ),
                );

                foreach ($orderList as $order) {
                    // 未兑换(已支付)
                    if ($order['order_status'] == $orderModel::order_status_pay_yes) {
                        $info[0]['create_time'] = date('Y-m-d H:i:s', $order['create_time']);
                        $info[0]['exchange_time'] = $order['exchange_time'] ? date('Y-m-d H:i:s', $order['exchange_time']) : '';
                        // 普通票
                        if ($order['type'] == $orderModel::$_type[0]) {
                            $info[0]['normalCount'] += $order['number'];
                        }

                        // 夜樱票
                        if ($order['type'] == $orderModel::$_type[1]) {
                            $info[0]['yyCount'] += $order['number'];
                        }
                    }

                    // 已兑换(已消费)
                    if ($order['order_status'] == $orderModel::order_status_consume) {
                        $info[1]['create_time'] = date('Y-m-d H:i:s', $order['create_time']);
                        $info[1]['exchange_time'] = $order['exchange_time'] ? date('Y-m-d H:i:s', $order['exchange_time']) : '';
                        // 普通票
                        if ($order['type'] == $orderModel::$_type[0]) {
                            $info[1]['normalCount'] += $order['number'];
                        }

                        // 夜樱票
                        if ($order['type'] == $orderModel::$_type[1]) {
                            $info[1]['yyCount'] += $order['number'];
                        }
                    }

                    // 未支付
                    if ($order['order_status'] == $orderModel::order_status_pay_not) {
                        $info[2]['create_time'] = date('Y-m-d H:i:s', $order['create_time']);
                        $info[2]['exchange_time'] = $order['exchange_time'] ? date('Y-m-d H:i:s', $order['exchange_time']) : '';
                        // 普通票
                        if ($order['type'] == $orderModel::$_type[0]) {
                            $info[2]['normalCount'] += $order['number'];
                        }

                        // 夜樱票
                        if ($order['type'] == $orderModel::$_type[1]) {
                            $info[2]['yyCount'] += $order['number'];
                        }
                    }


                }
            }
        }

        $this->assign('mobile', $mobile);
        $this->assign('info', $info);
        $this->assign('title', '查询售票信息');
        $this->display('manage');
    }

    /**
     * 根据手机号兑票
     */
    public function YH7ae741ddca06506c0a6308c5aeda87e7() {
            $mobile = I('get.mobile', '', 'trim');
            $url = U('manage/YH1f1b666990747edfd1874cd1c61861af', array('mobile' => $mobile));
            if (empty($mobile) || !$this->_isMobile($mobile)) {
                $this->error('非法操作', $url);
            }
            // 判断用户是否注册过
            $userInfo = M('User')->where(array('mobile' => $mobile))->find();
            if (empty($userInfo)) {
                $this->error('此用户未预订过票', $url);
            }

            // 把此手机未兑换的订单的订单状态设置为已消费
            $orderModel = D('Order');

            if ($orderModel->where(array('code_url' => $userInfo['code_id'], 'order_status' => $orderModel::order_status_pay_yes))->setField(array('order_status' => $orderModel::order_status_consume, 'exchange_time' => time()))) {
                $returnData['code'] = 1;
                $returnData['msg'] = '兑换成功';
                $this->success('兑换成功', $url);
            } else {
                $this->error('兑换失败，请重试', $url);
            }
    }

    /**
     * 统计每天的出售情况
     */
    public function YH2b4e0e9509339fb23ab26347f989709e() {
        $model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        // 每天售票数与金额

        $sql = "SELECT FROM_UNIXTIME(`create_time`, '%Y-%m-%d') AS days,"
             . "SUM(CASE WHEN `type`='1' THEN `number` ELSE 0 END) AS normal_number,"
             . "SUM(CASE WHEN `type`='1' THEN `total_price` ELSE 0 END) AS normal_price,"
             . "SUM(CASE WHEN `type`='2' THEN `number` ELSE 0 END) AS yy_number,"
             . "SUM(CASE WHEN `type`='2' THEN `total_price` ELSE 0 END) AS yy_price,"
             . "SUM(CASE WHEN `order_status`=2 AND `type`=1  THEN `number` ELSE 0 END) AS consume_normal_number,"
             . "SUM(CASE WHEN `order_status`=2 AND `type`=1  THEN `total_price` ELSE 0 END) AS consume_normal_price,"
             . "SUM(CASE WHEN `order_status`=2 AND `type`=2  THEN `number` ELSE 0 END) AS consume_yy_number,"
             . "SUM(CASE WHEN `order_status`=2 AND `type`=2  THEN `total_price` ELSE 0 END) AS consume_yy_price,"
             . "SUM(CASE WHEN `order_status`=2 THEN `number` ELSE 0 END) AS consume_number,"
             . "SUM(CASE WHEN `order_status`=2 THEN `total_price` ELSE 0 END) AS consume_price,"
             . "SUM(`number`) AS sell_number, sum(`total_price`) AS sell_price FROM __ORDER__ WHERE order_status !=0 AND status=1 GROUP BY days";

        $info = $model->query($sql);

        $totalArr = array(
            'total_number' => 0, // 总售票数
            'total_price' => 0.00, // 总计金额
            'total_consume_number' => 0, // 总兑票数
            'total_consume_price' => 0.00, // 总兑票金额
        );
        foreach($info as $vo) {
            $totalArr['total_number'] += $vo['sell_number'];
            $totalArr['total_price'] += $vo['sell_price'];
            $totalArr['total_consume_number'] += $vo['consume_number'];
            $totalArr['total_consume_price'] += $vo['consume_price'];
        }

        $this->assign('info', $info);
        $this->assign('totalArr', $totalArr);
        $this->display('list');
    }

    /**
     * 樱花女神报名审核
     */
    public function YHc36c98e93b699c40d16b7a3fc5a18539() {
        $auditStatusArr = array(0=>'待审', 1=>'通过', -1=>'不通过');

        // 搜索条件 审核状态,编号id,昵称
        $audit_status = I('get.audit_status');
        $id = I('get.id', '');
        $nickname = I('get.nickname', '', 'trim');
        $condition = array();
        // 编号ID搜索时，只精准匹配id
        if (is_numeric($id)) {
            // 转变类型是为了在模板进行全等判断
            $id = intval($id);
            $condition['id'] = $id;
        } else if (is_numeric($audit_status)) {
            // 转变类型是为了在模板进行全等判断
            $audit_status = intval($audit_status);
            $condition['audit_status'] = $audit_status;
            if (!empty($nickname)) {
                $condition['nickname'] = array('like', "%{$nickname}%");
            }
        } else if (!empty($nickname)) {
            $condition['nickname'] = array('like', "%{$nickname}%");
        }
        // 记录历史搜索单词
        $words = array(
            'audit_status' => $audit_status,
            'id' => $id,
            'nickname' => $nickname
        );

        $racingModel = M('Racing');
        // 分页操作
        $count      = $racingModel->where($condition)->count();// 查询满足要求的总记录数

        $Page       = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show       = $Page->show();// 分页显示输出

        $list = $racingModel->where($condition)->order('racing_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('auditStatusArr', $auditStatusArr);

        $this->assign('words', $words);
        $this->assign('list', $list);
        $this->assign('page',$show);
        $this->assign('title', '樱花女神管理');
        $this->display('voteList');
    }

    /**
     * 樱花女神详情
     */
    public function YHce17343327e1f32c7ef72356581db73c() {
        $id = I('get.id', 0, 'intval');

        $info = M('Racing')->where(array('id'=>$id))->find();
        $imgList = M('RacingImg')->where(array('to_id'=>$id))->select();

        if (empty($info)) {
            $this->error('无此参赛者信息');
        }
        // 参赛者图片
        $info['imgList'] = $imgList;

        $this->assign('info', $info);
        $this->assign('title', '樱花女神详情');
        $this->display('voteDetail');
    }

    /**
     * 樱花女神审核
     */
    public function YHc0ce0abb82a1553d0db7877ab3a1aab7() {
        $id = I('get.id', 0, 'intval');
        $type = I('get.type');
        if ( ($id == 0) || ( ! in_array($type, array(0, 1)))) {
            $this->error('非法操作');
        }
        // 审核不通过
        if ($type == 0) {
            $data['audit_status'] = -1;
            $data['audit_time'] = time();
        }

        // 审核通过
        if ($type == 1) {
            $data['audit_status'] = 1;
            $data['audit_time'] = time();
        }

        if (M('Racing')->field('audit_status,audit_time')->where(array('id'=>$id))->save($data)) {
            $this->success('审核成功', U('manage/YHc36c98e93b699c40d16b7a3fc5a18539'));
        } else {
            $this->success('审核失败，请重新审核', U('manage/YHc36c98e93b699c40d16b7a3fc5a18539'));
        }
    }
    
    /**
     * 樱花女神票数管理列表
     */
    public function YH827fac2fa9ae514611ac06490873eb41() {
        $condition = array('audit_status' => 1, 'status' => 1);
        
        $racingModel = M('Racing');
        // 分页操作
        $count      = $racingModel->where($condition)->count();// 查询满足要求的总记录数

        $Page       = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show       = $Page->show();// 分页显示输出

        $list = $racingModel->where($condition)->order(array('poll' => 'desc','id'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list', $list);
        $this->assign('page',$show);
        $this->assign('title', '樱花女神票数管理');
        
        $this->display('pollList');
    }
    
    /**
     * 更新票数
     */
    public function YHbfc40e8ff7b7bab483f3ddef2dd4e445() {
        if (IS_AJAX) {
            /* 返回标准数据 */
            $return  = array('status' => 1, 'info' => '修改成功', 'data' => '');
            
            $data = array(
                'id' => I('post.id', 0, 'intval'),
                'poll' => I('post.poll', '')
            );

            // 参赛者id
            if (! $data['id']) {
                $return['status'] = 0;
                $return['info'] = '非法操作';
            }
            
            if (! is_numeric($data['poll'])) {
                $return['status'] = 0;
                $return['info'] = '票数必须是数值';
            }

            if ($return['status']) {
                $racingModel = M('Racing');
                try {
                    $info = $racingModel->where(array('id' => $data['id'], 'audit_status' => 1, 'status' => 1))->find();
                    if (empty($info)) {
                        throw new \Exception('非法操作，无此参赛者');
                    }
                    // 修改的票数不能小于原来的票数
                    // if ($data['poll'] < $info['poll']) {
                    //     throw new \Exception('修改的票数不能小于原来的票数');
                    // }
                    // 更新票数
                    if ($racingModel->field('poll')->where(array('id' => $data['id']))->save(array('poll' => $data['poll']))) {
                        $return['info'] = '修改成功';
                    } else {
                        throw new \Exception('修改失败');
                    }
                } catch(\Exception $e) {
                    $return['status'] = 0;
                    $return['info'] = $e->getMessage();
                    $this->ajaxReturn($return);
                }
            }
            $this->ajaxReturn($return);
        }
    }
    
}