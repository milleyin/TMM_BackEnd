<?php
namespace Home\Controller;

/**
 * 文档模型控制器
 * 文档模型列表和详情
 *
 * @author Moore Mo
 */
class ArticleController extends HomeController {

	/**
	 * 文章首页
	 *
	 * @author Moore Mo
	 */
	public function index() {
		$this->all();
	}

	/**
	 * 查询所有文章
	 *
	 * @author Moore Mo
	 */
	public function all() {
		$Document = D('Document');

		// 统计分类的总数,(二级)
		$count = $Document->where(array('status' => 1))->count(); // 查询满足要求的总记录数
		$Page  = new \Common\Util\Page($count, C('HOME_LIST_ROWS'));

		// 查询条件
		$condition['status'] = 1;
		$list = $Document->where($condition)->order('update_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		if (IS_POST) {
			$this->success(json_encode(
				array('articleList' => $list, 'pageInfo' => $Page->getPagesInfo())
			));
		} else {
			$this->assign('list', $list);
			$this->assign('pageInfo', $Page->getPagesInfo());
			$this->display('all');
		}
	}

	/**
	 * 文档模型列表页
	 * @param int $p
	 */
	public function lists($p = 1) {
		// 分类信息
		$category = $this->category();

		// 获取当前分类列表
		$Document = D('Document');
		$list = $Document->page($p, $category['list_row'])->lists($category['id']);
		if(false === $list){
			$this->error('获取列表数据失败！');
		}

		// 模板赋值并渲染模板
		$this->assign('category', $category);
		$this->assign('list', $list);
		$this->display($category['template_lists']);
	}

	/* 文档模型详情页 */
	public function detail($id = 0, $p = 1){
		// 标识正确性检测
		if(!($id && is_numeric($id))){
			$this->error('文档ID错误！');
		}

		// 页码检测
		$p = intval($p);
		$p = empty($p) ? 1 : $p;

		// 获取详细信息
		$Document = D('Document');
		$info = $Document->detail($id);

		if(!$info){
			$this->error($Document->getError());
		}
		$info['detail_icon_path'] = $this->cover($info['cover_detail_id']);
		// article_type=2 为图册文章
		if ($info['article_type'] == 2) {
			// 关联的图册
			$picList = M('Picture')->where(array('status' => 1, 'pic_type'=>0, 'id' => array('in', $info['pictures'])))->select();
			// 随机取一张图片
			$info['rand_path'] = $picList[array_rand($picList)]['path'];
		} else {
			// article_type=1 为普通文章，没有关联的图册
			$picList = array();
		}
		// 分类信息
		$category = $this->category($info['category_id']);

		// 获取模板
		if ($info['article_type'] == 2) {
			// 图册文章模板
			if (isMobile()) {
				// 手机访问的模板
				$tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detailAlbumMobile';
			} else {
				$tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detailAlbum';
			}

		} else {
			$tmpl = 'Article/' . get_document_model($info['model_id'], 'name') . '/detail';
		}


		// 更新浏览数
		$map = array('id' => $id);
		$Document->where($map)->setInc('view');

		// 模板赋值并渲染模板
		$this->assign('category', $category);
		$this->assign('picList', $picList);
		$this->assign('picCount', count($picList));
		$this->assign('info', $info);
		$this->assign('page', $p); //页码
		$this->display($tmpl);
	}

	/**
	 * 文档分类检测
	 * @param int $id
	 * @return mixed
	 */
	private function category($id = 0){
		// 标识正确性检测
		$id = $id ? $id : I('get.category', 0);
		if(empty($id)){
			$this->error('没有指定文档分类！');
		}

		// 获取分类信息
		$category = D('Category')->info($id);
		if($category && 1 == $category['status']){
			switch ($category['display']) {
				case 0:
					$this->error('该分类禁止显示！');
					break;
				//TODO: 更多分类显示状态判断
				default:
					// 查询封面
					return $category;
			}
		} else {
			$this->error('分类不存在或被禁用！');
		}
	}

	/**
	 * 获取文章详情封面图片
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
}
