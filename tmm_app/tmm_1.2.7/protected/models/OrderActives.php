<?php

/**
 * This is the model class for table "{{order_actives}}".
 *
 * The followings are the available columns in table '{{order_actives}}':
 * @property string $id
 * @property string $actives_no
 * @property string $organizer_id
 * @property string $actives_id
 * @property integer $actives_type
 * @property string $user_order_count
 * @property string $user_pay_count
 * @property string $user_submit_count
 * @property string $user_price
 * @property string $user_go_count
 * @property string $user_price_count
 * @property string $total
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class OrderActives extends CActiveRecord
{

	/********************************* 活动分类 ****************************/
	/**
	 * 活动分类 活动(旅游)
	 * @var integer
	 */
	const actives_type_tour=0;
	/**
	 * 活动分类 活动(农产品)
	 * @var integer
	 */
	const actives_type_farm=1;
	/**
	 * 解释字段 actives_type 的含义
	 * @var array
	 */
	public static $_actives_type=array('觅趣','觅趣(农产品)');

	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁用','正常');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('开始时间','更新时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('add_time','up_time'); 
	/**
	 * 搜索开始的时间
	 * @var string
	 */
	public $search_start_time;
	/**
	 * 搜索结束的时间
	 * @var string
	 */
	public $search_end_time;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_actives}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('actives_type, status', 'numerical', 'integerOnly'=>true),
			array('actives_no', 'length', 'max'=>128),
			array('organizer_id, actives_id, user_order_count, user_pay_count, user_submit_count, user_go_count', 'length', 'max'=>11),
			array('user_price, user_price_count, total', 'length', 'max'=>13),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, actives_no, organizer_id, actives_id, actives_type, user_order_count, user_pay_count, user_submit_count, user_price, user_go_count, user_price_count, total, add_time, up_time, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				//活动详细情况
				'OrderActives_OrderItems'=>array(self::HAS_MANY,'OrderItems','order_organizer_id'),
				//活动详细价格
				'OrderActives_OrderItemsFare'=>array(self::HAS_MANY,'OrderItemsFare','order_organizer_id'),
				//活动总订单归属活动
				'OrderActives_Actives'=>array(self::BELONGS_TO,'Actives','actives_id'),
				//活动中报名的详细情况
				'OrderActives_Order'=>array(self::HAS_MANY,'Order','order_organizer_id'),
				//活动关联组织者
				'OrderActives_Organizer'=>array(self::BELONGS_TO,'Organizer','organizer_id'),
				//活动关联用户名
				'OrderActives_User'=>array(self::BELONGS_TO,'User','organizer_id'),
				//活动总订单归属活动
				'OrderActives_Shops'=>array(self::BELONGS_TO,'Shops','actives_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'actives_no' => '觅趣总单号',
			'organizer_id' => '用户',
			'actives_id' => '觅趣',
			'actives_type' => '觅趣分类',
			'user_order_count' => '订单总计',
			'user_pay_count' => '支付订单数',
			'user_submit_count' => '确认出游人数',
			'user_go_count' => '实际出游人数',
			'user_price_count' => '支付总额',
			'total' => '实际支付总额',
			'user_price' => '实际服务费用',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',

		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ==='')
		{
			$criteria=new CDbCriteria;
			if ($this->status != -1)
				$criteria->compare('t.status','<>-1');

			$criteria->with=array(
				'OrderActives_Actives',
				'OrderActives_Shops',
				'OrderActives_OrderItems',
				'OrderActives_User'=>array(
						'with'=>array('User_Organizer'),
				),
			);
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			if ($this->id != '')
			{
				$criteria->addCondition('t.id=:id OR OrderActives_Actives.id=:id');
				$criteria->params[':id'] = $this->id;
			}
			//是否
			$criteria->compare('OrderActives_Actives.is_organizer', $this->OrderActives_Actives->is_organizer);
			//觅趣总单号
			$criteria->compare('t.actives_no',$this->actives_no,true);
			//用户
			if ($this->organizer_id != '')
			{
				$criteria->addCondition('User_Organizer.firm_phone LIKE :organizer_id OR User_Organizer.firm_name LIKE :organizer_id OR OrderActives_User.phone LIKE :organizer_id');
				$criteria->params[':organizer_id'] = '%'.strtr($this->organizer_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('OrderActives_Shops.name',$this->OrderActives_Shops->name,true);
			$criteria->compare('OrderActives_Actives.is_open',$this->OrderActives_Actives->is_open);
			$criteria->compare('OrderActives_Actives.pay_type',$this->OrderActives_Actives->pay_type);
			$criteria->compare('OrderActives_Actives.order_number',$this->OrderActives_Actives->order_number,true);
			
			$criteria->compare('t.actives_id',$this->actives_id, true);
			$criteria->compare('t.actives_type',$this->actives_type);
			$criteria->compare('t.user_order_count',$this->user_order_count,true);
			$criteria->compare('t.user_pay_count',$this->user_pay_count,true);
			$criteria->compare('t.user_submit_count',$this->user_submit_count,true);
			$criteria->compare('t.user_price',$this->user_price,true);
			$criteria->compare('t.user_go_count',$this->user_go_count,true);
			$criteria->compare('t.user_price_count',$this->user_price_count,true);
			$criteria->compare('t.total',$this->total,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.status',$this->status);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'t.add_time desc', //设置默认排序
					'attributes'=>array(
							'OrderActives_Actives.is_organizer'=>array(
								'desc'=>'OrderActives_Actives.is_organizer desc',
							),
							'organizer_id'=>array(
									'asc'=>'OrderActives_User.phone',
									'desc'=>'OrderActives_User.phone desc',
							),
							'OrderActives_Shops.name'=>array(
									'desc'=>'OrderActives_Shops.name desc',
							),
							'OrderActives_Actives.is_open'=>array(
									'desc'=>'OrderActives_Actives.is_open desc',
							),	
							'OrderActives_Actives.pay_type'=>array(
									'desc'=>'OrderActives_Actives.pay_type desc',
							),
							'OrderActives_Actives.order_number'=>array(
									'desc'=>'OrderActives_Actives.order_number desc',
							),
							'*'
					),
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderActives the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if(parent::beforeSave())
		{		
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}
		else
			return false;
	}
	
	/**
	 * 是否能退款
	 * @param unknown $models
	 * @return boolean
	 */
	public function isRefund($models)
	{
		foreach ($models as $model)
		{
			if ($model->is_barcode == OrderItems::is_barcode_valid)
				return true;
		}
		return false;
	}
	
	/**
	 * OMQ+201509011   OMQ + date + id
	 */
	public static function get_actives_no($id,$actives_type=Actives::actives_type_tour)
	{
		if($actives_type == Actives::actives_type_tour )
			$front='MQT';
		else if($actives_type == Actives::actives_type_farm )
			$front='MQF';
		else
			return $id;
		return $front.date('Ymd').self::get_order_no_default($id);
	}
	
	/**
	 * 默认 
	 * @param unknown $id
	 * @return string|unknown
	 */
	public static function get_order_no_default($id)
	{
		$number=Yii::app()->params['order_organizer_no_default'];
		if(strlen($id) < $number)
			return sprintf('%0'.$number.'s', $id);
		return $id;
	}
	
	/**
	 * 	下单总计(报名 下单),
	 * @param unknown $order_actives_id 活动总订单id
	 * @return boolean
	 */
	public static function actives_order_count($order_actives_id, $restore=false)
	{
		if ($restore)
		{
			$attributes = array(
					'user_order_count'=>new CDbExpression('`user_order_count`-1')
			);
		}
		else
		{
			$attributes = array(
				'user_order_count'=>new CDbExpression('`user_order_count`+1')
			);
		}
		return self::model()->updateByPk($order_actives_id,$attributes);
	}
	
	/**
	 * 	实际支付总计数量
	 * @param unknown $order_actives_id 活动总订单id
	 * @return boolean
	 */
	public static function actives_pay_count($order_actives_id)
	{
		return self::model()->updateByPk($order_actives_id, array(
				'user_pay_count'=>new CDbExpression('`user_pay_count`+1')
		));
	}
	
	/**
	 * 	实际支付总计数量 还原
	 * @param unknown $order_actives_id 活动总订单id
	 * @return boolean
	 */
	public static function actives_pay_count_restore($order_actives_id)
	{
		if (self::model()->updateByPk($order_actives_id, array(
				'user_pay_count'=>new CDbExpression('`user_pay_count`-1')
		)))
		{
			$model=self::model()->findByPk($order_actives_id);
			if($model)
				return $model->user_pay_count >= 0;
		}
		
		return false;
	}
	
	/**
	 *	确认出游订单
	 * @param unknown $order_actives_id 活动总订单id
	 * @return boolean
	 */
	public static function actives_confirm_count($order_actives_id, $restore = false)
	{
		return self::model()->updateByPk($order_actives_id, array(
				'user_submit_count'=>new CDbExpression('`user_submit_count`'.($restore ? '-' : '+').'1')
		));
	}
	
	/**
	 * 实际出游人数 添加
	 * @param unknown $order_actives_id
	 * @return boolean
	 */
	public static function actives_go_count($order_actives_id,$number)
	{
		return self::model()->updateByPk($order_actives_id, array(
				'user_go_count'=>new CDbExpression('`user_go_count`+:number',array(':number'=>$number))
		));
	}
	
	/**
	 * 实际出游人数 还原
	 * @param unknown $order_actives_id
	 * @return boolean
	 */
	public static function actives_go_count_restore($order_actives_id,$number)
	{
		if (self::model()->updateByPk($order_actives_id, array(
				'user_go_count'=>new CDbExpression('`user_go_count`-:number',array(':number'=>$number))
		)))
		{
			$model=self::model()->findByPk($order_actives_id);
			if($model)
				return $model->user_go_count >= 0;
		}
		return false;
	}
	
	/**
	 *	支付总额 
	 * @param unknown $order_actives_id
	 * @return boolean
	 */
	public static function actives_price_count($order_actives_id,$money)
	{
		return self::model()->updateByPk($order_actives_id, array(
				'user_price_count'=>new CDbExpression('`user_price_count`+:money',array(':money'=>$money)),
				'up_time'=>new CDbExpression('`up_time`+1'),
		));
	}
	
	/**
	 *	实际总额
	 * @param unknown $order_actives_id
	 * @return boolean
	 */
	public static function actives_total($order_actives_id,$money)
	{
		return self::model()->updateByPk($order_actives_id, array(
				'total'=>new CDbExpression('`total`+:money',array(':money'=>$money)),
				'up_time'=>new CDbExpression('`up_time`+1'),
		));
	}
	
	/**
	 *	实际总额 还原
	 * @param unknown $order_actives_id
	 * @return boolean
	 */
	public static function actives_total_restore($order_actives_id,$money)
	{
		if (self::model()->updateByPk($order_actives_id, array(
				'total'=>new CDbExpression('`total`-:money',array(':money'=>$money)),
				'up_time'=>new CDbExpression('`up_time`-1'),
		)))
		{
			$model=self::model()->findByPk($order_actives_id);
			if($model)
				return $model->total >= 0;
		}
		return false;
	}
	
	/**
	 * 代付总订单 获取 活动的有效订单
	 * @param unknown $order_actives_id
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function getOrderActivesFull($order_actives_id)
	{
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array(
			'order_organizer_id'=>$order_actives_id,											//归属总订单
			'order_type'=>Order::order_type_actives_tour_full,							//代付订单
			'order_status'=>Order::order_status_store_yes,								//代付款
 			'status_go'=>Order::status_go_yes,													//确认出游
 			'centre_status'=>Order::centre_status_yes,										//是否可以支付
			'status'=>Order::status_yes,																//有效的订单
		));
		return Order::model()->find($criteria);
	}
}
