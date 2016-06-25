<?php
namespace Sakura\Controller;
use Common\Library\Util\CThumb;
use Think\Controller;
use Think\Exception;

/**
 * 樱花女神投票控制器
 * Class VoteController
 * @package Sakura\Controller
 *
 * @author Moore Mo
 */
class VoteController extends MainController {

    /**
     * 关注公众号页面
     */
    public function follow() {
        $this->assign('title', '关注公众号');
        $this->display('follow');
    }

    /**
     * 奖品设置页
     */
    public function rewards() {
        $this->assign('title', '樱花女神奖品及参赛规则');
        $this->display('rewards');
    }

    /**
     * 参赛者详情
     */
    public function detail() {
        $id = I('get.id', 0, 'intval');

//        $wxUserInfo = session('wxUserInfo');
//        if (empty($wxUserInfo)) {
//            // 获取公众号中用户的openid
//            $wxUserInfo = $this->_getUserOpenId(U('Vote/detail', array('id'=>$id)));
//            if (empty($wxUserInfo) || $wxUserInfo['subscribe'] == 0) {
//                $this->redirect('Vote/follow');
//            }
//        } else if ($wxUserInfo['subscribe'] == 0) {
//            $this->redirect('Vote/follow');
//        }
        if (! $id) {
            $this->error('非法操作', U('vote/index'));
        }

        $racingInfo = M('Racing')->where(array('id'=> $id, 'status' => 1))->find();
        if (empty($racingInfo)) {
            $this->error('无此参赛者信息', U('vote/index'));
        }
        // 统计名次和距上一名相差多少票
        $racingInfo['rankInfo'] = $this->rankInfo($racingInfo['id']);
        // 查找参赛者照片信息
        $imgList = M('RacingImg')->where(array('to_id' => $racingInfo['id']))->select();
        $racingInfo['imgList'] = $imgList;

        // 是否报过名
        //$racingInfo['isApply'] = $this->isApply($wxUserInfo['openid']);
        // 今天是否可以对该参赛者投票
        //$racingInfo['isCanVote'] = $this->isCanVote($racingInfo['id'], $wxUserInfo['openid']);
        $racingInfo['isCanVote'] = true;
        // 检测活动是否已结束
        $this->assign('isEnd', $this->isEndActivity());

        $this->assign('racingInfo', $racingInfo);

        $this->assign('title', "我是{$racingInfo['nickname']}，正在参加田觅觅“樱花女神”活动，请为我投上宝贵的一票吧！");

        // 报名成功过来的闪存，用于报名成功后第一次进入详情页时的提示
        $applyFlash = S('applyFlash'.$id);
        // 删除闪存
        S('applyFlash'.$id, null);

        $this->assign('applyFlash', $applyFlash);

        $this->display('detail');
    }

    /**
     * 查询参赛者
     */
    public function findRacing() {
        if (IS_AJAX) {
            $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');

            $keyword = I('post.keyword', '', 'trim');
            if (empty($keyword)) {
                $return['status'] = 0;
                $return['info'] = '请输入编号或名字查找';
            } else {
                $condition['id'] = $keyword;
                $condition['nickname'] = $keyword;
                $condition['_logic'] = 'OR';
                $info = M('Racing')->where($condition)->find();
                if (empty($info)) {
                    $return['status'] = 0;
                    $return['info'] = '无此参考者';
                } else {
                    $return['data'] = array('id' => $info['id']);
                }
            }
            $this->ajaxReturn($return);
        }
        $this->error('非法访问', U('vote/index'));
    }

    /**
     * 参赛首页 （最新参赛）
     */
    public function index() {
        $type = I('get.type', 1, 'intval');
        // 获取公众号中用户的openid
//        $wxUserInfo = session('wxUserInfo');
//        if (empty($wxUserInfo)) {
//            // 获取公众号中用户的openid
//            $wxUserInfo = $this->_getUserOpenId(U('Vote/index', array('type'=>$type)));
//            if (empty($wxUserInfo) || $wxUserInfo['subscribe'] == 0) {
//                $this->redirect('Vote/follow');
//            }
//        } else if ($wxUserInfo['subscribe'] == 0) {
//            $this->redirect('Vote/follow');
//        }
        // session保存openid
        //session('wxUserInfo', $wxUserInfo);
        // 查询出所有参赛者
        $racingList = $this->racingList($type);
        if (empty($racingList)) {
            $page = 1;
        } else {
            $page = 2;
        }
//
//        $racingInfo = M('Racing')->where(array('openid' => $wxUserInfo['openid']))->find();
//        if (empty($racingInfo)) {
//            // 没报过名
//            $this->assign('isApply', false);
//        } else {
//            // 已经报过名
//            $this->assign('racingId', $racingInfo['id']);
//            $this->assign('isApply', true);
//        }

        $this->assign('racingList', $racingList);
        // 已报名人数
        $this->assign('userNum', $this->racingUserCount());
        // 投票人数
        $this->assign('voteNum', $this->voteUserCount());
        // 检测活动是否已结束
        $this->assign('isEnd', $this->isEndActivity());

        $this->assign('type', $type);
        $this->assign('page', $page);
        $this->assign('title', '樱花女神参赛首页');
        $this->display('index');
    }

    /**
     * 获取更多参赛者
     */
    public function getMoreRacing() {
        if (IS_AJAX) {
            $type = I('get.type', 1, 'intval');
            $list = $this->racingList($type);
            $this->ajaxReturn($list);
        }
    }

    /**
     * 报名
     */
    public function apply() {
        // 获取公众号中用户的openid
        $wxUserInfo = session('wxUserInfo');
        if (empty($wxUserInfo)) {
            // 获取公众号中用户的openid
            $wxUserInfo = $this->_getUserOpenId(U('Vote/apply'));
            if (empty($wxUserInfo) || $wxUserInfo['subscribe'] == 0) {
                $this->redirect('Vote/follow');
            }
        } else if ($wxUserInfo['subscribe'] == 0) {
            $this->redirect('Vote/follow');
        }

        if (IS_POST) {
            // 检测活动是否结束
            if ($this->isEndActivity()) {
                $this->error('活动已结束', U('Vote/index'));
            }
            $errorInfo = array();
            // 获取图片数组1-5张
            $imageList = I('post.file_upload', '', 'trim');

            // 获取openid
            $data = array(
                'openid' => $wxUserInfo['openid'],
                'nickname' => I('post.nickname', '', 'trim'),
                'mobile' => I('post.mobile', '', 'trim'),
                'introduce' => I('post.introduce', '', 'trim'),
                'racing_time' => time()
            );

            if (empty($data['nickname'])) {
                $errorInfo['nickname'] = '昵称不能为空';
            } else if (! $this->_isMobile($data['mobile'])) {
                $errorInfo['mobile'] = '联系电话无效';
            }  else if (count($imageList) <=0 || count($imageList) > 5) {
                // 图片个数检测1-5张
                $errorInfo['imageList'] = '上传照片只能1-5张';
            }

            if (empty($data['introduce'])) {
                $data['introduce'] = '我是鲜花女神，为我自己代言...';
            } else if (empty($data['nickname'])) {
                $data['nickname'] = $wxUserInfo['nickname'];
            }

            // 错误提示
            if (! empty($errorInfo)) {
                $this->error(current($errorInfo));
            }

            $racingModel = M('Racing');
            // 重复报名检测
            $racingInfo = $racingModel->where(array('openid' => $wxUserInfo['openid']))->find();
            if (empty($racingInfo)) {
                // 开户事务
                $racingModel->startTrans();
                try {
                    $racingInfo = $racingModel->where(array('openid' => $wxUserInfo['openid']))->find();
                    // 重复报名检测
                    if (! empty($racingInfo)) {
                        throw new \Exception('您已报过名');
                    }
                    $id = $racingModel->add($data);
                    if ($id) {
                        // 插入图片
                        if ($this->addRaceImg($id, $imageList)) {
                            // 事务提交
                            $racingModel->commit();

                            S('applyFlash'.$id, 1);
                            $this->redirect('Vote/detail', array('id'=>$id));
                            exit;
                        } else {
                            throw new \Exception('报名失败，上传图片出错');
                        }
                    } else {
                        throw new \Exception('报名失败');
                    }
                } catch(\Exception $e) {
                    // 事务回滚
                    $racingModel->rollback();
                    $this->error($e->getMessage());
                }
            } else {
                $this->error('您已报过名', U('Vote/index'));
            }

        }

        $this->assign('nickname', $wxUserInfo['nickname']);
        $this->display('apply');
    }

    /**
     * 投票
     */
    public function vote() {
        if (IS_AJAX) {
            /* 返回标准数据 */
            $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');

            // 检测活动是否结束
            if ($this->isEndActivity()) {
                $return['status'] = 0;
                $return['info'] = '活动已结束';
                $this->ajaxReturn($return);
            }

            // 获取公众号中用户的openid
            $openid = session('wxUserInfo')['openid'];
            if (empty($openid)) {
                $return['status'] = 2;
                $return['info'] = '请先关注“公众号”';
                $this->ajaxReturn($return);
            }
            $voteLogData = array(
                'to_id' => I('post.id', 0, 'intval'),
                'from_id' => $openid,
                'vote_ip' => get_client_ip(),
                'create_time' => time()
            );

            // 参赛者id
            if (! $voteLogData['to_id']) {
                $return['status'] = 0;
                $return['info'] = '非法操作';
            }

            if ($return['status']) {
                $voteLogModel = M('VoteLog');

                // 每天每人对同一参赛者只能投一票
                $condition['to_id'] = $voteLogData['to_id'];
                $condition['from_id'] = $openid;
                $condition['create_time'] = array('between', array(strtotime(date('Y-m-d', time()) . '00:00:00'), strtotime(date('Y-m-d', time()) . '23:59:59')));
                $oneInfo = $voteLogModel->where($condition)->find();

                if ($oneInfo) {
                    $return['status'] = 0;
                    $return['info'] = '今天您已投过票，请明天再来';
                } else {
                    // 开户事务
                    $voteLogModel->startTrans();
                    try {
                        // 每天每人对同一参赛者只能投一票
                        $oneInfo = $voteLogModel->where($condition)->find();
                        if (empty($oneInfo)) {
                            // 每人每天只有5票
                            unset($condition['to_id']);
                            $count = $voteLogModel->where($condition)->count();
                            if (intval($count) >= 5) {
                                throw new \Exception('您今天票已用完，请明天再来');
                            } else {
                                // 验证通过，插入投票数据
                                $id = $voteLogModel->add($voteLogData);
                                if ($id) {
                                    if ($this->incPoll($voteLogData['to_id'])) {
                                        // 事务提交
                                        $voteLogModel->commit();
                                        $return['info'] = '投票成功，恭喜您为您支持的樱花女神投上了一票';
                                    } else {
                                        throw new \Exception('投票失败，请重试');
                                    }
                                } else {
                                    throw new \Exception('投票失败，请重试');
                                }
                            }
                        } else {
                            throw new \Exception('今天您已投过票，请明天再来');
                        }
                    } catch(\Exception $e) {
                        // 事务回滚
                        $voteLogModel->rollback();
                        $return['status'] = 0;
                        $return['info'] = $e->getMessage();
                        $this->ajaxReturn($return);
                    }
                }
            }
            $this->ajaxReturn($return);
        }
    }

    /**
     * 参赛者列表
     * @param int $type 1 最新参赛 2 最新排行
     * @return mixed
     */
    private function racingList($type=1) {
        if ($type == 2) {
            $order = array('poll' => 'desc','id'=>'desc');
        } else {
            $order = array('racing_time' => 'desc','id'=>'desc');
        }

        $racingModel = M('Racing'); // 实例化User对象
        $count      = $racingModel->where('status=1')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $racingList = $racingModel->where(array('audit_status'=>1, 'status'=>1))->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        // 随机取一张照片
        $racingImg = M('RacingImg');
        foreach($racingList as $key => $val) {
            $racingImgList = $racingImg->where(array('to_id' => $val['id']))->select();
            // 随机取一张图片
            $racingList[$key]['racingImg'] = $racingImgList[array_rand($racingImgList)]['img_url'];
        }
        return $racingList;
    }

    /**
     * 统计指定参赛者的排名和距上一名还差多少票
     * @param $id 参赛者id
     * @return array
     */
    private function rankInfo($id) {
        $model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        // poll票数 rank名次
        $sql = "SELECT * FROM (SELECT id,poll,(@rank:=@rank+1) AS rank FROM __RACING__,(SELECT (@rank:=0)) b ORDER BY poll DESC, id DESC) c WHERE id={$id}";
        // 返回的是一个二维数组，取第一维
        $rankInfo = $model->query($sql);
        $rank = intval($rankInfo[0]['rank']);

        // 距上一名还差多少票
        $sql = "SELECT * FROM (SELECT id,poll,(@rank:=@rank+1) AS rank FROM __RACING__,(SELECT (@rank:=0)) b ORDER BY poll DESC, id DESC) c WHERE rank=".($rank - 1);
        $preRankInfo = $model->query($sql);

        return array(
           'rank' => $rank,
           'difPoll' => $preRankInfo[0]['poll'] - $rankInfo[0]['poll']
        );
    }

    /**
     * 增加参赛图片 1-5张
     * @param $id 参赛者id
     * @param $list 图片列表
     * @return bool|string
     */
    private function addRaceImg($id, $list) {
        if (! $id) {
            return false;
        }
        if (count($list) <=0 || count($list) > 5) {
            return false;
        }
        $imgData = array();
        $cThumb = new CThumb();
        foreach ($list as $val) {

            $fileInfo = pathinfo(basename($val));
            $filename = $fileInfo['filename'];
            $new_path = dirname($val) . '/';

            // 压缩图片
            $compressImgConfig = C('COMPRESS_IMG_CONFIG');
            $cThumb->image = $val;
            $cThumb->mode = 4;
            $cThumb->quality = $compressImgConfig['app']['quality'];
            $cThumb->compression =  $compressImgConfig['app']['compression'];
            $cThumb->width = $compressImgConfig['app']['with'];
            $cThumb->height = $compressImgConfig['app']['height'];
            $cThumb->directory = $new_path;
            $cThumb->defaultName = $filename;
            $cThumb->setSrcExt(pathinfo($val, PATHINFO_EXTENSION));
            $cThumb->createThumb();
            $cThumb->save();

            $imgData[] = array(
                'to_id' => $id,
                'img_url' => $val,
                'upload_time' => time(),
            );
        }
        $racingImgModel = M('RacingImg');
        return $racingImgModel->addAll($imgData);
    }

    /**
     * 参赛者票数加1
     * @param $id 参赛者id
     * @return bool
     */
    private function incPoll($id) {
        return M('Racing')->where(array('id' => $id))->setInc('poll'); // 用户的积分加1
    }

    /**
     * 统计已报名人数
     * @return int
     */
    private function racingUserCount() {
        $count = M('Racing')->count('id');
        return intval($count);
    }

    /**
     * 统计投票人数
     * @return int
     */
    private function voteUserCount() {
        $count = M('VoteLog')->count('distinct from_id');

        // 查询出总共投的票数
        $total_poll = M('Racing')->sum('poll');
        $newCount = floor($total_poll / 2.8);
        
        return intval($newCount);
    }

    /**
     * 判断是否已经报过名
     * @param $openid
     * @return bool
     */
    private function isApply($openid) {
        $info =  M('Racing')->where(array('openid' => $openid))->find();
        if (empty($info)) {
            return false;
        }
        return true;
    }

    /**
     * 判断今天是否还可以投票
     * @param $toId
     * @param $openid
     * @return bool
     */
    private function isCanVote($toId, $openid) {
        // 每天每人对同一参赛者只能投一票
        $condition['to_id'] = $toId;
        $condition['from_id'] = $openid;
        $condition['create_time'] = array('between', array(strtotime(date('Y-m-d', time()) . '00:00:00'), strtotime(date('Y-m-d', time()) . '23:59:59')));
        $info = M('VoteLog')->where($condition)->find();
        //如果今天已经对指定参赛者投过票，返回false
        if (! empty($info)) {
            return false;
        }
        return true;
    }

    /**
     * 判断今天是否还有票可投
     * @param $openid
     * @return bool
     */
    private function isHasVote($openid) {
        // 每人每天只有5票
        $condition['from_id'] = $openid;
        $condition['create_time'] = array('between', array(strtotime(date('Y-m-d', time()) . '00:00:00'), strtotime(date('Y-m-d', time()) . '23:59:59')));
        $count =  M('VoteLog')->where($condition)->count();
        if (intval($count) >= 5) {
            return false;
        }
        return true;
    }

    /**
     * 检测活动是否已结束
     * @return bool true活动已结束 false活动未结束
     */
    private function isEndActivity() {
        $actConfig = C('ACTIVITY_CONFIG');
        return time() > strtotime($actConfig['end_time']);
    }
}