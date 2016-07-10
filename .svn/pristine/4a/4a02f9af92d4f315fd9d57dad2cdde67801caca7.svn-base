<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminModulesController extends ModulesController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '/layouts/column1';
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
     * 初始化
     * (non-PHPdoc)
     * @see ModulesController::init()
     */
    public function init()
    {
        parent::init();
        $this->name = '展示屏管理后台（工作人员）';
        Yii::app()->homeUrl = CHtml::normalizeUrl(array($this->module->defaultController . '/' . $this->defaultAction));
        $this->title = $this->pageTitle . ' - ' . $this->name;
        $this->errorCode = 302;
    }
}