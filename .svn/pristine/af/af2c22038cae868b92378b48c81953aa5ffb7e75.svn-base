<?php
namespace Common\Model;
use Think\Model;

/**
 * 管理员Admin模型类
 * Class AdminModel
 * @package Common\Model
 *
 * @author Moor Mo
 */
class AdminModel extends Model {
    /**
     * 用户登录
     * @param integer $loginName 登录名
     * @param string $password 用户密码
     * @param boolean or integer $remember 记住帐号
     * @return object $back status属性(0:用户名错误 1:登录成功 2:密码错误 3:账号已禁用)
     */

    public function login($loginName, $password) {
        $back = new \stdClass();

        // 验证登录名
        $map = array();
        $map['login_name'] = $loginName;
        $userInfo = $this->where($map)->find();
        if (!is_array($userInfo)) {
            $back->status = 0;
            return $back;
        }

        // 验证密码
        if (md5('TMM'.$password) != $userInfo['password']) {
            $back->status = 2;
            return $back;
        }

        // 验证用户状态
        if ($userInfo['status'] == 0) {
            $back->status = 3;
            return $back;
        }

        //更新最后一次登录时间和IP
        $data = array();
        $data['last_login'] = time();
        $data['last_ip'] = get_client_ip();
        $this->where(array('id' => $userInfo['id']))->save($data);

        //保存SESSION
        session('uid', $userInfo['id']);
        session('loginName', $userInfo['login_name']);
        $back->status = 1;
        return $back;
    }

    /**
     * 用户登出
     */

    public function logout() {
        //删除SESSION
        session('uid', null);
        session('loginName', null);
    }

    /**
     * 用户注册
     * @param array $user 用户信息
     * @return object $back status属性(0:用户名已存在 1:注册成功 2:注册失败);uid属性($uid:用户ID)
     */

    public function register($user) {
        $back = new \stdClass();

        $uid = null;

        $loginName = $this->where(array('login_name' => $user['login_name']))->getField('login_name');
        if ($loginName) {
            $back->status = 0;
            return $back;
        }

        $data = array();
        $data['login_name'] = $user['login_name'];
        $data['password'] = md5($user['password']);
        $data['mobile'] = $user['mobile'];
        $data['email'] = $user['email'];
        $data['reg_time'] = time();
        $data['last_login'] = time();
        $data['last_ip'] = get_client_ip();
        $uid = $this->data($data)->add();
        if (!$uid) {
            $back->status = 2;
            return $back;
        }

        $back->uid = $uid;
        $back->status = 1;
        return $back;
    }
}