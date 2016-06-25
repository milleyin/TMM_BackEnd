<?php
/**
 * 用户模块主入口
 * Class UserModule
 *
 * @author Moore Mo
 */
class UserModule extends WebModule
{
    /**
     * 微信id
     * @var
     */
    public $openid;
    /**
     * 微信用户信息
     * @var array
     */
    public $wxUserInfo = array();

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        Yii::app()->theme = 'html';
        // import the module-level models and components
        $this->setImport(array(
            $this->getId() . '.models.*',
            $this->getId() . '.components.*',
            'ext.Weixin.*',
        ));
        Yii::app()->setComponents(array(
            'errorHandler'=>array(
                'class'=>'CErrorHandler',
                'errorAction'=>YII_DEBUG ? null : '/' . $this->getId() . '/error/index',
            ),
            'user'=>array(
                'class'=>'UserWebUser',
                'stateKeyPrefix'=>$this->getId(),                                    //后台session前缀
                'loginUrl'=>array('/' . $this->getId() . '/login/index'),
                'loginRequiredAjaxResponse'=>array('/' . $this->getId() . '/login/index'),
                'authTimeout'=>1440,
//                 'allowAutoLogin'=>true,                                                    //自动登录
//                 'autoRenewCookie'=>true,                                            //自动登录的时候刷新Cooike的时间
//                 'identityCookie'=>array('httpOnly'=>true),                    //防止JS修改Cookie
            ),
             'session' => array(
                 'class'=>'CHttpSession',
                 'timeout' =>1440,
                 'cookieParams'=>array('httponly'=>true),                 //防止JS修改Cookie
                 'cookieMode'=>'only',                                                //保存SessionID 仅仅用Cookie的方式保存
             ),
        ), false);

        // 微信用户信息
        $this->openid = \Weixin::getOpenid();
        if ($this->openid)
            $this->wxUserInfo = \Weixin::getWxUserInfo($this->openid);
    }

    /**
     * 权限，登录判断
     * @param CController $controller
     * @param CAction $action
     * @return bool
     * @throws CHttpException
     */
    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action))
        {
            Yii::app()->homeUrl = CHtml::normalizeUrl(array($this->defaultController . '/' . $controller->defaultAction));
            $route = $controller->id . '/' . $action->id;
            if ( !Helper::allowIp(Yii::app()->request->userHostAddress, $this->ipFilters) && $route !== 'error/index')
                throw new CHttpException(403, "没有权限访问系统。");
             $publicPages = array(
                 'error/index',
                 'login/index',
             );
            if (Yii::app()->user->isGuest && !in_array($route, $publicPages)) {
                if (isset($this->wxUserInfo['openid']) && $this->wxUserInfo['subscribe'] == 1) {
                    // 创建用户
                    $model = new User;
                    if ($model->createUser($this->openid, $this->wxUserInfo['nickname'])) {
                        // 登录
                        $userLoginFormModel = new UserLoginForm();
                        $userLoginFormModel->scenario = 'login';
                        $userLoginFormModel->attributes = array(
                            'openid' => $this->openid,
                            'name' => $this->wxUserInfo['nickname'],
                        );
                        // 登录成功
                        if ($userLoginFormModel->validate() && $userLoginFormModel->login()) {
                            return true;
                        }
                    }
                }
                // 去关注，登录
                Yii::app()->user->loginRequired();
            } else if (!in_array($route, $publicPages) && (!isset($this->wxUserInfo['openid']) || $this->wxUserInfo['subscribe'] != 1)) {
                // 已经登录，但是未关注，踢出登录
                Yii::app()->user->logout(false);
                Yii::app()->user->loginRequired();
            } else if (!in_array($route, $publicPages)) {
                $criteria = new CDbCriteria;
                $criteria->with = array(
                    'User_Role',
                );
                $criteria->addColumnCondition(array(
                    't.status'=>User::_STATUS_NORMAL,
                    'User_Role.status'=>Role::_STATUS_NORMAL,
                    'openid'=>$this->openid,
                ));
                if ( !User::model()->findByPk(Yii::app()->user->id, $criteria)) {
                    // 已经登录，但是禁用 或 openid 改变，踢出登录
                    Yii::app()->user->logout(false);
                    Yii::app()->user->loginRequired();
                }
            }
            return true;
        }
        else
            return false;
    }
}