<?php
namespace Home\Controller;

/**
 * 类别模型控制器
 * 类别模型列表和详情
 *
 * @author Moore Mo
 */
class CategoryController extends HomeController {

    /**
     * 专题首页
     *
     * @author Moore Mo
     */
    public function index() {
        $this->all();
    }

    /**
     * 所有专题信息
     *
     * @author Moore Mo
     */
    public function all() {
        $Category = D('Category');

        // 统计分类的总数,(二级)
        $count = $Category->where(array('pid' => array('neq', 0), 'status' => 1))->count();// 查询满足要求的总记录数
        $Page = new \Common\Util\Page($count, C('HOME_LIST_ROWS'));

        // 查询条件
        $condition['status'] = 1;
        $condition['pid'] = array('neq', 0);
        $list = $Category->relation(true)->where($condition)->order('update_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        if (IS_POST) {
            $this->success(json_encode(
                array('categoryList' => $list, 'pageInfo' => $Page->getPagesInfo())
            ));
        } else {
            $this->assign('list', $list);
            $this->assign('pageInfo', $Page->getPagesInfo());
            $this->display('all');
        }
    }

    /**
     * 专题详情页
     * @author Moore Mo
     */
    public function detail() {
        $categoryId = I('get.id', 0);
        if (empty($categoryId)) {
            $this->error('没有指定专题分类！');
        }
        // 分类信息
        $category = $this->category($categoryId);
        $category['detail_cover'] = $this->cover($category['detail_icon']);

        // 获取当前分类列表
        $Document = D('Document');

        // 统计分类的总数,(二级)
        // 查询满足要求的总记录数
        $count = $Document->where(array('status' => 1, 'category_id' => $categoryId))->count();
        $Page = new \Common\Util\Page($count, C('HOME_LIST_ROWS'));

        // 查询条件
        $condition['status'] = 1;
        $condition['category_id'] = $categoryId;
        $list = $Document->Relation('ArticleCover')->where($condition)->order('update_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        if (IS_POST) {
            $this->success(json_encode(
                array('articleList' => $list, 'pageInfo' => $Page->getPagesInfo())
            ));
        } else {
            // 当前分类下的文章数
            $category['article_count'] =  $count;

            $this->assign('category', $category);
            $this->assign('list', $list);
            $this->assign('pageInfo', $Page->getPagesInfo());
            $this->display();
        }

    }

    /**
     * 获取分类封面信息
     * @param int $picId
     * @return string
     *
     * @author Moore Mo
     */
    private function cover($picId = 0) {
        // 标识正确性检测
        if (empty($picId)) {
            return '';
        }
        // 获取分类封面信息
        $picture = M('Picture')->where(array('id' => $picId))->find();
        if ($picture && 1 == $picture['status']) {
            return $picture['path'];
        } else {
            return '';
        }
    }

    /**
     * 文档分类检测
     * @param int $id
     * @return mixed
     *
     * @author Moore Mo
     */
    private function category($id = 0) {
        // 标识正确性检测
        $id = $id ? $id : I('get.id', 0);
        if (empty($id)) {
            $this->error('没有指定专题分类！');
        }

        // 获取分类信息
        $category = D('Category')->info($id);
        if ($category && 1 == $category['status']) {
            switch ($category['display']) {
                case 0:
                    $this->error('该分类禁止显示！');
                    break;
                //TODO: 更多分类显示状态判断
                default:
                    return $category;
            }
        } else {
            $this->error('分类不存在或被禁用！');
        }
    }
}
