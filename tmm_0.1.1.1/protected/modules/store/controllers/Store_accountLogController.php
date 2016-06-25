<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-12-21 16:45:47 */
class Store_accountLogController extends StoreMainController
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
			
		$criteria->addCondition('centre_status != :centre_status AND funds_type != :funds_type OR ( funds_type = :funds_type AND log_status != :log_status )');
		//提现冻结
		$criteria->params[':funds_type'] = AccountLog::funds_type_pending_cash_frozen;
		//处理成功
		$criteria->params[':log_status'] = AccountLog::log_status_success;
		//记录
		$criteria->params[':centre_status'] = AccountLog::centre_status_record;
		//默认
		$criteria->addColumnCondition(array(
				'account_id'=>Yii::app()->store->id,												//角色id
				'money_type'=>AccountLog::money_type_rmb,							//人民币
				'account_type'=>Account::store,													//账户类型 商家
				'status'=>AccountLog::status_normal,											//记录有效的
		));
		$criteria->order='add_time desc,up_time desc';
		//统计条数
		$count = AccountLog::model()->count($criteria);
		//返回值
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
	
	/**
	 * 	资金统计
	 * @param number $type entry 收益 cash提现
	 * @param string $time 		"天"=>day  "月"=>month
	 */
	public function actionCount($time='day')
	{
		$times = array('detail','day','month');	//时间
		
		if(in_array($time,$times))
		{
			$store = StoreUser::model()->findByPk(Yii::app()->store->id,'status=1');
			if($store)
				$store_id = $store->p_id == 0 ? Yii::app()->store->id : $store->p_id;
			else 
				$store_id = '';
			$criteria = new CDbCriteria;
			$criteria->with = array(
				'AccountLog_OrderItems',
			);
			//权限
			$criteria->addColumnCondition(array(
				'`AccountLog_OrderItems`.`store_id`' => Yii::app()->store->id,
				'`AccountLog_OrderItems`.`manager_id`' => Yii::app()->store->id,
			),'OR');
			
			$criteria->addColumnCondition(array(
					'`t`.`status`' => AccountLog::status_normal,															//正常
					'`t`.`account_type`' => Account::store,																	//角色类型
					'`t`.`account_id`' => $store_id,																				//角色id
					'`t`.`money_type`' => AccountLog::money_type_rmb,											//钱的类型
					'`t`.`funds_type`' => AccountLog::funds_type_entry_order_income,						//订单收益
			));
			//时间
			if($time == 'day')
			{	
				$criteria->select = '`t`.*,sum(`use_money`) as money_entry_rmb_count';
				$criteria->group = 'FROM_UNIXTIME(`t`.`add_time`, "%Y%m%d")';
				$criteria->order	=	'`t`.`add_time` desc';
			}
			elseif($time == 'month')
			{
				$criteria->select = '`t`.*,sum(`use_money`) as money_entry_rmb_count';
				$criteria->group = 'FROM_UNIXTIME(`t`.`add_time`, "%Y%m")';
				$criteria->order	=	'`t`.`add_time` desc';
			}
			elseif($time == 'detail')
			{
				$criteria->order	=	'`t`.`add_time` desc';
			}
			$count = AccountLog::model()->count($criteria);
			
			$return=array();
			//分页设置
			$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['account_count'], Yii::app()->params['app_api_domain']);
			//根据条件查询
			$models = AccountLog::model()->findAll($criteria);
			//分页数据
			$return['list_data']=$this->count_list($models, Yii::app()->params['app_api_domain'],$time);
			//统计金额
			$return['money_count'] = '0.00';
			$return['format_money_count'] = '0.00';
			if(empty($return['list_data']))
			{
				$return['list_data'] = array();
				$return['null'] = '小觅已经很努力了！';
			}
			elseif($time == 'detail')
			{
				foreach ($return['list_data'] as $data)
					$return['money_count'] += $data['money'];
				
				$return['format_money_count'] = number_format($return['money_count'],2);
			}
			elseif($time == 'day')
			{
				foreach ($return['list_data'] as $datas)
				{
					foreach ($datas['value'] as $data)
						$return['money_count'] += $data['money'];
				}
				$return['format_money_count'] = number_format($return['money_count'],2);
			}
			$this->send($return);
		}	
		$this->send_error(DATA_NULL);
	}
	
	/**
	 * 统计列表
	 * @param unknown $models
	 * @param unknown $domain
	 * @param string $time
	 * @return multitype:multitype:string
	 */
	public function count_list($models, $domain,$time='day')
	{
		$return=array();
		foreach ($models as $model)
		{
			if($time == 'detail')
			{	
				$return[]=array(
						'name'=>$model->funds_type_name,		//类型名称
						'value'=>$model->funds_type,					//类型值
						'account_no'=>$model->account_no,		//流水单号						
						'add_time' => date('Y-m-d H:i:s', $model->add_time),
						'money' => $model->use_money,
						'format_money'=>number_format($model->money_entry_rmb_count,2),
						'items'=>array(
							'name'=>$model->AccountLog_OrderItems->items_name,
							'value'=>$model->AccountLog_OrderItems->items_id,
							'link'=>$domain.Yii::app()->createUrl('/store/store_items/view',array('id'=>$model->AccountLog_OrderItems->items_id)),
							'total'=>$model->AccountLog_OrderItems->total,
							'format_total'=>number_format($model->AccountLog_OrderItems->total,2),
							'employ_time'=>$model->AccountLog_OrderItems->employ_time == 0 ? '过期消费' : date('Y-m-d H:i:s',$model->AccountLog_OrderItems->employ_time),
							'push'=>$model->AccountLog_OrderItems->items_push_store / 100,
							'classliy'=>array(
								'name' => $model->AccountLog_OrderItems->items_c_id,
								'value' => $model->AccountLog_OrderItems->items_c_name,
							),
						),
				);
			}
			elseif($time == 'day')
			{
				$group = date('Y年m月',$model->add_time);
				$return[$group]['name'] = $group;
				$return[$group]['value'][] = array(
						'group'=>$group,
						'add_time' => date('Y-m-d', $model->add_time),
						'money' => $this->money_floor($model->money_entry_rmb_count),
						'format_money'=>number_format($this->money_floor($model->money_entry_rmb_count),2),
				);
			}
			else
			{
				$return[]=array(
						'add_time' => date('Y-m', $model->add_time),
						'money' => $this->money_floor($model->money_entry_rmb_count),
						'format_money'=>number_format($this->money_floor($model->money_entry_rmb_count),2),
				);
			}
		}
		if($time == 'day')
			$return = array_values($return);
		return $return;
	}
}
