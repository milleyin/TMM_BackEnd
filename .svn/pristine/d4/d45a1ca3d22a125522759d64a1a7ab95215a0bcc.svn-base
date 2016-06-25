<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-12-21 12:37:55 */
class AccountLogController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='AccountLog';
	
	/**
	 * 列表页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;	
		
		$criteria->addCondition('centre_status!=:centre_status AND funds_type !=:funds_type OR (funds_type=:funds_type AND log_status !=:log_status)');	
		//提现冻结
		$criteria->params[':funds_type'] = AccountLog::funds_type_pending_cash_frozen; 
		//处理成功
		$criteria->params[':log_status'] =AccountLog::log_status_success;
		//记录
		$criteria->params[':centre_status'] = AccountLog::centre_status_record;		
		$criteria->addColumnCondition(array(
			'account_id'=>Yii::app()->api->id,
			'money_type'=>AccountLog::money_type_rmb,				//人民币
			'account_type'=>Account::user,											//账户类型 用户
			'status'=>AccountLog::status_normal,								//记录有效的
		));
		$criteria->order='add_time desc,up_time desc';
		$count = AccountLog::model()->count($criteria);
			
		$return=array();
		//分页设置
		$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['account_log'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$models = AccountLog::model()->findAll($criteria);
		//分页数据
		$return['list_data']=$this->list_data($models, Yii::app()->params['app_api_domain']);
		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='小觅已经很努力了！';
		}
		$this->send($return);
	}
	
	/**
	 * 列表数据处理
	 * @param unknown $models
	 * @return multitype:multitype:string NULL
	 */
	public function list_data($models,$domain)
	{
		$return=array();
		foreach ($models as $model)
		{
			$return[]=array(
					'name'=>$model->funds_type_name,		//类型名称
					'value'=>$model->funds_type,					//类型值
					'account_no'=>$model->account_no,		//流水单号
					'use_money'=>(
						$model->centre_status == $model::centre_status_entry ? "+" : (
							$model->centre_status == $model::centre_status_deduct  ? "-" : (
								$model->centre_status == $model::centre_status_pending ? (
										$model->log_status != $model::log_status_success ? "-":""
					): ""))).number_format($model->use_money,2),
					'add_time'=>date('Y-m-d H:i:s',$model->add_time),
					'money'=>number_format($model->after_money,2),
			);
		}
		return $return;
	}
}
