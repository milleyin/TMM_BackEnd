<?php
/**
 * 运营商管理平台继承控制器
 * @author Changhai Zhan
 * @property string $Title
 */
class OperatorMainController extends WebController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '/layouts/column_right';
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
     * 控制页面框架链接
     * @var unknown
     */
    public $frame = array();
    /**
     *    导航链接
     * @var unknown
     */
    public $navbar = array();
    /**
     * 控制器的前缀
     * @var unknown
     */
    public $prefix = '';    
    /**
     * 初始化
     * zch
     * (non-PHPdoc)
     * @see CController::init()
     */
    public function  init()
    {
        parent::init();
        $this->name='田觅觅-运营商管理平台';
    }
    
    /**
     * 执行方法之前的操作
     * @see SBaseController::beforeAction()
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            //保存上上次不同的链接
            if (!in_array($this->route, Yii::app()->params['operator_not_back_url']))
            {
                if( !Yii::app()->request->isAjaxRequest && Yii::app()->request->getUrlReferrer() != Yii::app()->request->hostInfo.Yii::app()->request->getUrl())
                    Yii::app()->session['operator__update_return_url'] = Yii::app()->request->getUrlReferrer();
            }
            return true;
        }else 
            return false;
    }
    
    /**
     * 面包屑后面添加
     */
    public function reload()
    {
        $this->breadcrumbs['刷新页面'] = Yii::app()->request->getUrl();
        $this->breadcrumbs['<<-返回'] = 'javascript:history.go(-1);';
    }
    
    /**
     * 返回上上次链接 排除重复链接
     */
    public function back($url=false)
    {
        if($url)
            return Yii::app()->session['operator__update_return_url'] == '' ?
            Yii::app()->request->getUrlReferrer() : 
            Yii::app()->session['operator__update_return_url'];
        if(Yii::app()->session['operator__update_return_url']=='')
            $this->redirect(Yii::app()->request->getUrlReferrer());
        else
            $this->redirect(Yii::app()->session['operator__update_return_url']);
    }
}