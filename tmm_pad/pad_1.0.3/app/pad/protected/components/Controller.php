<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 * @property string $access
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
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
     * 过滤IP
     * @var array
     */
    public $ipFilters = array('127.0.0.1', '*');
    /**
     *  标题
     * @var string
     */
    public $title;
    /**
     * 模块名称
     * @var string
     */
    public $name;
    /**
     * 前端资源
     * @var string
     */
    private $_assets;
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName;
    /**
     * 操作得到的数据
     * @var ActiveRecord
     */
    public $_model;
    /**
     * url获取主题名称
     * @var string
     */
    public $_themeName = 'theme';
    /**
     * csrf 攻击
     * @var boolean
     */
    public $enableCsrfValidation = true;
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
     *  错误消息
     * @var string
     */
    public $errorMessage;
    /**
     * 错误代码
     * @var unknown
     */
    public $errorCode = 404;
    /**
     * 是否提示错误
     * @var unknown
     */
    public $errorThrow = true;
    /**
     * 错误状态
     * @var unknown
     */
    public $errorStatus = 200;
    /**
     * 初始化
     * (non-PHPdoc)
     * @see CController::init()
     */
    public function init()
    {
        parent::init();
        $this->enableCsrfException = function ($this, $request) {
            throw new CHttpException(200, Yii::t('yii','The CSRF token could not be verified.'), 400);
        };
        //$this->setTheme();
        $this->copyAssets();
        $this->setLastUrl();
    }
    
    /**
     * 记录 上上次的Get url 
     * @param string $url
     */
    public function setLastUrl($url = '')
    {
       if ( !Yii::app()->getRequest()->getIsPostRequest() && Yii::app()->request->getUrlReferrer() != Yii::app()->request->hostInfo . Yii::app()->request->getUrl())
           Yii::app()->user->setState('__lastUrl', Yii::app()->request->getUrlReferrer());
    }
    
    /**
     * 获取上上次的url
     */
    public function getLastUrl()
    {
        return Yii::app()->user->getState('__lastUrl', Yii::app()->request->getUrlReferrer());
    }
    
    /**
     * 进入控制器之前
     * @see CController::beforeAction()
     */
    public function beforeAction($action)
    {
       if (parent::beforeAction($action))
       {
           $this->validateRequest();
           return true;
       }
       else
           return false;
    }
    
    /**
     * 验证请求的
     * @throws CHttpException
     */
    public function validateRequest()
    {
        if ( !Helper::allowIp(Yii::app()->request->userHostAddress, $this->ipFilters) && $this->route != ltrim(Yii::app()->getErrorHandler()->errorAction, '/'))
            throw new CHttpException(403, '没有权限访问系统');
        Yii::app()->getRequest()->validateHttpsMust($this);
        Yii::app()->getRequest()->validateCrossDomain($this);
        Yii::app()->getRequest()->validateRawBody($this);
        Yii::app()->getRequest()->validateCsrfToken($this);
    }
    
    /**
     * 设置主题
     * @param string $value
     */
    public function setTheme($value = '')
    {
        if ($value == '' && isset($_GET[$this->_themeName]) && in_array($_GET[$this->_themeName], Yii::app()->themeManager->getThemeNames()))
            $value = $_GET[$this->_themeName];
        if ($value != '')
            Yii::app()->theme = $value;
    }
    
    /**
     * 重写跳转
     * (non-PHPdoc)
     * @see CController::redirect()
     */
    public function redirect($url, $terminate = true, $statusCode = 302)
    {
         if (Helper::getDataType() == 'json' || Yii::app()->getRequest()->getIsAjaxRequest())
         {
             if (is_array($url))
                 $url = CHtml::normalizeUrl($url);
            echo Helper::location($url, $this->errorMessage);
            Yii::app()->end();
         }
         else
             parent::redirect($url, $terminate, $statusCode);
    }
    
    /**
     * 主题的资源拷贝
     */
    public function copyAssets()
    {
        if (isset(Yii::app()->theme, Yii::app()->theme->name))
        {
            $src = Yii::app()->theme->basePath . '/assets';
            $dstDir = Yii::app()->theme->basePath . '/../../../web/assets/themes/' . Yii::app()->theme->name;
        }
        else
        {
            $src = Yii::app()->basePath . '/assets';
            $dstDir = Yii::app()->basePath . '/../web/assets';
        }
        if ( !is_dir($dstDir) && is_dir($src))
            CFileHelper::copyDirectory($src, $dstDir, array('exclude'=>array('.svn', '.gitignore'), 'level'=>-1, 'newDirMode'=>0777, 'newFileMode'=>0666,));    
    }
    
    /**
     * 获取前端资源web路径
     * @return string
     */
    public function getAssets()
    {
        if ($this->_assets == null)
        {
            $this->_assets = Yii::app()->request->baseUrl . '/assets';
            if (isset(Yii::app()->theme, Yii::app()->theme->name))
            {
                $this->_assets .= '/themes/' . Yii::app()->theme->name;
                if (isset($this->module, $this->module->id))
                    $this->_assets .= '/' . $this->module->id;
            }
            elseif (isset($this->module, $this->module->id))
                $this->_assets .= '/' . $this->module->id;
        }
        return $this->_assets;
    }
    
    /**
     * 添加css文件
     * @param unknown $file
     * @param string $position
     * Yii::app()->baseUrl.filename
     */
    public function addCss($file, $position='')
    {
        Yii::app()->getClientScript()->registerCssFile($file, $position);
    }
    
    /**
     * 添加js文件
     * @param unknown $file
     * @param string $position
     * Yii::app()->baseUrl.filename
     */
    public function addJs($file)
    {
        Yii::app()->getClientScript()->registerScriptFile($file);
    }
    
    /**
     * 添加JQ框架
     * @param string $jq
     */
    public function addJq()
    {
        Yii::app()->getClientScript()->registerCoreScript('jquery');
    }
    
    /**
     * ajax 验证 兼容 相同数据模型验证
     * @param unknown $models
     * @param unknown $id
     * @param unknown $same
     */
    public function ajaxVerify($models, $id, $sames = '')
    {
        if (isset($_POST['ajax']) && ($_POST['ajax'] == $id))
        {
            $result = array();
            if ( !is_array($models))
                $models = array($models);
            if ( !is_array($sames))
                $sames = array($sames);
            $tabularModels = array();
            $newModels = array();
            foreach ($models as $model)
            {
                $name = CHtml::modelName($model);
                if (in_array($name, $sames))
                    $tabularModels[] = $model;
                else
                    $newModels[] = $model;
            }
            $result = array_merge(json_decode(CActiveForm::validate($newModels), true), json_decode(CActiveForm::validateTabular($tabularModels), true));
            echo function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
            Yii::app()->end();
        }
    }
    
    /**
     * 加载数据初始化
     * @throws CHttpException
     * @return string
     */
    public function loadModelInit()
    {
        if ( !(isset($this->_modelName) && $this->_modelName))
            throw new CHttpException(500, '没有设置操作数据模型');
        return $this->_modelName;
    }
    
    /**
     * 加载数据 findByPk
     * @param unknown $id
     * @param string $condition
     * @param unknown $params
     */
    public function loadModelByPk($id, $condition = '', $params = array())
    {
        $modelName = $this->loadModelInit();
        $this->_model = $modelName::model()->findByPk($id, $condition, $params);
        $this->loadModelError();
        return $this->_model;
    }
    
    /**
     * 加载数据 find
     * @param string $condition
     * @param unknown $params
     */
    public function loadModel($condition = '', $params = array())
    {
        $modelName = $this->loadModelInit();
        $this->_model = $modelName::model()->find($condition, $params);
        $this->loadModelError();
        return $this->_model;
    }
    
    /**
     * 加载数据 findAll
     * @param string $condition
     * @param unknown $params
     */
    public function loadModelAll($condition = '', $params = array())
    {
        $modelName = $this->loadModelInit();
        $this->_model = $modelName::model()->findAll($condition, $params);
        $this->loadModelError();
        return $this->_model;
    }
    
    /**
     * 加载数据 报错
     * @throws CHttpException
     * @return ActiveRecord
     */
    public function loadModelError()
    {
        if ( !$this->_model && $this->errorThrow) {
            if ( !$this->errorMessage)
                $this->errorMessage = '没有找到相关的数据';
            $this->returnMessage();
        }
        return $this->_model;
    }
    
    /**
     *  返回提示信息
     * @param string|array $message array('location'=>$this->getLastUrl(), 'result'=>1, 'message'=>'success', 'time' =>8)
     * @param string $action
     * @param string $paramsName
     * @throws CHttpException
     */
    public function returnMessage($message ='', $action = 'error/index', $paramsName = 'errorMessage')
    {
        if ($message == '') {
            $message = array(
                'code' => $this->errorStatus,
                'errorCode' => $this->errorCode,
                'message' => $this->errorMessage,
                'unix' => time(),
            );
        } elseif (is_array($message)) {
            if ( !isset($message['errorCode']))
                $message['errorCode'] = $this->errorCode;
            if ( !isset($message['code']))
                $message['code'] = $this->errorStatus;
            if ( !isset($message['message']))
                $message['message'] = $this->errorMessage;
            $message['unix'] =  time();
        } else {
            $message = array(
                'code' => $this->errorStatus,
                'errorCode' => $this->errorCode,
                'message' => $message,
                'unix' => time(),
            );
        }
        if ($message['code'] == 200) {
            Yii::app()->params[$paramsName] = Yii::app()->getSecurityManager()->hashData(serialize($message));
            $this->forward($action);
        } else {
            throw new CHttpException($message['code'], json_encode($message) , $message['errorCode']);
        }
    }
    
    /**
     * 获取消息
     * @param string $paramsName
     * @return boolean
     */
    public function getMessage($paramsName = 'errorMessage')
    {
        if (isset(Yii::app()->params[$paramsName]) && Yii::app()->params[$paramsName] && (($data = Yii::app()->getSecurityManager()->validateData(Yii::app()->params[$paramsName])) !== false)) {
            return @unserialize($data);
        }
        return false;
    }
}