<?php
namespace Sakura\Model;
use Think\Model;

class UserModel extends Model {

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('mobile', 'require', '联系人手机号码必须填写'),
        array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确！', '0', 'regex',1),
    );

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('create_time', 'time', 1, 'function'),
    );

}