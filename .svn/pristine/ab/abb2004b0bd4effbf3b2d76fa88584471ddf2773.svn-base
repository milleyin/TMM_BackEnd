<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FrontController extends RbacController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/json';
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
     *  运行跨域
     * @var boolean
     */
    public $enableCrossValidation = true;
    
    /**
     * 初始化
     * @see RbacController::init()
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 
     * @param unknown $controller
     * @param unknown $action
     * @throws CHttpException
     * @return boolean
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            if ( !Helper::allowIp(Yii::app()->request->userHostAddress, $this->ipFilters) && $this->route !== 'error/index')
                throw new CHttpException(403, "没有权限访问系统。");
            $publicPages = array(
                    'error/index',
                    'login/index',
            );
            if (Yii::app()->user->isGuest && !in_array($this->route, $publicPages)) {
                Yii::app()->user->loginRequired();
            } elseif( !in_array($this->route,$publicPages)) {
                $criteria = new CDbCriteria;
                $criteria->select = 'id';
                $criteria->with = array(
                        'Pad_Store'=>array('select'=>''),
                        'Pad_Role'=>array('select'=>''),
                );
                $criteria->addColumnCondition(array(
                    '`Pad_Store`.`id`'=>Yii::app()->user->id,
                    '`Pad_Store`.`status`'=>Store::_STATUS_NORMAL,
                    '`Pad_Role`.`status`'=>Role::_STATUS_NORMAL,
                    '`t`.`status`'=>Pad::_STATUS_NORMAL,
                ));
                if ( !Pad::model()->findByPk(Yii::app()->user->padId, $criteria))
                {
                    Yii::app()->user->logout(false);
                    Yii::app()->user->loginRequired();
                }    
                return true;
            }
            return true;
        }
        else
            return false;
    }
}