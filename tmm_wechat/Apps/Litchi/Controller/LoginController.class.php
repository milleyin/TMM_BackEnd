<?php
namespace Litchi\Controller;
use Think\Controller;

/**
 * 登录控制器
 * Class LoginController
 * @package Litchi\Controller
 *
 * @author Moore Mo
 */
class LoginController extends Controller {

    public function login() {
        I('session.uid') && $this->redirect('Back/ticket');

        if (IS_AJAX) {
            $adminModel = D('Admin');
            $back = new \stdClass();

            $loginName = I('post.loginName');
            $password = I('post.password');

            $back = $adminModel->login($loginName, $password);
            if (in_array($back->status, array(0, 2))) {
                $back->prompt = '登陆账号或密码错误';
                $this->ajaxReturn($back);

            } elseif ($back->status == 3) {
                $back->prompt = '账号已禁用';
                $this->ajaxReturn($back);
            } else {
                $back->url =  U('Back/ticket', '', '', true);
                $this->ajaxReturn($back);
            }
        }

        $this->assign('title', '登录');
        $this->display('login');
    }

    /**
     * 用户登出
     */
    public function logout() {
        $adminModel = D('Admin');
        $adminModel->logout();
        $this->redirect('Login/login');
    }
}