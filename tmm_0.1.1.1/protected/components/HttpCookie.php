<?php 
/**
 * 重写Cookie
 * @author Changhai Zhan
 * 全局调用
 */
class HttpCookie extends CHttpCookie
{
	/**
	 * 前缀
	 * @var unknown
	 */
	public $prefix;
	/**
	 * 前缀
	 * @var unknown
	 */
	private $_prefix;
	/**
	 * 默认设置 持续时间
	 * @var unknown
	 */
	public $duration=0;
	/**
	 * 默认设置
	 * @var unknown
	 */
	public $options=array(
		'domain'=>'',
		'expire'=>'',
		'path'=>'/',
		'secure'=>false,
		'httpOnly'=>false,
	);
	
	/**
	 * 构造函数
	 * @param string $name
	 * @param string $value
	 * @param unknown $options
	 */
	public function __construct($name='',$value='',$options=array())
	{
		parent::__construct($name, $value,$options);
	}

	/**
	 * 初始化
	 */
	public function init()
	{
		//parent::init();
		$this->configure($this->options);
	}
	
	/**
	 * 获取Cookie 前缀
	 * @return string
	 */
	public function getCookiePrefix($name)
	{
		if(! $this->_prefix)
		{
			$this->_prefix = isset(Yii::app()->controller,Yii::app()->controller->module) ? (Yii::app()->controller->module !== null ?  Yii::app()->controller->module->id : '') : '';	 		
	 		if(! $this->_prefix)
	 			$this->_prefix = $this->path;
	 		if(! $this->_prefix)
	 			$this->_prefix = $name.'_';
		}
		return $this->_prefix.'_';
	}
	
	/**
	 * 保存 Cookie 默认时间一年
	 * @param unknown $name
	 * @param unknown $value
	 * @param number $duration
	 * @param unknown $options
	 * @return CHttpCookie
	 */
	public function saveCookie($name,$value,$duration=0,$options=array())
	{
		$this->configure($options);
		if($this->duration == 0 && $duration == 0)
			$duration=time()+3600*24*365;//默认一年
		elseif($duration != 0)
			$duration=time()+$duration;
		elseif($this->duration != 0 ) 
			$duration=time()+$this->duration;
		else 
			$duration=0;
		$this->name=$this->getCookiePrefix($name).$name;
		$this->value=$value;	
		$this->expire = $duration;
		Yii::app()->request->cookies[$this->name]=$this;
		return Yii::app()->request->cookies[$this->name];
	}
		
	/**
	 * 获取Cookie
	 * @param unknown $name
	 * @return string
	 */
	public function getCookie($name)
	{
		$cookie=Yii::app()->request->getCookies();
		return isset($cookie[$this->getCookiePrefix($name).$name]->value)?$cookie[$this->getCookiePrefix($name).$name]->value:null;
	}
	
	/**
	 * 删除Cookie
	 * @param unknown $name
	 * @return boolean
	 */
	public function unsetCookie($name)
	{
		$cookie = Yii::app()->request->getCookies();
		if(isset($cookie[$this->getCookiePrefix($name).$name]->value))
		{
			unset($cookie[$this->getCookiePrefix($name).$name]);
			return true;
		}
		return false;
	}
}