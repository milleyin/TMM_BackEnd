<?php
namespace Home\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 *
 * @author Moore Mo
 */
class IndexController extends HomeController {

	//系统首页
    public function index(){

        // 推荐到首页的文章列表
        $Document = D('Document');
        // position=4 首页推荐
        $where = array('status' => 1, 'position' => 4);
        $count = $Document->where($where)->count();
        $Page = new \Common\Util\Page($count, C('HOME_LIST_ROWS'));

        $articleList = $Document->Relation(true)->where($where)->order('update_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        if (IS_POST) {
            $this->success(json_encode(
                array('articleList' => $articleList, 'pageInfo' => $Page->getPagesInfo())
            ));
        } else {
            // 首页横幅
            $bannerList = M('Banner')->where(array('status' => 1))->select();



            $this->assign('bannerList', $bannerList);
            $this->assign('articleList', $articleList);
            $this->assign('pageInfo', $Page->getPagesInfo());


            $this->display();
        }
    }
}