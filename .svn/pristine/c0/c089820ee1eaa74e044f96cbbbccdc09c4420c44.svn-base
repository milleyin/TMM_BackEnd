<?php
namespace Admin\Model;
use Think\Model;
use Think\Upload;

/**
 * 横幅模型
 * @author Moore Mo
 */

class BannerModel extends Model {

    /**
     * 字段检测
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('url', 'require', '链接地址不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('path', 'require', '请先上传横幅图片', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成
     */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_BOTH),
    );

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting, $driver = 'Local', $config = null){
        /* 上传文件 */
        $setting['callback'] = array($this, 'isFile');
        $setting['removeTrash'] = array($this, 'removeTrash');
        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);

        if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                /* 已经存在文件记录 */
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }

                /* 记录文件信息 */
                $value['path'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename'];	//在模板里的url路径
//                if($this->create($value) && ($id = $this->add())){
//                    $value['id'] = $id;
//                } else {
//                    //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
//                    unset($info[$key]);
//                }
            }
            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param $file  文件上传数组
     * @return mixed 文件信息， false - 不存在该文件
     * @throws \Exception
     *
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
        $map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
        return $this->field(true)->where($map)->find();
    }

    /**
     * 清除数据库存在但本地不存在的数据
     * @param $data
     */
    public function removeTrash($data){
        $this->where(array('id'=>$data['id'],))->delete();
    }
}
