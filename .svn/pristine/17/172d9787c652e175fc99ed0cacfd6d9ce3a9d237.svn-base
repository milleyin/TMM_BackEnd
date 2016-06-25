<?php
namespace Sakura\Controller;
use Think\Controller;
use Think\Upload;

/**
 * 文件控制器
 * 主要用于文件上传和下载
 * Class FileController
 * @package Sakura\Controller
 *
 * @author Moore Mo
 */
class FileController extends Controller {

    /**
     * 上传图片
     */
    public function uploadPicture(){
        /* 返回标准数据 */
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');

        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $Upload = new Upload(C('PICTURE_UPLOAD'), C('PICTURE_UPLOAD_DRIVER'), C("UPLOAD_{$pic_driver}_CONFIG"));
        $info   = $Upload->upload($_FILES);

        /* 记录图片信息 */
        if($info){
            $return['status'] = 1;
            $return['data'] = array('path' => C('PICTURE_UPLOAD')['rootPath'] . $info[0]['savepath'] . $info[0]['savename']);
        } else {
            $return['status'] = 0;
            $return['info']   = $Upload->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }
}