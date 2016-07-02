<?php
/**
 * 
 * @author Changhai Zhan
 *
 */
class WebUser extends CWebUser
{
    /**
     * 返回前端
     * @var unknown
     */
    public $page = 'login';
    /**
     * 消息
     * @var unknown
     */
    public $content = array('result'=>0, 'message'=>'fail');
    
    /**
     * 初始化
     * (non-PHPdoc)
     * @see CWebUser::init()
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * 
     * (non-PHPdoc)
     * @see CWebUser::loginRequired()
     */
    public function loginRequired()
    {
        $app=Yii::app();
        $request=$app->getRequest();
        if ( !(Helper::getDataType() == 'json' || $request->getIsAjaxRequest()))
        {
            $this->setReturnUrl($request->getUrl());
            if (($url=$this->loginUrl) !== null)
            {
                if (is_array($url))
                {
                    $route = isset($url[0]) ? $url[0] : $app->defaultController;
                    $url = $app->createUrl($route, array_splice($url, 1));
                }
                $request->redirect($url);
            }
        }
        elseif(isset($this->loginRequiredAjaxResponse))
        {
            echo Helper::location($this->loginRequiredAjaxResponse, $this->content, $this->page);
            Yii::app()->end();
        }
        throw new CHttpException(403,Yii::t('yii','Login Required'));
    }
    
    protected function beforeLogin($id, $states, $fromCookie)
    {
        if (parent::beforeLogin($id, $states, $fromCookie))
            return true;
        return false;
    }
    
    protected function afterLogin($fromCookie)
    {
        parent::afterLogin($fromCookie);
    }
}