<?php
namespace Admin\Controller;

/**
 * 横幅控制器
 * @author Moore Mo
 */
class BannerController extends AdminController {

    /**
     * 横幅管理首页
     * @author Moore Mo
     */
    public function index() {
        // 获取列表数据
        $list   =   $this->lists('Banner');
        // 转化状态显示为字符串
        int_to_string($list);

        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 添加横幅
     * @author Moore Mo
     */
    public function add(){
        if(IS_POST){
            $Banner = D('Banner');
            $data = $Banner->create();
            if($data){
                $id = $Banner->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_banner', 'banner', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Banner->getError());
            }
        } else {
            $this->assign('info',null);
            $this->meta_title = '新增横幅';
            $this->display('edit');
        }
    }

    /**
     * 编辑频道
     * @author Moore Mo
     */
    public function edit($id = 0){
        if(IS_POST){
            $Banner = D('Banner');
            $data = $Banner->create();
            if($data){
                if($Banner->save()){
                    //记录行为
                    action_log('update_banner', 'banner', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($Banner->getError());
            }
        } else {
            $info = array();
            // 获取数据
            $info = M('Banner')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            $this->display();
        }
    }

    /**
     * 删除横幅
     * @author Moore Mo
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Banner')->where($map)->delete()){
            //记录行为
            action_log('update_banner', 'banner', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}