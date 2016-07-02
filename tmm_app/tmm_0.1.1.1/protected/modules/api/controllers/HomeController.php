<?php

class HomeController extends ApiController
{
	/**
	 * 设置当前操作数据模型
	 * @var string
	 */
	public $_class_model='User';
	
	public function cash($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'提现申请','name'=>'提现申请','address'=>'');
			$return=Account::moneyPendingCashFrozenRmb(
					$money,
					array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
	
	public function pay($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'支付订单','name'=>'支付订单','address'=>'');
			$return=Account::moneyDeductOrderPayRmb(
					$money,
					array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
		
	public function itemspast($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'过期消费','name'=>'过期消费','address'=>'');
			$return=Account::moneyRecordOrderItemsPastRmb(
					$money,
					array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
	
	public function itemsconsume($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'扫描消费','name'=>'扫描消费','address'=>'');
			$return=Account::moneyRecordOrderItemsConsumeRmb(
					$money,
					array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
	
	public function onlinepay($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'在线支付','name'=>'在线支付','address'=>'');
			$return=Account::moneyRecordAlipayRmb(
					$money,
					array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
	
	public function cashsuccess($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'提现成功','name'=>'提现成功','address'=>'');
			$return=Account::moneyDeductCashSuccessRmb(
					$money,
					array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
	
	public function cashfail($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'提现失败','name'=>'提现失败','address'=>'');
			$return=Account::moneyEntryCashFailRmb(
					$money,
					array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
	
	public function recharge($money,$id)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$info=array('info_id'=>$id,'info'=>'在线充值','name'=>'在线充值','address'=>'');
			$return=Account::moneyEntryRechargeRmb(
					$money,
					array('account_id'=>$id,'account_type'=>Account::user),
					$info
			);
			if(! $return)
				throw new Exception("添加项目(吃)主要记录错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			print_r(array(
				Account::$create_error,
				AccountLog::$create_error,
			));
		}
		if(isset($return) && $return)
			echo 1;
	}
	
	/**
	 * 获取用户信息
	 */
	public function actionIndex()
	{
		if(!Yii::app()->api->isGuest)
		{
			$model=$this->loadModel(Yii::app()->api->id);
			$return = array();
			$datas=array(
				'nickname',
				'is_organizer',
				'audit',
				'gender',
				'count',
				'last_ip',
			);
			foreach($datas as $data)
				$return[$data]=$model->$data;
			$return['add_time'] = Yii::app()->format->datetime($model->add_time);
			$return['last_time'] = Yii::app()->format->datetime($model->last_time);
			$this->send($return);
		}else
			$this->send(array(),Yii::app()->params['API_LOGIN_REQUIRED']);
	}
	
	/*
	 public function actionR()
	 {
		//充值
		//$this->recharge(1000000,Yii::app()->api->id);
		//die;
		//提现
		//$this->cash(2000,2);
		//提现成功
		//$this->cashsuccess('1000',1);
		//提现失败
		//$this->cashfail(2000,2);
		//支付订单
		//$this->pay(1000,2);
		//在线支付
		//$this->onlinepay(1000);
		//扫描消费
		//$this->itemsconsume(100,2);
		//过期消费
		//$this->itemspast(24.28);
		//die;
		$models = User::model()->findAll();
		foreach ($models as $model)
			$this->recharge(rand(1000000.00,99999999) / 100,$model->id);
	}
	*/
}