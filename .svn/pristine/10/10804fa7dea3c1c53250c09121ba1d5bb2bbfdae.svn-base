<?php
namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 *
 * @author Moore Mo
 */
class IndexController extends HomeController {

	// 系统首页
    public function index(){
        $articleList = $this->categoryInfo('news');
        $this->assign('articleList', $articleList);
        $this->display();
    }

    // 用户端
    public function user() {
        $this->display();
    }

    // 商家端
    public function store() {
        $this->display();
    }

    // 运营商平台
    public function mon() {
        $this->display();
    }

    // 供应商入驻
    public function supplier() {
        $this->display();
    }

    // 运营商入驻
    public function operators() {
        $this->assign('downloadUrl', '/Uploads/Attachment');
        $this->display();
    }

    // 供应商入驻标注
    public function standard() {
        $this->display();
    }

    // 新闻动态
    public function news() {
        // 获取新闻动态 news 下的文档
        $articleList = $this->categoryInfo('news');
        $this->assign('articleList', $articleList);
        $this->display();
    }

    // 新闻详情
    public function detail() {
        $id = I('get.id', 0);
        $articleInfo = array();
        $articleInfo = D('Document')->relation('ArticleCategory')->where(array('id' => $id, 'status' => 1))->order('update_time desc')->find();
        $info = M('DocumentArticle')->where(array('id' => $id))->find();
        if (!empty($info)) {
            $articleInfo = array_merge($articleInfo, $info);
        }

        $this->assign('articleInfo', $articleInfo);
        $this->display('newsDetail');
    }

    // 关于我们
    public function about() {
        $this->display();
    }

    // 加入我们
    public function join() {
        // 获取新闻动态 join 下的文档
        $categoryInfo = M('Category')->field('id')->where(array('status' => 1, 'name' => 'join'))->find();
        $articleList = array();

        if (!empty($categoryInfo)) {
            $Document = D('Document');
            $articleList = $Document->lists($categoryInfo['id'], 'update_time desc');

            foreach($articleList as $key => $article) {
                /* 获取模型数据 */
                $info = M('DocumentArticle')->where(array('id' => $article['id']))->find();
                if (!empty($info)) {
                    $articleList[$key] = array_merge($articleList[$key], $info);
                }
            }
        }
        $this->assign('articleList', $articleList);
        $this->display();
    }


    /* 文档分类检测 */
    private function categoryInfo($id = 0){
        /* 标识正确性检测 */
        $id = $id ? $id : I('get.category', 0);
        if(empty($id)){
            $this->error('没有指定文档分类！');
        }

        /* 获取分类信息 */
        $category = D('Category')->info($id);

        if (empty($category)) {
            return array();
        }

        if($category && 1 == $category['status']){
            switch ($category['display']) {
                case 0:
                    $this->error('该分类禁止显示！');
                    break;
                //TODO: 更多分类显示状态判断
                default:
                    // 取得指定分类下的所有直接子分类
                    $categoryList = M('Category')->field('id')->where(array('status' => 1, 'pid' => $category['id']))->select();
                    $idStr = '';
                    foreach($categoryList as $category) {
                        $idStr .= $category['id'] . ',';
                    }
                    // 所有二级分类的id
                    $idStr = trim($idStr, ',');
                    if ($idStr) {
                        $Document = D('Document');
                        $count = $Document->where(array('status' => 1, 'category_id' => array('in', $idStr)))->count();
                        $Page = new \Common\Util\Page($count, C('HOME_LIST_ROWS'));
                        $articleList = $Document->Relation(true)->where(array('status' => 1, 'category_id' => array('in', $idStr)))->order('update_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

                        $this->assign('pageInfo', $Page->show());

                    } else {
                        $articleList = array();
                    }
                    return $articleList;
            }
        } else {
            $this->error('分类不存在或被禁用！');
        }
    }

}