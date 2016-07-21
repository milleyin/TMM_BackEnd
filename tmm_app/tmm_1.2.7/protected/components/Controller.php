<?php
/**
 *    系统核心继承控制器
 * @author Changhai Zhan
  * @property Controller 
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout;
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    /**
     * 是否刷新数据
     * @var unknown
     */
    public $isRefreshToken = true;
    /**
     * csrf 攻击
     * @var boolean
     */
    public $enableCsrfValidation = true;
    /**
     * 错误执行的方法
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
     * zch 
     * (non-PHPdoc)
     * @see CController::init()
     */
    public function  init()
    {
        parent::init();
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
     */
    public function validateRequest()
    {
        Yii::app()->getRequest()->validateHttpsMust($this);
        Yii::app()->getRequest()->validateCrossDomain($this);
        Yii::app()->getRequest()->validateRawBody($this);
        Yii::app()->getRequest()->validateCsrfToken($this);
    }

    /**
     * 添加错误日志
     * @param  string  $content  操作详情
     * @param  integer $manage_who 管理员类型
     * @param  integer $manage_type 操作类型
     * @param  integer $error_type  错误类型
     * @param  string  $method          操作方法
     * $this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::create,ErrorLog::rollback,__METHOD__);
     */
    public function error_log($content,$manage_who,$manage_type,$error_type='-1',$method='')
    {
        $modules = ErrorLog::$_manage_modules[$manage_who];
        $model=new ErrorLog;
        $model->error_id=Yii::app()->$modules->id;
        $model->error_type=$error_type;
        $model->manage_who=$manage_who;
        $model->manage_type=$manage_type;
        $model->manage_method=$method;
        $model->info=$content;
        $model->url=Yii::app()->getRequest()->hostInfo . Yii::app()->getRequest()->getUrl();//$this->route;
        $model->ip=Yii::app()->getRequest()->userHostAddress;
        $model->save(false);
        return true;
    }

    /**
     * post 密钥验证
     * @return array
     */
    public function csrf()
    {
        if(Yii::app()->getRequest()->enableCsrfValidation) 
        {
            $csrfTokenName = Yii::app()->getRequest()->csrfTokenName;
            $csrfToken = Yii::app()->getRequest()->csrfToken;
            return array($csrfTokenName=>$csrfToken, 'name'=>$csrfTokenName, 'value'=>$csrfToken);
        }
        else
            return array();
    }
    
    /**
     * 点的列表页显示 （选择点 创建线）
     * @param unknown $path
     * @param string $type
     * @param string $alt
     * @param unknown $params
     * @return string
     */
    public function dot_list_show_img($path,$type='',$alt='',$params=array())
    {
        if($type=='')
            $type=Yii::app()->params['litimg_pc'];
        $litimg_path=$this->litimg_path($path,$type);
        if( !$this->file_exists_uploads($litimg_path))
        {
            $litimg_path = $path;
            $params['width']=Yii::app()->params['litimg_confing'][$type]['width'];
            $params['height']=Yii::app()->params['litimg_confing'][$type]['height'];
        }
        if($alt=='')
            $alt=$litimg_path;
        return CHtml::image($litimg_path,$litimg_path,$params);
    }

    /**
     * 格式化输出
     */
    public function p_r($val)
    {
        echo "<pre>";
        print_r($val);
    }

    /**
     * 替换图片链接
     */
    public function admin_img_replace($content)
    {
        return  str_replace('<img alt="" src="/', '<img alt="" src="'.Yii::app()->params['admin_img_domain'].'/', $content);
    }
    
    /**
     * 格式化距离
     * @param unknown $distance
     * @return string
     */
    public function FormatDistance($distance)
    {
        $distance = (int)$distance;
        if ($distance >= 1000)
            $distance = round($distance/1000,2).'km';
        else
            $distance = $distance.'m';
        return $distance;
    }
    
    /**
     * 刷新Token
     */
    public function refreshToken()
    {
        if ($this->isRefreshToken)
            Yii::app()->getRequest()->unsetCsrfToken();
    }
    
    /**
     * 钱的算法
     * @param unknown $money
     * @param number $int
     * @param string $type
     * @return string
     */
    public function money_floor($money,$int=2)
    {
        return $this->floorAdd($money, '0.00', $int);
    }
    
    /**
     * 加
     * @param unknown $number1
     * @param unknown $number2
     * @param number $int
     * @return string
     */
    public function floorAdd($number1, $number2, $int=2)
    {
        return bcadd($number1, $number2, $int);
    }
    
    /**
     * 计算多个
     * @param unknown $array
     * @param number $int
     * @return Ambigous <number, string>
     */
    public function floorAddArray($array, $int=2)
    {
        $number2 = 0;
        foreach ($array as $number1)
            $number2 = $this->floorAdd($number2, $number1, $int);
        return $number2;
    }
    
    /**
     * 减
     * @param unknown $number1
     * @param unknown $number2
     * @param number $int
     * @return string
     */
     public function floorSub($number1, $number2, $int=2)
    {
        return bcsub($number1, $number2, $int);
    }
    
    /**
     * 乘
     * @param unknown $number1
     * @param unknown $number2
     * @param number $int
     * @return string
     */
    public function floorMul($number1, $number2, $int=2)
    {
        return bcmul($number1, $number2, $int);
    }
    
    /**
     * 除
     * @param unknown $number1
     * @param unknown $number2
     * @param number $int
     * @return string
     */
    public function floorDiv($number1, $number2, $int=2)
    {
        return bcdiv($number1, $number2, $int);
    }
    
    /**
     * 比较 大小
     * @param unknown $number1
     * @param unknown $number2
     * @param number $int
     * @return number
     */
    public function floorComp($number1, $number2, $int=2)
    {
        return bccomp($number1, $number2, $int);
    }
    
    /**
     * 格式化 钱 显示
     * @param $str
     * @param int $num
     * @return string
     */
    public function number_format($money,$int=2)
    {
        return number_format($money,$int);
    }
    
    /**
     * 验证是否属数字
     * @param unknown $int
     * @return number
     */
    public function isNumeric($int)
    {
        return preg_match('/^\d+$/i', $int);
    }
    
    /**
     * 公司名
     * @return string
     */
    public function powered()
    {
        return 'Shenzhen Tian Mimi Mdt InfoTech Ltd.';
    }
    
    /**
     * 返回重写的地址
     * @param unknown $path
     * @return string
     */
    public function rewritePath($path)
    {
        return Yii::app()->getRequest()->baseUrl . ltrim($path, '.');
    }
    
    /**
     * 隐藏关键东西
     * @param unknown $str
     * @param number $start
     * @param number $length
     * @param string $replacement
     * @return mixed
     */
    public function setHideKey($str, $start=3, $length=4, $replacement='*')
    {
        for($i=1; $i<$length; $i++)
            $replacement .= '*';
        return substr_replace($str, $replacement, $start, $length);
    }
    
    /**
     * 获取图片绝对地址
     * @param unknown $uploads
     * @return string
     */
    public function get_file_uploads($uploads)
    {
        return $uploads ? Yii::app()->basePath . '/..' . ltrim($uploads, '.') : '';
    }
    
    /**
     * Yii::app()->controller->file_exists_uploads(
     * $this->file_exists_uploads(
     * @param unknown $image
     * @return string
     */
    public function file_exists_uploads($uploads)
    {
        return $uploads && file_exists($this->get_file_uploads($uploads));
    }
}