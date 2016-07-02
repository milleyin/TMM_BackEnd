<?php

/**
 * This is the model class for table "{{bills}}".
 *
 * The followings are the available columns in table '{{bills}}':
 * @property string $id
 * @property string $order_id
 * @property string $order_no
 * @property string $order_items_id
 * @property string $group_no
 * @property string $organizer_id
 * @property string $order_organizer_id
 * @property string $user_id
 * @property string $order_shops_id
 * @property string $shops_name
 * @property string $agent_id
 * @property string $store_id
 * @property string $manager_id
 * @property string $shops_id
 * @property string $shops_c_id
 * @property string $shops_c_name
 * @property string $items_id
 * @property string $items_c_id
 * @property string $items_c_name
 * @property string $items_name
 * @property string $group_price
 * @property string $user_order_count
 * @property string $user_pay_count
 * @property string $user_submit_count
 * @property string $user_price
 * @property string $user_go_count
 * @property string $user_price_count
 * @property double $items_push
 * @property double $items_push_orgainzer
 * @property double $items_push_store
 * @property double $items_push_agent
 * @property string $items_money
 * @property string $items_money_orgainzer
 * @property string $items_money_store
 * @property string $items_money_agent
 * @property string $price
 * @property string $number
 * @property string $start_date
 * @property string $end_date
 * @property integer $hotel_number
 * @property string $total
 * @property string $cash_id
 * @property integer $cash_status
 * @property string $add_time
 * @property string $up_time
 * @property integer $son_status
 * @property integer $status
 */
class Bills extends CActiveRecord
{
	/**
	 * 账单提现状态 提现失败
	 * @var unknown
	 */
	const cash_status_cash_not=-2;
	/**
	 * 账单提现状态 审核未通过
	 * @var unknown
	 */
	const cash_status_audit_not_pass=-1;
	/**
	 * 账单提现状态 未申请
	 * @var unknown
	 */
	const cash_status_not_apply=0;
	/**
	 * 账单提现状态 待审核
	 * @var unknown
	 */
	const cash_status_auditing=1;
	/**
	 *账单提现状态 待提现
	 * @var unknown
	 */
	const cash_status_cashing=2;
	/**
	 *账单提现状态 已提现
	 * @var unknown
	 */
	const cash_status_close=3;

	/**
	 * 解释字段 $_cash_status 的含义
	 *  @var array
	 */
	public static $_cash_status=array(-2=>'提现失败',-1=>'审核未通过',0=>'未申请',1=>'待审核',2=>'待提现',3=>'已提现');
	/**
	 * 解释字段 son_status 的含义
	 *  @var array
	 */
	public static $_son_status=array(0=>'没有',1=>'已添加');
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
	public static $_search_time_type=array('添加时间','更新时间');
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
	 * 一天的营业额
	 * @var
	 */
	public $day_money;

	/**
	 * 统计项目数
	 * @var
	 */
	public $items_count;

	/**
	 * 统计某个项目的总收入
	 * @var
	 */
	public $items_count_money;
	/**
	 *日期
	 * @var
	 */
	public $day_time;
	/**
	 * 平台收益
	 * @var
	 */
	public $total_terrace;
	/**
	 * 组织者收益
	 * @var
	 */
	public $total_orgainzer;
	/**
	 * 商家收益
	 * @var
	 */
	public $total_store;
	/**
	 * 代理商收益
	 * @var
	 */
	public $total_agent;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bills}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('order_id, order_no, agent_id, store_id, manager_id, shops_id, shops_c_id, shops_c_name, items_id, items_c_id, items_c_name, items_name', 'required'),
			array('cash_status, son_status, status', 'numerical', 'integerOnly'=>true),
			array('hotel_number,items_push, items_push_orgainzer, items_push_store, items_push_agent', 'numerical'),
			array('order_id, order_items_id, organizer_id, order_organizer_id, user_id, order_shops_id, agent_id, store_id, manager_id, shops_id, shops_c_id, items_id, items_c_id, user_order_count, user_pay_count, user_submit_count, user_go_count, number, cash_id', 'length', 'max'=>11),
			array('order_no, group_no, shops_name', 'length', 'max'=>128),
			array('shops_c_name, items_c_name', 'length', 'max'=>20),
			array('items_name', 'length', 'max'=>100),
			array('group_price, user_price, user_price_count, items_money, items_money_orgainzer, items_money_store, items_money_agent, price, total', 'length', 'max'=>13),
			array('add_time, up_time,start_date,end_date', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('start_date,end_date,hotel_number,total_terrace,total_orgainzer,total_store,total_agent,day_time,search_time_type,search_start_time,search_end_time,id, order_id, order_no, order_items_id, group_no, organizer_id, order_organizer_id, user_id, order_shops_id, shops_name, agent_id, store_id, manager_id, shops_id, shops_c_id, shops_c_name, items_id, items_c_id, items_c_name, items_name, group_price, user_order_count, user_pay_count, user_submit_count, user_price, user_go_count, user_price_count, items_push, items_push_orgainzer, items_push_store, items_push_agent, items_money, items_money_orgainzer, items_money_store, items_money_agent, price, number, total, cash_id, cash_status, add_time, up_time, son_status, status', 'safe', 'on'=>'search'),
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
			// 项目帐单归属总订单
			'Bills_Order'=>array(self::BELONGS_TO,'Order','order_id'),
			// 项目帐单归属商品订单
			'Bills_OrderShops'=>array(self::BELONGS_TO,'OrderShops','shops_id'),
			// 项目帐单归属项目订单
			'Bills_OrderItems'=>array(self::BELONGS_TO,'OrderItems','items_id'),
			// 订单随行人员(一对多)
			'Bills_OrderRetinue'=>array(self::HAS_MANY,'OrderRetinue','order_id'),
			// 关联组织者订单详情表(归属)
			'Bills_OrderOrganizer'=>array(self::BELONGS_TO,'OrderOrganizer','order_organizer_id'),
			// 关联商家(归属)
			'Bills_StoreUser'=>array(self::BELONGS_TO,'StoreUser','store_id'),
			//关联代理商
			'Bills_Agent'=>array(self::BELONGS_TO,'Agent','agent_id'),
			//关联用户
			'Bills_User'=>array(self::BELONGS_TO,'User','user_id'),
			//关联商品
			'Bills_Shops'=>array(self::BELONGS_TO,'Shops','shops_id'),
			//活动
			'Bills_OrderActives'=>array(self::BELONGS_TO,'OrderActives','order_organizer_id'),
			//
			'Bills_Organizer'=>array(self::BELONGS_TO,'Organizer','organizer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'order_id' => '订单ID',
			'order_no' => '订单号',
			'order_items_id' => '订单项目详细',
			'group_no' => '活动单号',
			'organizer_id' => '组织者',
			'order_organizer_id' => '活动ID',
			'user_id' => '归属用户',
			'order_shops_id' => '商品表',
			'shops_name' => '商品名称',
			'agent_id' => '代理商',
			'store_id' => '商家',
			'manager_id' => '商家管理者',
			'shops_id' => '商品来源',
			'shops_c_id' => '商品分类',
			'shops_c_name' => '商品分类名称',
			'items_id' => '项目来源',
			'items_c_id' => '项目分类',
			'items_c_name' => '项目分类名称',
			'items_name' => '项目名称',
			'group_price' => '总服务费',
			'user_order_count' => '用户下单数',
			'user_pay_count' => '用户支付数',
			'user_submit_count' => '用户出游数',
			'user_price' => '实际服务费/人',
			'user_go_count' => '用户出游人数',
			'user_price_count' => '下单总额',
			'items_push' => '平台 %(生效值)',
			'items_push_orgainzer' => '组织 %(生效值)',
			'items_push_store' => '商家 %(生效值)',
			'items_push_agent' => '代理商 %(生效值)',
			'items_money' => '平台(计算后)',
			'items_money_orgainzer' => '组织者(计算后)',
			'items_money_store' => '商家(计算后)',
			'items_money_agent' => '代理商(计算后)',
			'price' => '项目价格',
			'number' => '购买数量',
			'hotel_number'=>'入住天数',
			'start_date'=>'入住日期',
			'end_date'=>'退房日期',
			'total' => '总计总额',
			'cash_id' => '提现记录',
			'cash_status' => '提现 ',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'son_status' => '组织着细账',
			'status' => '记录状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'day_time'=>'日期',
			'total_terrace'=>'平台收益(元)',
			'total_orgainzer'=>'组织者收益(元)',
			'total_store'=>'商家收益(元)',
			'total_agent'=>'代理商收益(元)',
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
		if($criteria ===''){
			$criteria=new CDbCriteria;
			$criteria->compare('t.status','<>-1');
			$criteria->with=array(
				'Bills_StoreUser',
				'Bills_Agent',
				'Bills_User',
				'Bills_Shops',
				'Bills_OrderActives',
				'Bills_Organizer'=>array(
					'with'=>'Organizer_User'
				),
			);

			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.order_id',$this->order_id,true);
			$criteria->compare('t.order_no',$this->order_no,true);
			$criteria->compare('t.order_items_id',$this->order_items_id,true);
			$criteria->compare('t.group_no',$this->group_no,true);
			$criteria->compare('Organizer_User.phone',$this->organizer_id,true);
			$criteria->compare('t.order_organizer_id',$this->order_organizer_id,true);
			$criteria->compare('Bills_User.phone',$this->user_id,true);
			$criteria->compare('t.order_shops_id',$this->order_shops_id,true);
			$criteria->compare('t.shops_name',$this->shops_name,true);
			$criteria->compare('Bills_Agent.name',$this->agent_id,true);
			$criteria->compare('Bills_StoreUser.phone',$this->store_id,true);
			$criteria->compare('t.manager_id',$this->manager_id,true);
			$criteria->compare('t.shops_id',$this->shops_id,true);
			$criteria->compare('t.shops_c_id',$this->shops_c_id,true);
			$criteria->compare('t.shops_c_name',$this->shops_c_name,true);
			$criteria->compare('t.items_id',$this->items_id,true);
			$criteria->compare('t.items_c_id',$this->items_c_id,true);
			$criteria->compare('t.items_c_name',$this->items_c_name,true);
			$criteria->compare('t.items_name',$this->items_name,true);
			$criteria->compare('t.group_price',$this->group_price,true);
			$criteria->compare('t.user_order_count',$this->user_order_count,true);
			$criteria->compare('t.user_pay_count',$this->user_pay_count,true);
			$criteria->compare('t.user_submit_count',$this->user_submit_count,true);
			$criteria->compare('t.user_price',$this->user_price,true);
			$criteria->compare('t.user_go_count',$this->user_go_count,true);
			$criteria->compare('t.user_price_count',$this->user_price_count,true);
			$criteria->compare('t.items_push',$this->items_push);
			$criteria->compare('t.items_push_orgainzer',$this->items_push_orgainzer);
			$criteria->compare('t.items_push_store',$this->items_push_store);
			$criteria->compare('t.items_push_agent',$this->items_push_agent);
			$criteria->compare('t.items_money',$this->items_money,true);
			$criteria->compare('t.items_money_orgainzer',$this->items_money_orgainzer,true);
			$criteria->compare('t.items_money_store',$this->items_money_store,true);
			$criteria->compare('t.items_money_agent',$this->items_money_agent,true);
			$criteria->compare('t.price',$this->price,true);
			$criteria->compare('t.number',$this->number,true);
			$criteria->compare('t.hotel_number',$this->hotel_number,true);
			$criteria->compare('t.start_date',$this->start_date,true);
			$criteria->compare('end_date',$this->end_date,true);
			$criteria->compare('t.total',$this->total,true);
			$criteria->compare('t.cash_id',$this->cash_id,true);
			$criteria->compare('t.cash_status',$this->cash_status);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.son_status',$this->son_status);
			$criteria->compare('t.status',$this->status);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'t.add_time desc', //设置默认排序
			),
		));
	}

	/**
	 * 代理商（提现）帐单明细
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_agent($id,$criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ===''){
			$criteria=new CDbCriteria;
			$criteria->together = true;
			$criteria->compare('`t`.`status`','<>-1');
			$criteria->addColumnCondition(array(
				'`t`.`cash_id`'=>$id,
				'`t`.`agent_id`'=>Yii::app()->agent->id,
			));
			$criteria->with=array(
				'Bills_StoreUser'=>array(
					'with'=>array(
						'Store_Content'=>array(
							'with'=>array(
								'Content_area_id_p_Area_id',
								'Content_area_id_m_Area_id',
								'Content_area_id_c_Area_id',
							),
						),
					),
				),
			);
			$this->search_time_type = 0;
			if(isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('`t`.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('`t`.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('`t`.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}

//			$criteria->compare('Bills_StoreUser.Store_Content.name',$this->store_id,true);
			$criteria->compare('Bills_StoreUser.phone',$this->store_id,true);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
				'defaultOrder'=>'t.add_time desc', //设置默认排序
			),
		));
	}

	/**
	 * 结算申请详情
	 * @param string $cash_id
	 * @return CActiveDataProvider
	 */
	public function search_cash($cash_id='',$bills_count=''){

			$criteria=new CDbCriteria;
			$criteria->compare('t.status','<>-1');

			if(isset($cash_id) && $cash_id != '')
				$criteria->compare('t.cash_id','='.$cash_id);

			if(isset($bills_count) && $bills_count != ''){
				$day_arr = self::current_time(strtotime($bills_count));
				$criteria->compare('t.add_time','>'.$day_arr['start']);
				$criteria->compare('t.add_time','<'.$day_arr['end']);
			}


			$criteria->with=array(
				'Bills_StoreUser',
				'Bills_Agent',
				'Bills_User',
				'Bills_Shops',
				'Bills_OrderActives',
				'Bills_Organizer'=>array(
					'with'=>'Organizer_User'
				),
			);

			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.order_id',$this->order_id,true);
			$criteria->compare('t.order_no',$this->order_no,true);
			$criteria->compare('t.order_items_id',$this->order_items_id,true);
			$criteria->compare('t.group_no',$this->group_no,true);
			$criteria->compare('t.organizer_id',$this->organizer_id,true);
			$criteria->compare('t.order_organizer_id',$this->order_organizer_id,true);
			$criteria->compare('Bills_User.phone',$this->user_id,true);
			$criteria->compare('t.order_shops_id',$this->order_shops_id,true);
			$criteria->compare('t.shops_name',$this->shops_name,true);
			$criteria->compare('Bills_Agent.name',$this->agent_id,true);
			$criteria->compare('Bills_StoreUser.phone',$this->store_id,true);
			$criteria->compare('t.manager_id',$this->manager_id,true);
			$criteria->compare('t.shops_id',$this->shops_id,true);
			$criteria->compare('t.shops_c_id',$this->shops_c_id,true);
			$criteria->compare('t.shops_c_name',$this->shops_c_name,true);
			$criteria->compare('t.items_id',$this->items_id,true);
			$criteria->compare('t.items_c_id',$this->items_c_id,true);
			$criteria->compare('t.items_c_name',$this->items_c_name,true);
			$criteria->compare('t.items_name',$this->items_name,true);
			$criteria->compare('t.group_price',$this->group_price,true);
			$criteria->compare('t.user_order_count',$this->user_order_count,true);
			$criteria->compare('t.user_pay_count',$this->user_pay_count,true);
			$criteria->compare('t.user_submit_count',$this->user_submit_count,true);
			$criteria->compare('t.user_price',$this->user_price,true);
			$criteria->compare('t.user_go_count',$this->user_go_count,true);
			$criteria->compare('t.user_price_count',$this->user_price_count,true);
			$criteria->compare('t.items_push',$this->items_push);
			$criteria->compare('t.items_push_orgainzer',$this->items_push_orgainzer);
			$criteria->compare('t.items_push_store',$this->items_push_store);
			$criteria->compare('t.items_push_agent',$this->items_push_agent);
			$criteria->compare('t.items_money',$this->items_money,true);
			$criteria->compare('t.items_money_orgainzer',$this->items_money_orgainzer,true);
			$criteria->compare('t.items_money_store',$this->items_money_store,true);
			$criteria->compare('t.items_money_agent',$this->items_money_agent,true);
			$criteria->compare('t.price',$this->price,true);
			$criteria->compare('t.number',$this->number,true);
			$criteria->compare('t.hotel_number',$this->hotel_number,true);
			$criteria->compare('t.start_date',$this->start_date,true);
			$criteria->compare('end_date',$this->end_date,true);
			$criteria->compare('t.total',$this->total,true);
			$criteria->compare('t.cash_id',$this->cash_id,true);
			$criteria->compare('t.cash_status',$this->cash_status);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.son_status',$this->son_status);
			$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
				'defaultOrder'=>'t.add_time desc', //设置默认排序
			),
		));
	}

	/**
	 * 总数
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_bills_count()
	{
			$criteria=new CDbCriteria;
			$criteria->select  = 't.*,
				FROM_UNIXTIME(add_time,"%Y-%m-%d")as day_time,
				sum(items_money) as total_terrace,
				sum(items_money_orgainzer) as total_orgainzer,
				sum(items_money_store) as total_store,
				sum(items_money_agent) as total_agent
			';
			$criteria->together = true;
			$criteria->compare('`t`.`status`','<>-1');

			$this->search_time_type = 0;
			if(isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('`t`.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('`t`.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('`t`.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			if($this->day_time != '')
			{
				$criteria->addBetweenCondition('`t`.add_time',strtotime($this->day_time),strtotime($this->day_time)+3600*24-1);
			}

			$criteria->group = 'FROM_UNIXTIME(add_time,"%Y-%m-%d")' ;

			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
				),
				'sort'=>array(
					'defaultOrder'=>'t.add_time desc', //设置默认排序
				),
			));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bills the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 当前时间开始结束
	 * @param $t
	 * @return array
	 */
	public static function current_time($t){
		$t = $t ? $t : time ();
		$today = array ();
		$today ['start'] = mktime ( 0, 0, 0, date ( "m", $t ), date ( "d", $t ), date ( "Y", $t ) );
		$today ['end'] = mktime ( 23, 59, 59, date ( "m", $t ), date ( "d", $t ), date ( "Y", $t ) );
		return $today;
	}

	/**
	 * 更新申请状态
	 * @param $id
	 * @param $type
	 * @param bool|false $status
	 * @return int
	 */
	public static function bills_account_status($id,$type,$status=false){
		// $status===true 审核失败，项目账单详情 cash_id 值为 0
		if($status==false)
			$cash_arr = array('cash_status'=>$type);
		else
			$cash_arr = array('cash_status'=>$type,'cash_id'=>0);

		return self::model()->updateAll($cash_arr,'cash_id=:cash_id',array(':cash_id'=>$id));
	}
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave(){
		if(parent::beforeSave()){		
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
}
