<?php
/**
 * 活动定时任务
 * @author Changhai Zhan
 *	创建时间：2015-10-26 13:41:03
 *
 *php yiic.php CommandName ActionName --Option1=Value1 --Option2=Value2 ...
 * */
class ConsoleCommand extends CConsoleCommand
{
	/**
	 * 运行是正确的
	 * @var unknown
	 */
	const correct=0;
	/**
	 * 运行是错误的
	 * @var unknown
	 */
	const return_error=1;

	/**
	 * 时间类型
	 * 小时
	 */
	const time_hour =2;
	/**
	 * 时间类型
	 * 天
	 */
	const time_day = 1;
	/**
	 * 运行的日志
	 * @var unknown
	 */
	public $logText=array();
	
	/**
	 * 初始化
	 * @see CConsoleCommand::init()
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 * 之前
	 * @see CConsoleCommand::beforeAction()
	 */
	public function beforeAction($action, $params)
	{
		if(parent::beforeAction($action, $params))
		{
			$this->logText[]="\r\n".'[控制台开始] :' .$this->microtime_format();
			return true;
		}
		else 
			return false;
	}
	
	/**
	 * 之后
	 * @see CConsoleCommand::afterAction()
	 */
	public function afterAction($action,$params,$exitCode=0)
	{
		$exitCode=parent::afterAction($action,$params,$exitCode);
		$this->logText[]='[控制台结束] :' .$this->microtime_format();
		$this->logText[]="##############################";
		$logText=implode($this->logText, "\r\n");
		if(!is_dir(YII::app()->basePath.'/runtime/error/console'))
			mkdir(YII::app()->basePath.'/runtime/error/console', 0777, true);
		if($exitCode != 0)
			Yii::log($logText,'error',ucfirst($this->getName()).ucfirst($action));
		else			
			Yii::log($logText,'info',ucfirst($this->getName()).ucfirst($action));
		echo iconv('UTF-8', 'GB2312',strtr($logText,array('[控制台开始]'=>'[RUN]','[控制台结束]'=>'[END]')));
	}
	
	/**
	 * 格式化时间戳，精确到毫秒，x代表毫秒
	 * @param string $format
	 * @param string $time
	 * @param string $x
	 * @return mixed date('Y-m-d H:i:s'):[0-9999]
	 */
	public function microtime_format($format='Y-m-d H:i:s:x', $time='',$x='x')
	{
		if($time=='')
			$time=$this->microtime_float();
		$data=explode('.', $time);
		list($usec, $sec) =count($data)==2?$data:array($data[0],0);
		$date = date($format,$usec);
		return str_replace($x,$sec, $date);
	}
	
	/**
	 * 返回时间戳
	 * @return number  time().[0-9999]
	 */
	public function microtime_float()
	{
		list($usec, $sec) = explode(' ', microtime());
		return ((float)$usec + (float)$sec);
	}
	
	/**
	 * 执行 控制器中的方法  默认执行一天  20秒执行一次
	 * @param string $action
	 * @param number $time
	 * @param number $sleep
	 * @return string
	 * order index （表示执行 控制器order方法index ）
	 */
	public function actionTest($action='index',$time=24,$sleep=20)
	{
		$time=$time*3600;
		$old_action=$action;
		$action='action'.ucfirst($action);	
		while($time>=0)
		{			
			$this->beforeAction($old_action, array());
			$this->afterAction($old_action,array(),$this->$action());
			$this->logText=array();
			sleep($sleep);
			$time -= $sleep;
		}
		return self::correct;
	}
	
	/**
	 * 获取页数
	 * @param unknown $count
	 * @param number $pageSize
	 * @return number
	 */
	public function getPageCount($count,$pageSize=30)
	{
		return (int)(($count+$pageSize-1)/$count);
	}

	/**
	 * 
	 * @param unknown $page
	 */
	public function getPageCriteria($page,$criteria,$pageSize=30)
	{
		$criteria->limit=$pageSize;
		$criteria->offset=($page-1)*$pageSize;
		return $criteria;
	}

	/**
	 * 计算过期时间
	 */
	public function calculate_out_time($time,$type=self::time_day){
		if($type == self::time_day)
			$cal_time  = time()-($time+1)*3600*24;
		elseif($type==self::time_hour)
			$cal_time  = time()-$time*3600;
		else
			$cal_time = time();

		return $cal_time;
	}

	/**
	 * 钱的算法
	 * @param $money
	 * @param int $int
	 * @param string $type
	 * @return string
	 */
	public function money_floor($money,$int=2,$type='floor')
	{
		$array=array('ceil','floor','round');
		return sprintf("%0.2f", floor((string)($money * 100)) / 100);
	}

}