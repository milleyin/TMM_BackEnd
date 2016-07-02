<?php

/**
 * This is the model class for table "{{order_items}}".
 *
 * The followings are the available columns in table '{{order_items}}':
 * @property string $id
 * @property string $order_items_id
 * @property string $organizer_id
 * @property string $order_organizer_id
 * @property string $user_id
 * @property string $order_id
 * @property string $order_shops_id
 * @property string $store_id
 * @property string $manager_id
 * @property string $agent_id
 * @property string $shops_id
 * @property string $shops_name
 * @property string $shops_c_id
 * @property string $shops_c_name
 * @property string $items_id
 * @property string $items_c_id
 * @property string $items_c_name
 * @property string $items_name
 * @property string $items_address
 * @property double $items_push
 * @property double $items_push_orgainzer
 * @property double $items_push_store
 * @property double $items_push_agent
 * @property string $items_map
 * @property string $items_phone
 * @property string $items_weixin
 * @property string $items_content
 * @property integer $items_lng
 * @property integer $items_lat
 * @property integer $items_free_status
 * @property string $items_img
 * @property string $items_start_work
 * @property string $items_end_work
 * @property string $items_up_time
 * @property integer $items_pub_time
 * @property integer $shops_sort
 * @property integer $shops_day_sort
 * @property integer $shops_half_sort
 * @property string $shops_dot_id
 * @property string $shops_thrand_id
 * @property string $shops_info
 * @property string $shops_up_time
 * @property integer $shops_pub_time
 * @property string $total
 * @property string $employ_time
 * @property string $barcode
 * @property integer $is_shops
 * @property integer $is_barcode
 * @property string $scan_barcode
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class OrderItems extends CActiveRecord
{
	/**
	 *	商家确认状态   订单用户取消了
	 * @var unknown
	 */
	const is_shops_order_not=-4;
	/**
	 *	商家确认状态   已接收被其他拒收
	 * @var unknown
	 */
	const is_shops_yes_not=-3;
	/**
	 *  商家确认状态   待接收被其他拒收
	 * @var unknown
	 */
	const is_shops_query_not=-2;
	/**
	 * 商家确认状态   已拒收
	 * @var unknown
	 */
	const is_shops_store_not=-1;
	/**
	 *	商家确认状态   待接收
	 * @var unknown
	 */
	const is_shops_store_query=0;
	/**
	 * 商家确认状态   已接收
	 * @var unknown
	 */
	const is_shops_store_yes=1;
	/**
	 * 解释字段 is_shops 的含义
	 * @var array
	 */
	public static $_is_shops=array(
				-4=>'已取消',
				-3=>'已接收(被其他拒收)',
				-2=>'待接收(被其他拒收)',
				-1=>'已拒收',
				0=>'待接收',
				1=>'已接收',
	);
	/***********************项目消费状态*****************************/
	/**
	 * 已退款
	 * @var unknown
	 */
	const is_barcode_refund=-3;
	/**
	 *  无效
	 * @var unknown
	 */
	const is_barcode_invalid=-2;
	/**
	 * 已过期
	 * @var unknown
	 */
	const is_barcode_past=-1;
	/**
	 *  已消费
	 * @var unknown
	 */
	const is_barcode_yes=0;
	/**
	 *  未消费 有效
	 * @var unknown
	 */
	const is_barcode_valid=1;	
	/**
	 *  解释字段 is_barcode 的含义
	 * @var unknown
	 */
	public static $_is_barcode=array(
			-3=>'已退款',
			-2=>'无效',
			-1=>'已过期',
			0=>'已消费',
			1=>'未消费'		
	);
	
	/**************************************************************************/
	
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
	public static $_search_time_type=array('项目更新时间','项目发布时间','商品更新时间','商品发布时间','项目扫码消费时间','创建时间','更新时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('items_up_time','items_pub_time','shops_up_time','shops_pub_time','employ_time','add_time','up_time'); 
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
	 * 统计项目数
	 * @var
	 */
	public $items_count;

	/**
	 * 统计项目收入
	 * @var
	 */
	public $items_push_count;
	/**
	 * 没有接单的状态
	 * @var unknown
	 */
	public $__is_shops;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('order_shops_id, store_id, manager_id, agent_id, shops_id, shops_c_id, shops_c_name, items_id, items_c_id, items_c_name, items_name, items_address, items_content, items_up_time, items_pub_time, shops_info, shops_up_time, shops_pub_time, employ_time, barcode, scan_barcode, add_time, up_time', 'required'),
			array('items_pub_time, shops_sort, shops_day_sort, shops_half_sort, shops_pub_time, is_shops, is_barcode, status', 'numerical', 'integerOnly'=>true),
			array('items_push, items_push_orgainzer, items_push_store, items_push_agent', 'numerical'),
			array('order_items_id,organizer_id, order_organizer_id, user_id, order_id, order_shops_id, store_id, manager_id, agent_id, shops_id, shops_c_id, items_id, items_c_id, shops_dot_id, shops_thrand_id', 'length', 'max'=>11),
			array('shops_c_name, items_c_name, items_phone, items_weixin', 'length', 'max'=>20),
			array('items_name, items_map, items_img, barcode, scan_barcode', 'length', 'max'=>100),
			array('items_address', 'length', 'max'=>200),
			array('items_up_time, shops_up_time, employ_time, add_time, up_time', 'length', 'max'=>10),
			array('total', 'length', 'max'=>11),
					
			//array('items_free_status','in','range'=>array_keys(Items::$_free_status)),
			//array('items_lng,items_lat','validate_lng_lat'),
			//验证钱
			array('total','ext.Validator.Validator_money'),
			
			//创建点订单 创建项目复制 dot
			array(
	'user_id,store_id,manager_id,agent_id,shops_id,shops_name,shops_c_id,shops_c_name,items_id,items_c_name,
	items_name,items_address,items_push,items_push_orgainzer,items_push_store,items_c_id,items_push_agent,
	items_map,items_phone,items_weixin,items_start_work,items_end_work,
	items_up_time,items_pub_time,shops_sort,shops_up_time,shops_pub_time,total',
					'required','on'=>'create_dot'),
// 			array(
// 	'items_lng,items_lat,items_free_status,user_id,store_id,manager_id,agent_id,shops_id,shops_name,shops_c_id,shops_c_name,items_id,items_c_name,
// 	items_name,items_address,items_push,items_push_orgainzer,items_push_store,items_c_id,items_push_agent,
// 	items_map,items_phone,items_weixin,items_content,items_img,items_start_work,items_end_work,
// 	items_up_time,items_pub_time,shops_sort,shops_info,shops_up_time,shops_pub_time,total', 
// 	'safe', 'on'=>'create_dot'),
// 			array('search_time_type,search_start_time,search_end_time,id, organizer_id, order_organizer_id, order_id, order_shops_id, shops_day_sort, shops_half_sort, shops_dot_id, shops_thrand_id, employ_time, barcode, is_shops, is_barcode, scan_barcode, add_time, up_time, status', 'unsafe', 'on'=>'create_dot'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id,order_items_id,shops_name, organizer_id, order_organizer_id, user_id, order_id, order_shops_id, store_id, manager_id, agent_id, shops_id, shops_c_id, shops_c_name, items_id, items_c_id, items_c_name, items_name, items_address, items_push, items_push_orgainzer, items_push_store, items_push_agent, items_map, items_phone, items_weixin, items_content, items_img, items_start_work, items_end_work, items_up_time, items_pub_time, shops_sort, shops_day_sort, shops_half_sort, shops_dot_id, shops_thrand_id, shops_info, shops_up_time, shops_pub_time, total, employ_time, barcode, is_shops, is_barcode, scan_barcode, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			//订单项目详细购买价格(一对多)
			'OrderItems_OrderItemsFare'=>array(self::HAS_MANY,'OrderItemsFare','order_items_id'),
			//归属商家
			'OrderItems_StoreUser'=>array(self::BELONGS_TO,'StoreUser','store_id'),
			//对应项目类型
			'OrderItems_ItemsClassliy'=>array(self::BELONGS_TO,'ItemsClassliy','items_c_id'),
			// 订单项目归属总订单
			'OrderItems_Order'=>array(self::BELONGS_TO, 'Order','order_id'),
			// 订单项目归属商品
			'OrderItems_OrderShops'=>array(self::BELONGS_TO, 'OrderShops','order_shops_id'),
			//归属商家管理账号
			'OrderItems_StoreUser_Manager'=>array(self::BELONGS_TO,'StoreUser','manager_id'),
			//订单项目归属活动项目
			'OrderItems_OrderItems' =>array(self::BELONGS_TO,'OrderItems','order_items_id'),
			//订单项目归属订单活动（总）
			'OrderItems_OrderActives'=>array(self::BELONGS_TO,'OrderActives','order_organizer_id'),
			//订单项目归属项目
			'OrderItems_Items'=>array(self::BELONGS_TO,'Items','items_id'),
			//归属运营商
			'OrderItems_Agent'=>array(self::BELONGS_TO, 'Agent', 'agent_id'),
			//订单项目归属订单
			'OrderItems_Order'=>array(self::BELONGS_TO,'Order','order_id'),
			//订单项目归属用户
			'OrderItems_User'=>array(self::BELONGS_TO,'User','organizer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'order_items_id'=>'归属觅趣总项目',
			'organizer_id' => '归属组织者表',
			'order_organizer_id' => '归属组织者订单详情表',
			'user_id' => '归属用户',
			'order_id' => '归属订单表',
			'order_shops_id' => '归属订单商品表（复制表）',
			'store_id' => '项目归属商家',
			'manager_id' => '商家管理者',
			'agent_id' => '项目归属代理商',
			'shops_id' => '商品来源',
			'shops_name'=>'商品名称',
			'shops_c_id' => '归属商品分类',
			'shops_c_name' => '归属商品分类名称',
			'items_id' => '项目来源',
			'items_c_id' => '归属项目分类',
			'items_c_name' => '归属项目分类名称',
			'items_name' => '项目名称',
			'items_address' => '项目地址',
			'items_push' => '平台对项目的抽成 %(生效值)',
			'items_push_orgainzer' => '组织者对项目的抽成 %(生效值)',
			'items_push_store' => '商家对项目的抽成 %(生效值)',
			'items_push_agent' => '代理商平台对项目的抽成 %(生效值)',
			'items_map' => '地图',
			'items_phone' => '联系电话',
			'items_weixin' => '微信号',
			'items_content' => '项目详细内容',
			'items_lng'=>'项目经度',
			'items_lat'=>'项目维度',
			'items_free_status'=>'项目免费状态',
			'items_img' => '随机一张图片',
			'items_start_work' => '工作开始时间',
			'items_end_work' => '工作结束时间',
			'items_up_time' => '最后一次更新时间',
			'items_pub_time' => '项目审核通过时间',
			'shops_sort' => '点排序',
			'shops_day_sort' => '区分天单位(半天)',
			'shops_half_sort' => '线 结伴游 排序',
			'shops_dot_id' => '当前项目关联点id',
			'shops_thrand_id' => '当前项目关联线id',
			'shops_info' => '项目简介',
			'shops_up_time' => '最后一次更新时间',
			'shops_pub_time' => '商品审核通过时间',
			'total' => '总计总额',
			'employ_time' => '消费时间',
			'barcode' => '消费码',
			'is_shops' => '商家同意是否接单 0没选择 1同意 -1 不同意',
			'is_barcode' => '-1 无效 0 可消费 1已消费',
			'scan_barcode' => '扫码',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '记录状态1正常0禁用-1删除',
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
		if($criteria ===''){
			$criteria=new CDbCriteria;
			$criteria->compare('status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('order_items_id',$this->order_items_id,true);		
			$criteria->compare('organizer_id',$this->organizer_id,true);
			$criteria->compare('order_organizer_id',$this->order_organizer_id,true);
			$criteria->compare('user_id',$this->user_id,true);
			$criteria->compare('order_id',$this->order_id,true);
			$criteria->compare('order_shops_id',$this->order_shops_id,true);
			$criteria->compare('store_id',$this->store_id,true);
			$criteria->compare('manager_id',$this->manager_id,true);
			$criteria->compare('agent_id',$this->agent_id,true);
			$criteria->compare('shops_id',$this->shops_id,true);
			$criteria->compare('shops_c_id',$this->shops_c_id,true);
			$criteria->compare('shops_c_name',$this->shops_c_name,true);
			$criteria->compare('items_id',$this->items_id,true);
			$criteria->compare('items_c_id',$this->items_c_id,true);
			$criteria->compare('items_c_name',$this->items_c_name,true);
			$criteria->compare('items_name',$this->items_name,true);
			$criteria->compare('items_address',$this->items_address,true);
			$criteria->compare('items_push',$this->items_push);
			$criteria->compare('items_push_orgainzer',$this->items_push_orgainzer);
			$criteria->compare('items_push_store',$this->items_push_store);
			$criteria->compare('items_push_agent',$this->items_push_agent);
			$criteria->compare('items_map',$this->items_map,true);
			$criteria->compare('items_phone',$this->items_phone,true);
			$criteria->compare('items_weixin',$this->items_weixin,true);
			$criteria->compare('items_content',$this->items_content,true);
			$criteria->compare('items_img',$this->items_img,true);
			$criteria->compare('items_start_work',$this->items_start_work,true);
			$criteria->compare('items_end_work',$this->items_end_work,true);
			if($this->items_up_time != '')
				$criteria->addBetweenCondition('items_up_time',strtotime($this->items_up_time),(strtotime($this->items_up_time)+3600*24-1));
			if($this->items_pub_time != '')
				$criteria->addBetweenCondition('items_pub_time',strtotime($this->items_pub_time),(strtotime($this->items_pub_time)+3600*24-1));
			$criteria->compare('shops_sort',$this->shops_sort);
			$criteria->compare('shops_day_sort',$this->shops_day_sort);
			$criteria->compare('shops_half_sort',$this->shops_half_sort);
			$criteria->compare('shops_dot_id',$this->shops_dot_id,true);
			$criteria->compare('shops_thrand_id',$this->shops_thrand_id,true);
			$criteria->compare('shops_info',$this->shops_info,true);
			if($this->shops_up_time != '')
				$criteria->addBetweenCondition('shops_up_time',strtotime($this->shops_up_time),(strtotime($this->shops_up_time)+3600*24-1));
			if($this->shops_pub_time != '')
				$criteria->addBetweenCondition('shops_pub_time',strtotime($this->shops_pub_time),(strtotime($this->shops_pub_time)+3600*24-1));
			$criteria->compare('total',$this->total,true);
			if($this->employ_time != '')
				$criteria->addBetweenCondition('employ_time',strtotime($this->employ_time),(strtotime($this->employ_time)+3600*24-1));
			$criteria->compare('barcode',$this->barcode,true);
			$criteria->compare('is_shops',$this->is_shops);
			$criteria->compare('is_barcode',$this->is_barcode);
			$criteria->compare('scan_barcode',$this->scan_barcode,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('status',$this->status);
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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderItems the static model class
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
				$this->up_time = $this->add_time = time();
			else
				$this->up_time = time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 验证经纬度
	 * @param unknown $attribute
	 */
	public function validate_lng_lat()
	{
		if(!preg_match('/^-?((0|[1-9]\d?|1[1-7]\d)(\.\d{1,7})?|180(\.0{1,7})?)?$/', $this->items_lng))
			$this->addError('items_lng','{attribute} 不是有效的经度');
		if(!preg_match('/^-?((0|[1-8]\d|)(\.\d{1,7})?|90(\.0{1,7})?)?$/', $this->items_lat))
			$this->addError('items_lat','{attribute} 不是有效的维度');
	}
	
	/**
	 * 返回生成的条形码
	 * @param unknown $id 12 数字
	 * @return unknown
	 */
	public static function get_barcode($id)
	{
		return substr($id.date('ymd').mt_rand(100000,999999),0,12);
	}
	
	/**
	 * 设置项目 商家接单状态
	 * @param unknown $ids
	 * @param unknown $is_shops
	 * @return boolean|Ambigous <number, unknown>
	 */
	public static function set_is_shops($ids,$is_shops=self::is_shops_store_yes)
	{
		if(! is_array($ids))
			$ids=array($ids);
		if(empty($ids))
			return true;
		$criteria=new CDbCriteria;
		$criteria->addInCondition('id', $ids);		
		return self::model()->updateAll(array('is_shops'=>$is_shops),$criteria);
	}
	
	/**
	 * 设置项目 消费扫描状态
	 * @param unknown $ids
	 * @param unknown $is_barcode
	 * @return boolean|Ambigous <number, unknown>
	 */
	public static function set_is_barcode($ids,$is_barcode=self::is_barcode_invalid)
	{
		if(! is_array($ids))
			$ids=array($ids);
		if(empty($ids))
			return true;
		$criteria=new CDbCriteria;
		$criteria->addInCondition('id', $ids);
		return self::model()->updateAll(array('is_barcode'=>$is_barcode),$criteria);
	}

	/**
	 * 是否有权限显示 申请提现======自助游
	 * @order_id int 订单ID
	 * @return bool
	 */
	public static function Apply_order($order_id){
		$order_item = self::model()->find(array(
				'select'=>array('id'),
				'order' => 'id DESC',
				'condition' => 'order_id=:order_id AND status=:status AND is_barcode=:is_barcode',
				'params' => array(':order_id'=>$order_id,':status' => 1,':is_barcode'=>OrderItems::is_barcode_yes),
			)
		);
		if($order_item)
			return false;
		else
			return true;
	}

	/**
	 * 是否有权限显示 申请提现======活动
	 * @order_organizer_id int 活动ID
	 * @return bool
	 */
	public static function Apply_actives($order_organizer_id){

		$order_item = OrderItems::model()->find(array(
				'select'=>array('id'),
				'order' => 'id DESC',
				'condition' => 'order_organizer_id=:order_organizer_id AND status=:status AND is_barcode=:is_barcode',
				'params' => array(':order_organizer_id'=>$order_organizer_id,':status' => 1,':is_barcode'=>OrderItems::is_barcode_yes),
			)
		);
		if($order_item)
			return false;
		else
			return true;
	}

	/**
	 * 排除 商品价格没有购买的项目id
	 * @param unknown $order_id
	 */
	public static function exclude_items_code($order_id,$order_organizer_id=0)
	{
		$criteria=new CDbCriteria;
		$criteria->select='id';
		$criteria->addCondition('is_shops != :is_shops');
		$criteria->params[':is_shops'] = self::is_shops_store_yes;
		$criteria->addColumnCondition(array(
			't.order_id'=>$order_id,
			't.order_organizer_id'=>$order_organizer_id,
			't.items_free_status'=>Items::free_status_yes,  // 免费项目
		));
		$models=self::model()->findAll($criteria);
		$return = array();
		foreach ($models as $model)
			$return[]=$model->id;
		
		return $return;
	}
	
	/**
	 * 点 线消费的记录
	 * 添加资金记录 钱包操作
	 * @param unknown $model
	 * @return boolean
	 */
	public static function scancodeDotThrand($model,$past=false)
	{
		if(! isset($model->OrderItems_OrderItemsFare) || empty($model->OrderItems_OrderItemsFare))
			return false;
		$floor = isset(Yii::app()->controller) && Yii::app()->controller ? Yii::app()->controller : Yii::app()->command;
		$user=array(
				'money'=>$model->total,
				'account'=>array('account_id'=>$model->user_id,'account_type'=>Account::user),
				'info'=>array(
						'info_id'=>$model->id,
						'name'=>$past ? '扫描消费':'过期消费',
						'address'=>'',
						'info'=>'',
						'infos'=>array(								
						'订单号：'.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
						'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
						'商品分类： '.$model->shops_c_name,
						'项目名称： '.$model->items_name.' （'.$model->items_id.'）',
						'项目分类： '.$model->items_c_name,
						'项目总价： '.$model->total,
				)),
		);
		$store=array(
				'money'=>0.00,
				'account'=>array('account_id'=>$model->store_id,'account_type'=>Account::store),
				'info'=>array('info_id'=>$model->id,'name'=>'订单收益','address'=>'','info'=>'','infos'=>array(
						'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
						'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
						'商品分类：'.$model->shops_c_name,
						'项目名称：'.$model->items_name.' （'.$model->items_id.'）',
						'项目分类：'.$model->items_c_name,
						'项目总价：'.$model->total,
						'项目分成：'.$model->items_push_store,
				)),
				'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
		);
		$agent=array(
				'money'=>0.00,
				'account'=>array('account_id'=>$model->agent_id,'account_type'=>Account::agent),
				'info'=>array('info_id'=>$model->id,'name'=>'订单收益','address'=>'','info'=>'','infos'=>array(
						'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
						'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
						'商品分类：'.$model->shops_c_name,
						'项目名称：'.$model->items_name.' （'.$model->items_id.'）',
						'项目分类：'.$model->items_c_name,
						'项目总价：'.$model->total,
						'项目分成：'.$model->items_push_agent,
				)),
				'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
		);
		$tmm=array(
				'money'=>0.00,
				'account'=>array('account_id'=>Account::fiction_role_id,'account_type'=>Account::tmm),
				'info'=>array('info_id'=>$model->id,'name'=>'订单收益','address'=>'','info'=>'','infos'=>array(
						'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
						'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
						'商品分类：'.$model->shops_c_name,
						'项目名称：'.$model->items_name.' （'.$model->items_id.'）',
						'项目分类：'.$model->items_c_name,
						'项目总价：'.$model->total,
						'项目分成： '.$model->items_push,
				)),
				'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
		);

		foreach ($model->OrderItems_OrderItemsFare as $fare)
		{
			//累计内容简介
			$user['info']['infos'][]='';
			$user['info']['infos'][] = '单项名称：'.$fare->fare_name;
			$user['info']['infos'][] = '单项价格：'.$fare->price;
			$user['info']['infos'][] = '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$user['info']['infos'][] = '单项总价：'.$fare->total;
			
			$store['info']['infos'][]='';
			$store['info']['infos'][]= '单项名称：'.$fare->fare_name;
			$store['info']['infos'][]= '单项价格：'.$fare->price;
			$store['info']['infos'][]= '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$store['info']['infos'][]= '单项总价：'.$fare->total;
			$store['info']['infos'][]= '单项分成：'.$fare->total .' * '. $floor->floorDiv($model->items_push_store, 100);
			
			$agent['info']['infos'][]= '';
			$agent['info']['infos'][]= '单项名称：'.$fare->fare_name;
			$agent['info']['infos'][]= '单项价格：'.$fare->price;
			$agent['info']['infos'][]= '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$agent['info']['infos'][]= '单项总价：'.$fare->total;
			$agent['info']['infos'][]= '单项分成：'.$fare->total .' * ' . $floor->floorDiv($model->items_push_agent, 100);
				
			$tmm['info']['infos'][]= '';
			$tmm['info']['infos'][]= '单项名称：'.$fare->fare_name;
			$tmm['info']['infos'][]= '单项价格：'.$fare->price;
			$tmm['info']['infos'][]= '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$tmm['info']['infos'][]= '单项总价：'.$fare->total;
			$tmm['info']['infos'][]= '单项分成：'.$fare->total .' - ('.($fare->total .' * ' . $floor->floorDiv($model->items_push_agent, 100)).' + '.($fare->total .' * '. $floor->floorDiv($model->items_push_store, 100)).')';
	
			//计算钱
			$store_money = $floor->floorMul($floor->floorDiv($model->items_push_store, 100), $fare->total);
			$agent_money = $floor->floorMul($floor->floorDiv($model->items_push_agent, 100), $fare->total);
			//累计
			$store['money'] = $floor->floorAdd($store['money'], $store_money);
			$agent['money'] 	= $floor->floorAdd($agent['money'], $agent_money);
			$tmm['money'] = $floor->floorAdd($tmm['money'], $floor->floorSub($fare->total, $floor->floorAdd($store_money, $agent_money)));
		}
		$user['info']['info'] =implode("<br>\n", $user['info']['infos']);
		unset($user['info']['infos']);
	
		$store['info']['info'] =implode("<br>\n ", $store['info']['infos']);
		unset($store['info']['infos']);
	
		$agent['info']['info'] = implode("<br>\n", $agent['info']['infos']);
		unset($agent['info']['infos']);
	
		$tmm['info']['info'] =implode("<br>\n", $tmm['info']['infos']);
		unset($tmm['info']['infos']);
		$return_user = $return_store = $return_agent = $return_tmm = false;
		//用户消费
		if($past)
		{
			$return_user=Account::moneyRecordOrderItemsConsumeRmb(
					$user['money'],
					$user['account'],
					$user['info']
			);
		}
		else
		{
			$return_user=Account::moneyRecordOrderItemsPastRmb(
					$user['money'],
					$user['account'],
					$user['info']
			);
		}
		if(! $return_user)
			return false;
		//商家收益
		$return_store=Account::moneyEntryOrderIncomeRmb(
				$store['money'],
				$store['account'],
				$store['info'],
				$store['to_account']
		);
		if(! $return_store)
			return false;
		//代理商收益
		$return_agent=Account::moneyEntryOrderIncomeRmb(
				$agent['money'],
				$agent['account'],
				$agent['info'],
				$agent['to_account']
		);
		if(! $return_agent)
			return false;
		//平台收益
		$return_tmm=Account::moneyEntryOrderIncomeRmb(
				$tmm['money'],
				$tmm['account'],
				$tmm['info'],
				$tmm['to_account']
		);
		if(! $return_tmm)
			return false;
	
		return true;
	}
	
	/**
	 * 活动项目扫码消费
	 * @param unknown $model
	 * @param string $past
	 * @param string $is_organizer
	 * @return boolean
	 */
	public static function scancodeActivesTour($model,$past=false,$is_organizer=true)
	{
		if(! isset($model->OrderItems_Order->Order_OrderActives) || empty($model->OrderItems_Order->Order_OrderActives))
			return false;
		$floor = isset(Yii::app()->controller) && Yii::app()->controller ? Yii::app()->controller : Yii::app()->command;
		$user=array(
				'money'=>$model->total,
				'account'=>array('account_id'=>$model->user_id,'account_type'=>Account::user),
				'info'=>array(
						'info_id'=>$model->id,
						'name'=>$past?'扫描消费':'过期消费',
						'address'=>'','info'=>'','infos'=>array(
								'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
								'觅趣单号：'.$model->OrderItems_Order->Order_OrderActives->actives_no.' （'.$model->OrderItems_Order->Order_OrderActives->id.'）',
								'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
								'商品分类：'.$model->shops_c_name,
								'项目名称：'.$model->items_name.' （'.$model->items_id.'）',
								'项目分类：'.$model->items_c_name,
								'项目总价：'.$model->total,
						)),
		);
		$store=array(
				'money'=>0.00,
				'account'=>array('account_id'=>$model->store_id,'account_type'=>Account::store),
				'info'=>array('info_id'=>$model->id,'name'=>'订单收益','address'=>'','info'=>'','infos'=>array(
						'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
						'觅趣单号：'.$model->OrderItems_Order->Order_OrderActives->actives_no.' （'.$model->OrderItems_Order->Order_OrderActives->id.'）',
						'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
						'商品分类：'.$model->shops_c_name,
						'项目名称：'.$model->items_name.' （'.$model->items_id.'）',
						'项目分类：'.$model->items_c_name,
						'项目总价：'.$model->total,
						'项目分成：'.$floor->floorDiv($model->items_push_store, 100),
				)),
				'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
		);
		$agent=array(
				'money'=>0.00,
				'account'=>array('account_id'=>$model->agent_id,'account_type'=>Account::agent),
				'info'=>array('info_id'=>$model->id,'name'=>'订单收益','address'=>'','info'=>'','infos'=>array(
						'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
						'觅趣单号：'.$model->OrderItems_Order->Order_OrderActives->actives_no.' （'.$model->OrderItems_Order->Order_OrderActives->id.'）',
						'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
						'商品分类：'.$model->shops_c_name,
						'项目名称：'.$model->items_name.' （'.$model->items_id.'）',
						'项目分类：'.$model->items_c_name,
						'项目总价：'.$model->total,
						'项目分成：'.$floor->floorDiv($model->items_push_agent, 100),
				)),
				'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
		);
		if($is_organizer)
		{
			$organizer=array(
					'money'=>0.00,
					'account'=>array('account_id'=>$model->organizer_id,'account_type'=>Account::user),
					'info'=>array('info_id'=>$model->id,'name'=>'觅趣收益','address'=>'','info'=>'','infos'=>array(
							'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
							'觅趣单号：'.$model->OrderItems_Order->Order_OrderActives->actives_no.' （'.$model->OrderItems_Order->Order_OrderActives->id.'）',
							'商品名称 '.$model->shops_name.' （'.$model->shops_id.'）',
							'商品分类 '.$model->shops_c_name,
							'项目名称 '.$model->items_name.' （'.$model->items_id.'）',
							'项目分类 '.$model->items_c_name,
							'项目总价 '.$model->total,
							'项目分成 '.$floor->floorDiv($model->items_push_orgainzer, 100),
					)),
					'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
			);
		}
		$tmm=array(
				'money'=>0.00,
				'account'=>array('account_id'=>Account::fiction_role_id,'account_type'=>Account::tmm),
				'info'=>array('info_id'=>$model->id,'name'=>'订单收益','address'=>'','info'=>'','infos'=>array(
						'订单号 ： '.$model->OrderItems_Order->order_no.' （'.$model->OrderItems_Order->id.'）',
						'觅趣单号：'.$model->OrderItems_Order->Order_OrderActives->actives_no.' （'.$model->OrderItems_Order->Order_OrderActives->id.'）',
						'商品名称：'.$model->shops_name.' （'.$model->shops_id.'）',
						'商品分类：'.$model->shops_c_name,
						'项目名称：'.$model->items_name.' （'.$model->items_id.'）',
						'项目分类：'.$model->items_c_name,
						'项目总价：'.$model->total,
						'项目分成：'.$floor->floorDiv($model->items_push, 100),
				)),
				'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
		);
		foreach ($model->OrderItems_OrderItemsFare as $fare)
		{
			//累计内容简介
			$user['info']['infos'][] = '';
			$user['info']['infos'][] = '单项名称：'.$fare->fare_name;
			$user['info']['infos'][] = '单项价格：'.$fare->price;
			$user['info']['infos'][] = '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$user['info']['infos'][] = '单项总价：'.$fare->total;

			$store['info']['infos'][]= '';
			$store['info']['infos'][]= '单项名称：'.$fare->fare_name;
			$store['info']['infos'][]= '单项价格：'.$fare->price;
			$store['info']['infos'][]= '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$store['info']['infos'][]= '单项总价：'.$fare->total;
			$store['info']['infos'][]= '单项分成：'.$fare->total .' * '.$floor->floorDiv($model->items_push_store, 100);

			$agent['info']['infos'][]= '';
			$agent['info']['infos'][]= '单项名称：'.$fare->fare_name;
			$agent['info']['infos'][]= '单项价格：'.$fare->price;
			$agent['info']['infos'][]= '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$agent['info']['infos'][]= '单项总价：'.$fare->total;
			$agent['info']['infos'][]= '单项分成：'.$fare->total .' * '.$floor->floorDiv($model->items_push_agent, 100);
			
			if($is_organizer)
			{
				$organizer['info']['infos'][]= '';
				$organizer['info']['infos'][]= '单项名称：'.$fare->fare_name;
				$organizer['info']['infos'][]= '单项价格：'.$fare->price;
				$organizer['info']['infos'][]= '购买数量：'.$fare->number .' * '. $fare->hotel_number;
				$organizer['info']['infos'][]= '单项总价：'.$fare->total;
				$organizer['info']['infos'][]= '单项分成：'.$fare->total .' * '.$floor->floorDiv($model->items_push_orgainzer, 100);
			}
			
			$tmm['info']['infos'][]= '';
			$tmm['info']['infos'][]= '单项名称：'.$fare->fare_name;
			$tmm['info']['infos'][]= '单项价格：'.$fare->price;
			$tmm['info']['infos'][]= '购买数量：'.$fare->number .' * '. $fare->hotel_number;
			$tmm['info']['infos'][]= '单项总价：'.$fare->total;
			if($is_organizer)
				$tmm['info']['infos'][]= '单项分成：'.$fare->total .' - ('.($fare->total .' * '.$floor->floorDiv($model->items_push_agent, 100)).' + '.($fare->total .' * '.$floor->floorDiv($model->items_push_store, 100)).' + '.($fare->total .' * '.$floor->floorDiv($model->items_push_orgainzer, 100)).')';
			else
				$tmm['info']['infos'][]= '单项分成：'.$fare->total .' - ('.($fare->total .' * '.$floor->floorDiv($model->items_push_agent, 100)).' + '.($fare->total .' * '.$floor->floorDiv($model->items_push_store, 100)).')';
			
			//计算钱
			$store_money = $floor->floorMul($floor->floorDiv($model->items_push_store, 100), $fare->total);
			$agent_money = $floor->floorMul($floor->floorDiv($model->items_push_agent, 100), $fare->total);

			//累计
			$store['money'] = $floor->floorAdd($store['money'], $store_money);
			$agent['money'] 	= $floor->floorAdd($agent['money'], $agent_money);
			// 组织者的钱
			if($is_organizer) 
			{
				$orgainzer_money = $floor->floorMul($floor->floorDiv($model->items_push_orgainzer, 100), $fare->total);
				$organizer['money'] = $floor->floorAdd($organizer['money'], $orgainzer_money);
				$tmm['money'] = $floor->floorAdd($tmm['money'], $floor->floorSub($fare->total, $floor->floorAddArray(array($store_money,$agent_money,$orgainzer_money))));
			}
			else
			{
				$tmm['money'] = $floor->floorAdd($tmm['money'], $floor->floorSub($fare->total, $floor->floorAdd($store_money, $agent_money)));
			}
		}

		$user['info']['info'] =implode("<br>\n", $user['info']['infos']);
		unset($user['info']['infos']);

		$store['info']['info'] =implode("<br>\n", $store['info']['infos']);
		unset($store['info']['infos']);

		$agent['info']['info'] = implode("<br>\n", $agent['info']['infos']);
		unset($agent['info']['infos']);
		
		if ($is_organizer)
		{
			$organizer['info']['info'] = implode("<br>\n", $organizer['info']['infos']);
			unset($organizer['info']['infos']);
		}

		$tmm['info']['info'] =implode("<br>\n", $tmm['info']['infos']);
		unset($tmm['info']['infos']);

		$return_user = $return_store = $return_agent = $return_orgainzer = $return_tmm = false;
		if ($past)
		{
			//用户消费
			$return_user = Account::moneyRecordOrderItemsConsumeRmb(
					$user['money'],
					$user['account'],
					$user['info']
			);
			if (! $return_user)
				return false;
		}
		else
		{
			//用户过期消费
			$return_user = Account::moneyRecordOrderItemsPastRmb(
					$user['money'],
					$user['account'],
					$user['info']
			);
			if (! $return_user)
				return false;
		}
		//商家收益
		$return_store = Account::moneyEntryOrderIncomeRmb(
				$store['money'],
				$store['account'],
				$store['info'],
				$store['to_account']
		);
		if (! $return_store)
			return false;
		//是否值组织者
		if ($is_organizer)
		{
			//组织者收益
			$return_orgainzer = Account::moneyEntryOrderIncomeRmb(
					$organizer['money'],
					$organizer['account'],
					$organizer['info'],
					$organizer['to_account']
			);
			if(! $return_orgainzer)
				return false;
		}
		//代理商收益
		$return_agent = Account::moneyEntryOrderIncomeRmb(
				$agent['money'],
				$agent['account'],
				$agent['info'],
				$agent['to_account']
		);
		if (! $return_agent)
			return false;
		//平台收益
		$return_tmm = Account::moneyEntryOrderIncomeRmb(
				$tmm['money'],
				$tmm['account'],
				$tmm['info'],
				$tmm['to_account']
		);
		if(! $return_tmm)
			return false;
					
		return true;
	}
	
	/**
	 * 活动项目扫码消费的服务费用 
	 * @param unknown $model
	 * @return boolean
	 */
	public static function scancodeActivesTourCharge($models,$past=false)
	{
		$floor = isset(Yii::app()->controller) && Yii::app()->controller ? Yii::app()->controller : Yii::app()->command;
		foreach ($models as $model)
		{
			$user=array(
					'money'=>$floor->floorMul($model->Order_OrderActives->user_price, $model->user_go_count),
					'account'=>array('account_id'=>$model->user_id,'account_type'=>Account::user),
					'info'=>array('info_id'=>$model->id,'name'=>'服务费用','address'=>'','info'=>'','infos'=>array(
							'商品名称：'.$model->Order_OrderActives->OrderActives_Actives->Actives_Shops->name.' （'.$model->Order_OrderActives->actives_no.'）',
							'商品分类：'.Actives::$_actives_type[$model->Order_OrderActives->OrderActives_Actives->actives_type],
							'觅趣单号：'.$model->Order_OrderActives->actives_no,
							'订单号 ： '.$model->order_no,
							'订单总价：'.$model->price,
							'出游人数：'.$model->user_go_count,
							'服务费/人：'.$model->Order_OrderActives->user_price,
							'服务统计：'.$model->Order_OrderActives->user_price.' * '.$model->user_go_count,
					)),
					'to_account'=>array('to_account_id'=>$model->Order_OrderActives->organizer_id,'to_account_type'=>Account::user),
			);
			$organizer=array(
					'money'=>$floor->floorMul($model->Order_OrderActives->user_price, $model->user_go_count),
					'account'=>array('account_id'=>$model->Order_OrderActives->organizer_id,'account_type'=>Account::user),
					'info'=>array('info_id'=>$model->Order_OrderActives->id,'name'=>'觅趣收益','address'=>'','info'=>'','infos'=>array(
							'商品名称：'.$model->Order_OrderActives->OrderActives_Actives->Actives_Shops->name.' （'.$model->Order_OrderActives->actives_no.'）',
							'商品分类：'.Actives::$_actives_type[$model->Order_OrderActives->OrderActives_Actives->actives_type],
							'觅趣单号：'.$model->Order_OrderActives->actives_no,
							'订单编号：'.$model->order_no,
							'订单总价：'.$model->price,
							'出游人数：'.$model->user_go_count,
							'服务费/人：'.$model->Order_OrderActives->user_price,
							'服务统计：'.$model->Order_OrderActives->user_price.' * '.$model->user_go_count,
					)),
					'to_account'=>array('to_account_id'=>$model->user_id,'to_account_type'=>Account::user),
			);
			$user['info']['info'] =implode("<br>\n", $user['info']['infos']);
			unset($user['info']['infos']);
				
			$organizer['info']['info'] =implode("<br>\n", $organizer['info']['infos']);
			unset($organizer['info']['infos']);
				
			$return_user = $return_orgainzer = false;
			//用户
			$return_user = Account::moneyRecordActivesTourChargeRmb(
					$user['money'],
					$user['account'],
					$user['info'],
					$user['to_account']
			);
			if (! $return_user)
				return false;
			//组织者
			$return_orgainzer = Account::moneyEntryActivesTourIncomeRmb(
					$organizer['money'],
					$organizer['account'],
					$organizer['info'],
					$organizer['to_account']
			);
			if (! $return_orgainzer)
				return false;
		}
		return true;
	}
}
