<?php 
/**
 * 
 * @author Changhai Zhan
 *
 */
class WebModule extends CWebModule
{
    /**
     * ip 过滤
     * @var unknown
     */
    public $ipFilters = array('127.0.0.1', '::1');
        
    /**
     * 初始化
     * (non-PHPdoc)
     * @see CModule::init()
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * (non-PHPdoc)
     * @see CWebModule::beforeControllerAction()
     */
    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action))
            return true;
        else
            return false;
    }
    
    /**
     * (non-PHPdoc)
     * @see CWebModule::afterControllerAction()
     */
    public function afterControllerAction($controller, $action)
    {
        if (parent::afterControllerAction($controller, $action))
            return true;
        else
            return false;
    }
}