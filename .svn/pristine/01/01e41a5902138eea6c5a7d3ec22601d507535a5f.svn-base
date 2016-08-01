<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class CallbackModulesController extends ModulesController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '/layouts/column2';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    /**
     * csrf 攻击
     * @var boolean
     */
    public $enableCsrfValidation = false;
    /**
     * csrf 回调函数
     * @var unknown
     */
    public $enableCsrfException;
    /**
     * 能否 跨域
     * @var boolean
     */
    public $enableCrossValidation = false;
    /**
     * 跨域 域名
     * @var string
     */
    public $crossDomainName = '*';
    /**
     * 是否强制 https
     * @var boolean
     */
    public $enableHttpsValidation = false;
    /**
     * 是否RawBody获取数据
     * @var boolean | array
     */
    public $enableRawBodyValidation = false;
    /**
     * 初始化
     * (non-PHPdoc)
     * @see ModulesController::init()
     */
    public function init()
    {
        parent::init();
        Yii::app()->homeUrl = CHtml::normalizeUrl(array($this->module->defaultController . '/' . $this->defaultAction));
        $this->name = '外部回调';
        $this->title = $this->pageTitle . ' - ' . $this->name;
    }
    
    /**
     * 执行之前的方法
     * @see Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ( !Helper::allowIp(Yii::app()->request->userHostAddress, $this->ipFilters) && $this->route !== 'error/index') {
                throw new CHttpException(403, '无权限');
            }
            return true;
        } else {
            return false;
        }
    }
}