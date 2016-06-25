<?php
namespace Litchi\Controller;
use Think\Controller;

/**
 * 管理控制器
 * Class BackController
 * @package Litchi\Controller
 *
 * @author Moore Mo
 */
class BackController extends Controller {

    private $uid;
    private $loginName;

    public function _initialize() {
        $this->uid = I('session.uid');
        if (empty($this->uid)) {
            $this->redirect('Login/login');
        }
        $this->loginName = I('session.loginName');
        $this->assign('loginName', $this->loginName);
    }

    public function index() {
        $this->ticket();
    }

    /**
     * 游园门票验证
     */
    public function ticket() {
        $mobile = I('get.mobile', '', 'trim');

        if (isMobile($mobile)) {
            // 查询条件
            $condition['is_enter'] = 0;
            $condition['is_valid'] = 1;
            $condition['mobile'] = $mobile;
            $lzTicketModel = M('LzTicket');
            $lzTicketOrderModel = M('LzTicketOrder');

            // 查询出可用的电子门票信息
            $ticketOrderList = $lzTicketOrderModel
                ->field('id, mobile, ticket_id, type, count(id) as nums')
                ->where($condition)
                ->group('mobile,ticket_id,type')
                ->select();

            foreach ($ticketOrderList as $key => $ticketOrder) {
                // 电子门票信息
                $ticketOrderList[$key]['ticketInfo'] = $lzTicketModel->where(array('id' => $ticketOrder['ticket_id']))->find();
            }
        }

        $this->assign('ticketOrderList', $ticketOrderList);
        $this->assign('mobile', $mobile);
        $this->assign('title', '游园门票验证');
        $this->display('ticket');
    }

    /**
     * 确认入园
     */
    public function goToEnter() {
        ! IS_AJAX && $this->error('非法访问');

        $back = new \stdClass();

        $mobile = I('post.mobile', '', 'trim');
        $type = I('post.type');
        $tid = I('post.tid');
        $number = I('post.number');

        if (! is_numeric($number) || ! isMobile($mobile) || !in_array($type, array(1, 2))) {
            $back->status = 0;
            $back->prompt = '非法访问';
            $this->ajaxReturn($back);
        }

        $lzTicketOrderModel = M('LzTicketOrder');
        $lzTicketModel = M('lzTicket');

        // 查询电子门票订单的信息
        $conditions = array('mobile' => $mobile, 'type' => $type, 'ticket_id' => $tid, 'is_enter' => 0, 'is_valid' => 1);
        $ticketOrderInfo = $lzTicketOrderModel->where($conditions)->order('id asc')->find();
        $ticketOrderIds = $lzTicketOrderModel->where($conditions)->limit(0, $number)->order('id asc')->getField('id', true);

        if (empty($ticketOrderIds) || empty($ticketOrderInfo)) {
            $back->status = 0;
            $back->prompt = '抱歉，系统繁忙，请稍后重试';
            $this->ajaxReturn($back);
        }

        // 电子门票的信息
        $ticketInfo = $lzTicketModel->where(array('id' => $ticketOrderInfo['ticket_id']))->find();
        // 判断电子门票是否在使用日期内
        $nowTime = time();
        if ( !($ticketInfo['start_time'] < $nowTime) || !($ticketInfo['end_time'] > $nowTime) ) {
            $back->status = 0;
            $back->prompt = '此票不在使用日期内';
            $this->ajaxReturn($back);
        }

        // 更新入园状态
        $updateResult = $lzTicketOrderModel->where(array('id' => array('in', $ticketOrderIds)))->save(array('is_enter' => 1, 'enter_time' => time()));
        if ($updateResult === false) {
            $back->status = 0;
            $back->prompt = '抱歉，系统繁忙，请稍后重试';
            $this->ajaxReturn($back);
        }

        // 记录操作日志
        $this->_writeActionLog(array(
            'action_user' => $this->loginName,
            'user_id' => $this->uid,
            'action_type' => 1,
            'action_note' => $number . '张电子门票确认入园',
            'action_json' => json_encode(array('ticketIds' => $ticketOrderIds)),
            'add_time' => time()
        ));

        $back->status = 1;
        $back->prompt = '确认入园成功';
        $this->ajaxReturn($back);
    }

    /**
     * 游园门票统计
     */
    public function ticketList() {
        $mobile = I('get.mobile', '', 'trim');
        $ticketType = I('get.ticketType');
        $type = I('get.type');
        $isEnter = I('get.isEnter');

        // 查询条件
        $condition['is_valid'] = 1;
        if (isMobile($mobile)) {
            $condition['mobile'] = $mobile;
        }
        if (is_numeric($type)) {
            $condition['type'] = $type;
        }
        if (is_numeric($ticketType)) {
            $condition['ticket_id'] = $ticketType;
        }
        if (is_numeric($isEnter)) {
            $condition['is_enter'] = $isEnter;
        }
        $lzTicketModel = M('LzTicket');
        $lzTicketOrderModel = M('LzTicketOrder');

        // 查询出可用的电子门票信息 并构造成子查询语句
        $subQuery = $lzTicketOrderModel
            ->field('id, mobile, ticket_id, type, is_enter, count(id) as nums')
            ->where($condition)
            ->group('mobile,ticket_id,type,is_enter')
            ->buildSql();

        // 分页操作
        $count = $lzTicketOrderModel->table($subQuery.' a')->count();// 查询满足要求的总记录数
        $Page = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        if (I('get.action') == 'excel') {
            // 查询出可用的电子门票信息
            $ticketOrderList = $lzTicketOrderModel->table($subQuery.' a')->select();
        } else {
            // 查询出可用的电子门票信息
            $ticketOrderList = $lzTicketOrderModel->table($subQuery.' a')->limit($Page->firstRow.','.$Page->listRows)->select();
        }

        foreach ($ticketOrderList as $key => $ticketOrder) {
            // 电子门票信息
            $ticketOrderList[$key]['ticketInfo'] = $lzTicketModel->where(array('id' => $ticketOrder['ticket_id']))->find();
        }

        // 导出excel
        if (I('get.action') == 'excel') {
            $this->_downloadTicketExcel($ticketOrderList);
        }

        $this->assign('ticketOrderList', $ticketOrderList);
        $this->assign('keyword', array(
            'mobile' => $mobile,
            'ticketType' => $ticketType,
            'type' => $type,
            'isEnter' => $isEnter
        ));
        $this->assign('page', $show);
        $this->assign('title', '游园门票统计');
        $this->display('ticketList');
    }

    /**
     * 卡券验证
     */
    public function card() {
        $lzCardModel = M('LzCard');

        $word = I('get.word', '', 'trim');
        $status = I('get.status');

        // 查询条件
        if (!empty($word)) {
            $condition['is_valid'] = 1;
            if (is_numeric($status)) {
                $condition['is_carry'] = $status;
            }
            $condition['card_no|mobile'] = $word;

            // 分页操作
            $count = $lzCardModel->where($condition)->count();// 查询满足要求的总记录数
            $Page = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $Page->show();// 分页显示输出

            // 查询出可用的卡券信息
            $cardList = $lzCardModel->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
        }

        $this->assign('cardList', $cardList);
        $this->assign('word', $word);
        $this->assign('status', $status);
        $this->assign('page',$show);
        $this->assign('title', '众筹荔枝验证');
        $this->display('card');
    }

    /**
     * 提果
     */
    public function goToCarry() {
        ! IS_AJAX && $this->error('非法访问');

        $lzCardModel = M('lzCard');
        $back = new \stdClass();


        $cardId = max(intval(I('post.id')), 0);

        if (! $cardId) {
            $back->status = 0;
            $back->prompt = '非法访问';
            $this->ajaxReturn($back);
        }
        // 卡券信息
        $cardInfo = $lzCardModel->where(array('id' => $cardId, 'is_valid' => 1, 'is_carry' => 0))->find();
        if (empty($cardInfo)) {
            $back->status = 0;
            $back->prompt = '非法访问';
            $this->ajaxReturn($back);
        }

        // 判断卡券是否在使用日期内
        $nowTime = time();
        if ( !($cardInfo['start_time'] < $nowTime) || !($cardInfo['end_time'] > $nowTime) ) {
            $back->status = 0;
            $back->prompt = '此卡不在使用日期内';
            $this->ajaxReturn($back);
        }
        // 更新提果状态
        $updateCardResult = $lzCardModel->where(array('id' => $cardId, 'is_valid' => 1, 'is_carry' => 0))
            ->save(array('carry_type' => 0, 'is_carry' => 1, 'carry_time' => time()));

        if ($updateCardResult === false) {
            $back->status = 0;
            $back->prompt = '抱歉，系统繁忙，请稍后重试';
            $this->ajaxReturn($back);
        }

        // 记录操作日志
        $this->_writeActionLog(array(
            'action_user' => $this->loginName,
            'user_id' => $this->uid,
            'action_type' => 2,
            'action_note' => '提货卡券：' . $cardInfo['card_no'] . ' 提果',
            'action_json' => json_encode(array('cardInfo' => $cardInfo)),
            'add_time' => time()
        ));

        $back->status = 1;
        $back->prompt = '提果成功';
        $this->ajaxReturn($back);
    }

    /**
     * 卡券统计
     */
    public function cardList() {
        $lzCardModel = M('LzCard');

        $word = I('get.word', '', 'trim');
        $status = I('get.status');

        // 查询条件
        $condition['is_valid'] = 1;
        if (!empty($word)) {
            $condition['card_no|mobile'] = $word;
        }
        if (is_numeric($status)) {
            $condition['is_carry'] = $status;
        }
        // 分页操作
        $count = $lzCardModel->where($condition)->count();// 查询满足要求的总记录数
        $Page = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        // 导出excel
        if (I('get.action') == 'excel') {
            $cardList = $lzCardModel->where($condition)->select();
            $this->_downloadCardExcel($cardList);
        } else {
            // 查询出可用的卡券信息
            $cardList = $lzCardModel->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
        }

        $this->assign('cardList', $cardList);
        $this->assign('word', $word);
        $this->assign('status', $status);
        $this->assign('page',$show);
        $this->assign('title', '众筹荔枝统计');
        $this->display('cardList');
    }

    /**
     * 邮寄管理
     */
    public function logistics() {
        $lzLogisticsModel = M('LzLogistics');

        $word = I('get.word', '', 'trim');
        $status = I('get.status');

        // 查询条件
        $condition['is_valid'] = 1;
        if (is_numeric($status)) {
            $condition['is_send'] = $status;
        }
        if (!empty($word)) {
            $condition['card_no|mobile'] = $word;
        }

        // 分页操作
        $count = $lzLogisticsModel->where($condition)->count();// 查询满足要求的总记录数
        $Page = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        // 查询物流信息
        $logisticsList = $lzLogisticsModel->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('logisticsList', $logisticsList);
        $this->assign('word', $word);
        $this->assign('status', $status);
        $this->assign('page',$show);
        $this->assign('title', '众筹荔枝邮寄');
        $this->display('logistics');
    }

    /**
     * 发货
     */
    public function goToSend() {
        ! IS_AJAX && $this->error('非法访问');

        $lzLogisticsModel = M('LzLogistics');
        $back = new \stdClass();


        $logisticsId = max(intval(I('post.id')), 0);
        $logisticsNo = I('post.logistics_no', '', 'trim');

        if (! $logisticsId || empty($logisticsNo)) {
            $back->status = 0;
            $back->prompt = '非法访问';
            $this->ajaxReturn($back);
        }
        // 物流信息
        $logisticsInfo = $lzLogisticsModel->where(array('id' => $logisticsId, 'is_valid' => 1, 'is_send' => 0))->find();
        if (empty($logisticsInfo)) {
            $back->status = 0;
            $back->prompt = '非法访问';
            $this->ajaxReturn($back);
        }

        // 更新物流状态
        $updateLogisticsResult = $lzLogisticsModel->where(array('id' => $logisticsId, 'is_valid' => 1, 'is_send' => 0))
            ->save(array('logistics_company' => '顺丰速运', 'logistics_no' => $logisticsNo, 'is_send' => 1, 'send_time' => time()));

        if ($updateLogisticsResult === false) {
            $back->status = 0;
            $back->prompt = '抱歉，系统繁忙，请稍后重试';
            $this->ajaxReturn($back);
        }

        // 记录操作日志
        $this->_writeActionLog(array(
            'action_user' => $this->loginName,
            'user_id' => $this->uid,
            'action_type' => 3,
            'action_note' => '提货卡券：' . $logisticsInfo['card_no'] . ' 发货',
            'action_json' => json_encode(array('logisticsInfo' => $logisticsInfo)),
            'add_time' => time()
        ));

        $back->status = 1;
        $back->prompt = '发货成功';
        $this->ajaxReturn($back);
    }

    /**
     * 门票统计
     */
    public function ticketOrderList() {
        $lzOrderModel = D('LzOrder');

        $searchArr = array(
            'orderNo' => I('get.orderNo'),
            'mobile' => I('get.mobile'),
            'fromType' => I('get.fromType'),
            'ticketType' => I('get.ticketType'),
            'startTime' => I('get.startTime'),
            'endTime' => I('get.endTime')
        );

        // 查询条件
        $condition['order_status'] = $lzOrderModel::order_status_pay_yes;
        $condition['type'] = 1;

        if (!empty($searchArr['orderNo'])) {
            $condition['order_no'] = $searchArr['orderNo'];
        }
        if (!empty($searchArr['mobile'])) {
            $condition['mobile'] = $searchArr['mobile'];
        }
        if (is_numeric($searchArr['fromType'])) {
            $condition['from_type'] = $searchArr['fromType'];
        }
        if (is_numeric($searchArr['ticketType'])) {
            $condition['ticket_id'] = $searchArr['ticketType'];
        }
        if (!empty($searchArr['startTime']) && empty($searchArr['endTime'])) {
            $condition['add_time'] = array('egt', strtotime($searchArr['startTime'] . ' 00:00:00'));
        }
        if (empty($searchArr['startTime']) && !empty($searchArr['endTime'])) {
            $condition['add_time'] = array('elt', strtotime($searchArr['endTime'] . ' 23:59:59'));
        }
        if (!empty($searchArr['startTime']) && !empty($searchArr['endTime'])) {
            $condition['add_time'] = array('between',array(strtotime($searchArr['startTime'] . ' 00:00:00'), strtotime($searchArr['endTime'] . ' 23:59:59')));
        }


        // 分页操作
        $count = $lzOrderModel->where($condition)->count();// 查询满足要求的总记录数
        $Page = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        // 导出excel
        if (I('get.action') == 'excel') {
            $ticketOrderListExcel = $lzOrderModel->where($condition)->order('id desc')->select();
            $this->_downloadTicketOrderExcel($ticketOrderListExcel);
        } else {
            // 电子门票的订单信息
            $ticketOrderList = $lzOrderModel->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }

        // 电子门票总订单
        $totalOrder = $lzOrderModel->where(array('order_status' => array('neq', $lzOrderModel::order_status_pay_not), 'type' => 1))->count();

        // 电子门票购买总量
        $totalNumber = $lzOrderModel->where(array('order_status' => array('neq', $lzOrderModel::order_status_pay_not), 'type' => 1))->sum('number');

        // 电子门票购买总价格
        $totalPrice = $lzOrderModel->where(array('order_status' => array('neq', $lzOrderModel::order_status_pay_not), 'type' => 1))->sum('total_price');



        $this->assign('ticketOrderList', $ticketOrderList);
        $this->assign('searchArr', $searchArr);
        $this->assign('page',$show);
        $this->assign('totalOrder', $totalOrder);
        $this->assign('totalNumber', $totalNumber);
        $this->assign('totalPrice', $totalPrice);
        $this->assign('title', '门票统计');
        $this->display('ticketOrderList');
    }

    /**
     * 提货券统计
     */
    public function cardOrderList() {
        $lzOrderModel = D('LzOrder');

        $searchArr = array(
            'orderNo' => I('get.orderNo'),
            'mobile' => I('get.mobile'),
            'fromType' => I('get.fromType'),
            'startTime' => I('get.startTime'),
            'endTime' => I('get.endTime')
        );

        // 查询条件
        $condition['order_status'] = $lzOrderModel::order_status_pay_yes;
        $condition['type'] = 2;

        if (!empty($searchArr['orderNo'])) {
            $condition['order_no'] = $searchArr['orderNo'];
        }
        if (!empty($searchArr['mobile'])) {
            $condition['mobile'] = $searchArr['mobile'];
        }
        if (is_numeric($searchArr['fromType'])) {
            $condition['from_type'] = $searchArr['fromType'];
        }
        if (!empty($searchArr['startTime']) && empty($searchArr['endTime'])) {
            $condition['add_time'] = array('egt', strtotime($searchArr['startTime'] . ' 00:00:00'));
        }
        if (empty($searchArr['startTime']) && !empty($searchArr['endTime'])) {
            $condition['add_time'] = array('elt', strtotime($searchArr['endTime'] . ' 23:59:59'));
        }
        if (!empty($searchArr['startTime']) && !empty($searchArr['endTime'])) {
            $condition['add_time'] = array('between',array(strtotime($searchArr['startTime'] . ' 00:00:00'), strtotime($searchArr['endTime'] . ' 23:59:59')));
        }

        // 分页操作
        $count = $lzOrderModel->where($condition)->count();// 查询满足要求的总记录数
        $Page = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        // 提货券的订单信息
        $cardOrderList = $lzOrderModel->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        // 提货券总订单
        $totalOrder = $lzOrderModel->where(array('order_status' => array('neq', $lzOrderModel::order_status_pay_not), 'type' => 2))->count();

        // 提货券购买总量
        $totalNumber = $lzOrderModel->where(array('order_status' => array('neq', $lzOrderModel::order_status_pay_not), 'type' => 2))->sum('number');

        // 提货券购买总价格
        $totalPrice = $lzOrderModel->where(array('order_status' => array('neq', $lzOrderModel::order_status_pay_not), 'type' => 2))->sum('total_price');

        $this->assign('cardOrderList', $cardOrderList);
        $this->assign('searchArr', $searchArr);
        $this->assign('page',$show);
        $this->assign('totalOrder', $totalOrder);
        $this->assign('totalNumber', $totalNumber);
        $this->assign('totalPrice', $totalPrice);
        $this->assign('title', '提货券统计');
        $this->display('cardOrderList');
    }

    /**
     * 创意自拍管理
     */
    public function voteList() {
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

        $racingModel = M('LzRacing');
        // 分页操作
        $count      = $racingModel->where($condition)->count();// 查询满足要求的总记录数

        $Page       = new \Common\Library\Util\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show       = $Page->show();// 分页显示输出

        $list = $racingModel->where($condition)->order('racing_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('auditStatusArr', $auditStatusArr);

        $this->assign('words', $words);
        $this->assign('list', $list);
        $this->assign('page',$show);
        $this->assign('title', '创意自拍管理');
        $this->display('voteList');
    }

    /**
     * 创意自拍详情
     */
    public function voteDetail() {
        $id = I('get.id', 0, 'intval');

        $info = M('LzRacing')->where(array('id'=>$id))->find();
        $imgList = M('LzRacingImg')->where(array('to_id'=>$id))->select();

        if (empty($info)) {
            $this->error('无此参赛者信息');
        }
        // 参赛者图片
        $info['imgList'] = $imgList;

        $this->assign('info', $info);
        $this->assign('title', '创意自拍详情');
        $this->display('voteDetail');
    }

    /**
     * 创意自拍审核
     */
    public function voteAudit() {
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

        if (M('LzRacing')->field('audit_status,audit_time')->where(array('id'=>$id))->save($data)) {
            $this->success('审核成功', U('Back/voteList'));
        } else {
            $this->success('审核失败，请重新审核', U('Back/voteList'));
        }
    }

    /**
     * 记录操作日志
     * @param $data
     * @return mixed
     */
    private function _writeActionLog($data) {
        return M('LzActionLog')->add($data);
    }


    /**
     * 导出游园门票统计数据
     * @param $ticketOrderList
     */
    private function _downloadTicketExcel($ticketOrderList) {
        $fields = array(
            'mobile' => '手机号',
            'name' => '名称',
            'type' => '类别',
            'nums' => '入园门票',
            'is_enter' => '状态',
        );
        $ticketList = array();
        foreach($ticketOrderList as $key => $val) {
            $ticketList[$key]['mobile'] = $val['mobile'];
            $ticketList[$key]['name'] = $val['ticketInfo']['name'];
            $ticketList[$key]['type'] = $val['type'] == 1 ? '自购' : '赠票';
            $ticketList[$key]['nums'] = $val['nums'];
            $ticketList[$key]['is_enter'] = $val['is_enter'] == 1 ? '已使用' : '未使用';
        }
        $this->_downloadExcel('游园门票统计', $fields, $ticketList);
    }

    /**
     * 导出门票订单统计数据
     * @param $ticketOrderList
     */
    private function _downloadTicketOrderExcel($ticketOrderList) {
        $fields = array(
            'order_no' => '订单号',
            'transaction_id' => '微信交易号	',
            'mobile' => '购买手机',
            'from_type' => '购买渠道',
            'ticket_id' => '类型',
            'price' => '单价',
            'number' => '购买数量',
            'total_price' => '支付费用(元)',
            'add_time' => '购买时间'
        );
        $ticketList = array();
        foreach($ticketOrderList as $key => $val) {
            $ticketList[$key]['order_no'] = $val['order_no'];
            // 在前面加个空格，解决excel中显示为科学计数的问题
            $ticketList[$key]['transaction_id'] = ' ' . $val['transaction_id'];
            $ticketList[$key]['mobile'] = $val['mobile'];
            $ticketList[$key]['from_type'] = $val['from_type'] == 1 ? '田觅觅' : '天美';
            $ticketList[$key]['ticket_id'] = $val['ticket_id'] == 1 ? '妃子笑' : '桂味/糯米糍';
            $ticketList[$key]['price'] = $val['price'];
            $ticketList[$key]['number'] = $val['number'];
            $ticketList[$key]['total_price'] = $val['total_price'];
            $ticketList[$key]['add_time'] = date('Y/m/d H:i:s', $val['add_time']);
        }
        $this->_downloadExcel('门票订单统计', $fields, $ticketList);
    }

    /**
     * 导出卡券统计数据
     * @param $cardOrderList
     */
    private function _downloadCardExcel($cardOrderList) {
        $fields = array(
            'card_no' => '卡券号',
            'use_time' => '使用日期',
            'mobile' => '购券手机号',
            'name' => '名称',
            'type' => '类别',
            'info' => '含果',
            'is_carry' => '是否提果',
            'carry_type' => '提果方式',
            'add_time' => '购买时间',
            'carry_time' => '提果时间'
        );
        $cardList = array();
        foreach($cardOrderList as $key => $val) {
            $cardList[$key]['card_no'] = $val['card_no'];
            $cardList[$key]['use_time'] = date('Y/m/d', $val['start_time']) . '至' . date('Y/m/d', $val['end_time']);
            $cardList[$key]['mobile'] = $val['mobile'];
            $cardList[$key]['name'] = $val['name'];
            $cardList[$key]['type'] = $val['type'] == 1 ? '电子卡' : '实体卡';
            $cardList[$key]['info'] = '桂味50斤';
            $cardList[$key]['is_carry'] = $val['is_carry'] == 1 ? '已提果' : '未提果';
            $cardList[$key]['carry_type'] = $val['carry_type'] == 1 ? '邮寄' : '--';
            $cardList[$key]['add_time'] = date('Y/m/d', $val['add_time']);
            $cardList[$key]['carry_time'] = $val['carry_time'] != 0 ? date('Y/m/d', $val['carry_time']) : '--';
        }
        $this->_downloadExcel('卡券统计', $fields, $cardList);
    }

    /**
     * 导出excel表
     * @param $fileName
     * @param $fields 要导出的数据列名称数组
     * @param $data 要导出的数据
     *
     * @author Moore Mo
     */
    private function _downloadExcel($fileName, $fields, $data) {
        import('Common.Vendor.PHPExcel', APP_PATH, '.php');
        // 英文字母序号串
        $letterStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

        // 设置文档属性
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // 设置单元格水平居中，垂直居中
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // 设置第一行单元格的高度为40
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
        // 设置第一行单元格的边框样式
        $styleThinBlackBorderOutline = array(
            'borders' => array (
                'outline' => array (
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                    'color' => array ('argb' => 'FF000000'),          //设置border颜色
                ),
            ),
        );

        // 列标志
        $cell_num = 0;
        foreach($fields as $key => $value) {
            // 设置第一行的每一单元格的标题
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letterStr[$cell_num]. '1', $value);
            // 设置第一行的每一单元格宽度为40
            $objPHPExcel->getActiveSheet()->getColumnDimension($letterStr[$cell_num])->setWidth(40);
            // 设置第一行的每一单元格的背景色和边框
            $objPHPExcel->getActiveSheet()->getStyle($letterStr[$cell_num]. '1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle($letterStr[$cell_num]. '1')->getFill()->getStartColor()->setARGB('FFCAE8EA');
            $objPHPExcel->getActiveSheet()->getStyle($letterStr[$cell_num]. '1')->applyFromArray($styleThinBlackBorderOutline);
            $cell_num++;
        }

        // 把数据循环写入对应列的单元格中
        foreach($data as $key => $value){
            $key += 2;
            $cell_key = 0;
            // 循环列
            foreach($fields as $k => $field) {
                $val =  $value["{$k}"];

                // 如果是数组转为josn数据
                if (is_array($val)) {
                    $val = json_encode($val);
                }
                // 如果为空填充空串，为了避免为空时相邻列的数据撑过来
                if ($val === '') {
                    $val = '  ';
                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letterStr[$cell_key].$key, $val);
                $cell_key++;

            }
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($fileName);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $filename = $fileName . '-' . date('Y-m-d H:i:s');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        // 设置excel表的名称
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}