<?php
/**
 * 
 * @author Changhai Zhan
 * 列表页
 */
class ColumnShopsAction extends CAction
{
	/**
	 * 执行的方法
	 * @param string $id
	 * @param unknown $type
	 */
	public function run($id='', $type=Select::type_actives)
	{
		if ($type == Select::type_actives || $type == Select::type_nearby)
		{
			//gps 的 信息
			$getGps = Yii::app()->cookie->getCookie(Yii::app()->params['gps']);
			
			$exist_Gps = $getGps == null ? false : true;
			
			$criteria = new CDbCriteria;
			$criteria->together = true;
			//是否有计算距离定位
			$distance = false;
			if ($exist_Gps == false)
			{
				if ($id == '')
					$criteria->order = '`Select_Ad`.`sort`,`Select_Ad`.`up_time` desc,`Select_Ad`.`add_time` desc';
				else
					$criteria->order = '`t`.`sort`,`t`.`up_time` desc,`t`.`add_time` desc';
			}
			elseif ($exist_Gps == true && isset($getGps['address_info']['location_accurate']['lng'], $getGps['address_info']['location_accurate']['lat']))
			{
				$select = 'MIN(ROUND(6378.138*2*ASIN(SQRT(POW(SIN(( :lat *PI()/180-`Pro_Items`.`lat`*PI()/180)/2) , 2)+COS( :lat *PI()/180)*COS(`Pro_Items`.`lat`*PI()/180)*POW(SIN(( :lng *PI()/180-`Pro_Items`.`lng`*PI()/180)/2),2)))*1000)) AS distance';
				$criteria->params[':lat'] = $getGps['address_info']['location_accurate']['lat'];
				$criteria->params[':lng'] = $getGps['address_info']['location_accurate']['lng'];
				$criteria->select = array($select);
				$criteria->order = 'distance,`t`.`sort`,`t`.`up_time` desc,`t`.`add_time` desc';
				$distance = true;
			}
			
			$criteria->with = array(
				'Select_Ad'=>array('with'=>'Ad_Select_Count'),
				'Select_Actives',
				'Select_Shops'=>array(
					'with'=>array(
						'Shops_ShopsClassliy'=>array('select'=>'`id`,`name`,`admin`,`append`'),
						'Shops_Pro'=>array(
							'with'=>array(
								'Pro_Items'=>array(
									'select'=>'`id`',	
								),
							),
						),		
					),
				),
			);
			$this->search_centre($criteria);
			if ($id == '')
			{
				//标准条件
				$criteria->addColumnCondition(array(
						'`t`.`type`'=>$type,
						'`Select_Ad`.`status`'=>Ad::status_suc,
				));
			}
			else
			{
				//标准条件
				$criteria->addColumnCondition(array(
						'`t`.`to_id`'=>$id,
						'`Select_Ad`.`status`'=>Ad::status_suc,
				));
			}
			if ($id == '')
				$criteria->group = '`t`.`to_id`';
			else
				$criteria->group = '`t`.`select_id`';
			//商品
			$criteria->addColumnCondition(array(
					'`Select_Shops`.`status`'=>Shops::status_online,	//上线
					'`Select_Shops`.`audit`'=>Shops::audit_pass,			//审核通过
			));
			$count = Select::model()->count($criteria);
			//返回的数据
			$return=array();
			//分页设置
			$return['page'] = $this->controller->page($criteria, $count, ($id == '' ? Yii::app()->params['api_pageSize']['p_ad'] : Yii::app()->params['api_pageSize']['ad']), Yii::app()->params['app_api_domain']);
			//根据条件查询
			$models = Select::model()->findAll($criteria);
 			//分页数据
			if ($id == '')
				$return['list_data']=$this->list_data_parent($models, Yii::app()->params['app_api_domain'], $distance);
			else
				$return['list_data']=$this->list_data($models, Yii::app()->params['app_api_domain'], $distance);
			//空
			if (empty($return['list_data']))
			{
				$return['list_data'] = array();
				$return['null'] = '小觅已经很努力了！';
			}
			$this->controller->send($return);
		}
		$this->controller->send_error(DATA_NULL);
	}
	
	/**
	 * 处理数据
	 * @param unknown $models
	 * @param unknown $domain
	 * @param unknown $distance
	 * @return multitype:multitype:string NULL multitype:multitype: NULL
	 */
	public function list_data_parent($models, $domain, $distance)
	{
		$return = array();
		foreach ($models as $model)
		{
			$img = $this->controller->litimg_path($model->Select_Ad->img);
			$return[] = array(
					'name'=>CHtml::encode($model->Select_Ad->name),
					'info'=>CHtml::encode($model->Select_Ad->info),
					'img'=>$domain . ltrim($img, '.'),
					'link'=>$domain . Yii::app()->createUrl('/api/shops/column', array('id'=>$model->Select_Ad->id)),
					'value'=>$model->Select_Ad->id,
					'sort'=>$model->Select_Ad->sort,
					'type'=>array(
							'name'=>Ad::$_type[$model->Select_Ad->type],
							'value'=>$model->Select_Ad->type,
					),
					'distance'=>$distance ? $this->controller->FormatDistance($model->distance) : '',
					'count'=>$model->Select_Ad->Ad_Select_Count,
			);
		}
		return $return;
	}
	
	/**
	 * 数据处理
	 * @param unknown $models
	 * @param unknown $domain
	 * @param unknown $distance
	 * @return multitype:multitype:
	 */
	public function list_data($models, $domain, $distance)
	{
		$return=array();
		foreach ($models as $key=>$model)
		{
			$img = $this->controller->litimg_path($model->Select_Shops->list_img);
			$share_img = $this->controller->litimg_path($model->Select_Shops->list_img, 'litimg_share', $img);
			$actives = array();
			if (isset($model->Select_Actives) && $model->Select_Actives)
			{
				$actives['actives_status'] = array(
						'name'=>Actives::$_actives_status[$model->Select_Actives->actives_status],
						'value'=>$model->Select_Actives->actives_status,
				);
				//活动时间
				$actives['actives_time'] = array(
						'start_time'=>date('Y-m-d', $model->Select_Actives->start_time),
						'end_time'=>date('Y-m-d', $model->Select_Actives->end_time),
						'go_time'=>$model->Select_Actives->go_time==0 ? '出游日期未定' : date('Y-m-d', $model->Select_Actives->go_time),
						'value'=>$model->Select_Actives->go_time==0 ? 0 : 1, // 0表示报名 1表示我也想去
				);
				//活动简介
				$actives['number'] = array(
						'name'=>'觅趣人数',
						'value'=>$model->Select_Actives->number,
				);
				$actives['order_number'] = array(
						'name'=>'剩余报名人数',
						'value'=>$model->Select_Actives->order_number,
						'sign'=>$model->Select_Actives->number-$model->Select_Actives->order_number,
				);
				$actives['tour_price'] = array(
						'name'=>'服务费/人',
						'value'=>$model->Select_Actives->tour_price,
				);
				$actives['tour_count'] = array(
						'name'=>'报名统计',
						'value'=>$model->Select_Actives->tour_count,
				);
				$actives['order_count'] = array(
						'name'=>'付款统计',
						'value'=>$model->Select_Actives->order_count,
				);
				$actives['actives_type'] = array(
						'name'=>Actives::$_actives_type[$model->Select_Actives->actives_type],
						'value'=>$model->Select_Actives->actives_type,
				);
				$actives['actives_pay_type'] = array(
					'name'=>Actives::$_pay_type[$model->Select_Actives->pay_type],
					'value'=>$model->Select_Actives->pay_type,
				);
			}
			$return[$key] = array(
					'day_num' =>Pro::get_day_num($model->Select_Shops->id),
					'collect_count'=> Collect::get_collect_count(Collect::collect_type_praise, $model->Select_Shops->id),
					'value'=>$model->Select_Shops->id,
					'price'=>Shops::get_price_num($model->Select_Shops->id, $model->Select_Shops->Shops_ShopsClassliy->append, true),
					'down'=>Shops::get_down($model->Select_Shops->id, $model->Select_Shops->Shops_ShopsClassliy->append),
					'brow'=>$model->Select_Shops->brow,
					'share'=>$model->Select_Shops->share,
					'praise'=>$model->Select_Shops->praise,
					'link'=>$domain . Yii::app()->createUrl('/api/'.$model->Select_Shops->Shops_ShopsClassliy->admin.'/view', array('id'=>$model->Select_Shops->id)),
					'name'=>CHtml::encode($model->Select_Shops->name),
					'info'=>CHtml::encode($model->Select_Shops->list_info),
					'image'=>empty($img) ? '' : Yii::app()->params['admin_img_domain'] . ltrim($img, '.'),
					'share_image'=>empty($share_img) ? '' : Yii::app()->params['admin_img_domain'] . ltrim($share_img, '.'),
					'classliy'=>array(
							'name'=>CHtml::encode($model->Select_Shops->Shops_ShopsClassliy->name),
							'value'=>$model->Select_Shops->Shops_ShopsClassliy->id,
					),
					'type'=>array(
							'name'=>$model->Select_Shops->Shops_ShopsClassliy->append == 'Actives' ? ($model->Select_Actives->actives_type == Actives::actives_type_tour ? '觅趣' : '觅鲜') : ($model->Select_Shops->Shops_ShopsClassliy->append == 'Dot' ? '景点' : '线路'),
							'value'=>$model->Select_Shops->Shops_ShopsClassliy->id,
					),
					'actives'=>$actives,
			);
			if($distance)
				$return[$key]['distance'] = $this->controller->FormatDistance($model->distance);
			else 
				$return[$key]['distance'] = '';
		}
		return $return;
	}
	
	/**
	 * 商品核心条件
	 *
	 * 点 至少有一个项目
	 *
	 * 线 有一个项目下线 线路不显示
	 *
	 * 活动 项目不影响 不显示私密活动 显示开放
	 * @param unknown $criteria
	 */
	public function search_centre($criteria)
	{
		//点的条件
		$condition_dot = array(
				'`Select_Shops`.`c_id`=:dot',
				'`Pro_Items`.`status`=:dot_status',
				'`Pro_Items`.`audit`=:dot_audit',
		);
		$criteria->params[':dot'] = Shops::shops_dot;										//点
		$criteria->params[':dot_status'] = Items::status_online;						//上线
		$criteria->params[':dot_audit'] = Items::audit_pass;								//审核通过
		//线的条件
		$condition_thrand = array(
				'`Select_Shops`.`c_id`=:thrand',
				'(`Pro_Items`.`status`!=:thrand_status_offline AND `Pro_Items`.`status`!=:thrand_status_del AND `Pro_Items`.`audit`!=:thrand_audit_nopass AND `Pro_Items`.`audit`!=:thrand_audit_pending AND `Pro_Items`.`audit`!=:thrand_audit_draft)',
		);
		$criteria->params[':thrand'] = Shops::shops_thrand;								//线
		$criteria->params[':thrand_status_offline'] = Items::status_offline;		//下线
		$criteria->params[':thrand_status_del'] = Items::status_del;					//删除
		$criteria->params[':thrand_audit_nopass'] = Items::audit_nopass;		//审核不通过
		$criteria->params[':thrand_audit_pending'] = Items::audit_pending;	//审核中
		$criteria->params[':thrand_audit_draft'] = Items::audit_draft;				//未提交
		//活动的条件
		$condition_actives = array(
				'`Select_Shops`.`c_id`=:actives',
				'`Select_Actives`.`is_open`=:actives_is_open',
		);
		$criteria->params[':actives'] = Shops::shops_actives;							//活动
		$criteria->params[':actives_is_open'] = Actives::is_open_yes;				//开放
		$criteria->addCondition(implode(' OR ', array('('.implode(' AND ',$condition_dot).')','('.implode(' AND ',$condition_thrand).')','('.implode(' AND ',$condition_actives).')')));
	}
}