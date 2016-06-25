<?php

/**
 * This is the model class for table "{{actives}}".
 *
 * The followings are the available columns in table '{{actives}}':
 * @property string $id
 * @property string $c_id
 * @property integer $actives_type
 * @property string $organizer_id
 * @property integer $tour_type
 * @property string $tour_count
 * @property string $order_count
 * @property double $push
 * @property double $push_orgainzer
 * @property double $push_store
 * @property double $push_agent
 * @property string $price
 * @property string $number 
 * @property string $order_number
 * @property string $tour_price
 * @property string $remark
 * @property string $start_time
 * @property string $end_time
 * @property string $pub_time
 * @property string $go_time
 * @property integer $actives_status
 * @property integer $status
 * @property interer $thrand_id
 * @property interer $is_organizer
 * @property string $barcode
 * @property integer $barcode_num
 * @property integer $pay_type
 * @property integer $is_open
 */
class Actives extends CActiveRecord
{
	/********************************* 活动状态 ****************************/
	/**
	 * 活动状态 已取消
	 * @var integer
	 */
	const actives_status_cancel=-1;
	/**
	 * 活动状态 未开始
	 * @var integer
	 */
	const actives_status_not_start=0;
	/**
	 * 活动状态 开始（报名中，进行中）
	 * @var integer
	 */
	const actives_status_start=1;
	/**
	 * 活动状态 报名结束
	 * @var integer
	 */
	const actives_status_end=2;
	/**
	 * 解释字段 actives_status 的含义
	 * @var array
	 */
	public static $_actives_status=array(-1=>'已取消',0=>'未开始',1=>'报名中',2=>'已结束');
	
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
	
	/********************************* 活动（旅游）类型 ****************************/
	/**
	 * 活动（旅游）类型  由多个点创建的
	 * @var integer
	*/
	const tour_type_farm=-1;
	/**
	 * 活动（旅游）类型  由多个点创建的
	 * @var integer
	 */
	const tour_type_dot=0;
	/**
	 * 活动（旅游）类型  由一条线创建的
	 * @var integer
	 */
	const tour_type_thrand=1;
	/**
	 * 解释字段 actives_type 的含义
	 * @var array
	 */
	public static $_tour_type=array(
		//	-1=>'农产品觅趣',
			'多个点',			
			'一条线'
	);

	public static $__tour_type = array(
		self::tour_type_farm		=>	'farm',
		self::tour_type_dot		=>	'dot',
		self::tour_type_thrand	=> 'thrand'
	);
	
	/**
	 * 活动是否发布 活动未发布
	 * @var integer
	 */
	const status_not_publish=-1;
	/**
	 * 活动是否发布 活动已结束
	 * @var integer
	 */
	const status_published=0;
	/**
	 * 活动是否发布 活动进行中
	 * @var integer
	 */
	const status_publishing=1;	
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'未进行','已失效','未失效');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('开始时间','结束时间','发布时间','出游时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('start_time','end_time','pub_time','go_time'); 
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
	 * 是否组织者  是 组织者
	 * @var int
	 */
	const is_organizer_yes = 1;
	/**
	 * 是否组织者  否 组织者
	 * @var int
	 */
	const is_organizer_no  = 0;
	/**
	 * 是否组织者
	 * @var array
	 */
	public static $_is_organizer = array(0=>'否',1=>'是');

	/**
	 *  付款方式  AA付款
	 * @var int
	 */
	const pay_type_AA = 0;
	/**
	 *  付款方式  全额付款
	 *  @var int
	 */
	const pay_type_full = 1;
	/**
	 *  付款方式
	 * @var array
	 */
	public static $_pay_type = array(0=>'自费',1=>'代付');

	/**
	 *  对外开放  开放
	 *  @var int
	 */
	const is_open_yes = 1;
	/**
	 *  对外开放  不开放
	 *  @var int
	 */
	const is_open_no = 0;
	/**
	 *  对外开放
	 * @var array
	 */
	public static $_is_open = array(0=>'不开放',1=>'开放');


	public $is_sale;
	public $thrand_id;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{actives}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		//	array('id, c_id, organizer_id, remark', 'required'),
			array('actives_type, tour_type, actives_status, status,number', 'numerical', 'integerOnly'=>true),
			array('push, push_orgainzer, push_store, push_agent', 'numerical'),
			array('id, c_id, organizer_id, tour_count, order_count, order_number', 'length', 'max'=>11),
			array('price', 'length', 'max'=>13),
			array('barcode_num,tour_price', 'length', 'max'=>12),
			array('start_time, end_time, pub_time, go_time', 'length', 'max'=>10),
			array('barcode', 'length', 'max'=>100),

			array('pay_type','in','range'=>array_keys(self::$_pay_type)),
			array('is_open','in','range'=>array_keys(self::$_is_open)),
			//验证钱
			array('tour_price','ext.Validator.Validator_money'),

			//创建
			array('actives_type,tour_type,number,price,tour_price,remark,end_time,start_time', 'required','on'=>'create'),

			array('thrand_id,actives_type,tour_type,number,price,tour_price,remark,end_time,start_time', 'required','on'=>'create_thrand'),
			array('thrand_id','validate_thrand_id','on'=>'create_thrand'),

			array('remark', 'length', 'max'=>10000,'on'=>'create,create_thrand,api_update_actives_remark'),
			array('number', 'length', 'max'=>4,'on'=>'create,create_thrand,api_update_actives_number'),

			array('end_time,start_time,go_time','type','dateFormat'=>'yyyy-MM-dd','type'=>'date', 'on'=>'create,create_thrand'),
			array('start_time,end_time','validate_end_time','on'=>'create,create_thrand,api_update_actives_start_end_time'),
			array('end_time,start_time','type','dateFormat'=>'yyyy-MM-dd','type'=>'date', 'on'=>'api_update_actives_start_end_time'),

			array('go_time','validate_go_time','on'=>'create,create_thrand'),
			array('thrand_id,actives_type,tour_type,number,price,tour_price,remark,end_time,go_time,start_time','safe','on'=>'create,create_thrand'),
			array('barcode,barcode_num,is_organizer,search_time_type,search_start_time,search_end_time,id, c_id, organizer_id, tour_count, order_count, pub_time,push, push_orgainzer, push_store, push_agent,order_number,  actives_status, status','unsafe','on'=>'create,create_thrand'),

			//审核通过 设置默认分成比例
			array('push,push_orgainzer,push_store,push_agent','required','on'=>'pass'),
			array('push,push_orgainzer,push_store,push_agent', 'safe', 'on'=>'pass'),
			//验证是否合法
			array('push,push_orgainzer,push_store,push_agent','ext.Validator.Validator_push','on'=>'pass'),
			//验证统计是否合法
			array('push,push_orgainzer,push_store,push_agent','push_count','on'=>'pass'),
			array('barcode,barcode_num,pay_type,is_open,,is_organizer,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count,  price, number,order_number, tour_price, remark, start_time, end_time, pub_time, go_time, actives_status, status', 'unsafe', 'on'=>'pass'),


			//选择创建点、线
			array('tour_type', 'required','on'=>'organizer_create'),
			array('tour_type','safe','on'=>'organizer_create'),
			array('tour_type','in','range'=>array_keys(self::$_tour_type)),
			array('is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, number,order_number, tour_price, remark, start_time, end_time, pub_time, go_time, actives_status, status','unsafe','on'=>'organizer_create'),


			//设置出游时间
			array('go_time','required','on'=>'go_time'),
			array('go_time', 'safe', 'on'=>'go_time'),
			array('go_time','type','dateFormat'=>'yyyy-MM-dd','type'=>'date','on'=>'go_time,api_update_actives_go_time'),
			array('go_time','validate_go_time','on'=>'go_time,api_update_actives_go_time'),
			array('barcode,barcode_num,pay_type,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, number,order_number, tour_price, remark, start_time, end_time, pub_time, actives_status, status', 'unsafe', 'on'=>'go_time'),


			// api 修改线路 活动参与人数   2016-2-23
			array('number', 'safe', 'on'=>'api_update_actives_number'),
			array('barcode,barcode_num,pay_type,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, order_number, tour_price, remark, start_time, end_time, pub_time, go_time, actives_status, status','unsafe','on'=>'api_update_actives_number'),

			// api 修改线路 服务费   2016-2-23
			array('tour_price', 'safe', 'on'=>'api_update_actives_tour_price'),
			array('number,barcode,barcode_num,pay_type,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, order_number,  remark, start_time, end_time, pub_time, go_time, actives_status, status','unsafe','on'=>'api_update_actives_tour_price'),

			// api 修改线路 出游日期   2016-2-23
			array('go_time', 'safe', 'on'=>'api_update_actives_go_time'),
			array('number,barcode,barcode_num,pay_type,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, order_number, tour_price, remark, start_time, end_time, pub_time, actives_status, status','unsafe','on'=>'api_update_actives_go_time'),

			// api 修改线路 报名起止日期   2016-2-23
			array('start_time,end_time', 'safe', 'on'=>'api_update_actives_start_end_time'),
			array('number,barcode,barcode_num,pay_type,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, order_number, tour_price, remark,  pub_time, go_time, actives_status, status','unsafe','on'=>'api_update_actives_start_end_time'),

			// api 修改线路 活动简介   2016-2-23
			array('remark', 'safe', 'on'=>'api_update_actives_remark'),
			array('number,barcode,barcode_num,pay_type,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, order_number, tour_price, start_time, end_time, pub_time, go_time, actives_status, status','unsafe','on'=>'api_update_actives_remark'),

			// api 修改线路 付款方式   2016-2-23
			array('pay_type', 'safe', 'on'=>'api_update_actives_pay_type'),
			array('number,barcode,barcode_num,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, order_number, tour_price, remark, start_time, end_time, pub_time, go_time, actives_status, status','unsafe','on'=>'api_update_actives_pay_type'),

			// api 修改线路 活动公开性   2016-2-23
			array('is_open', 'safe', 'on'=>'api_update_actives_is_open'),
			array('number,barcode,barcode_num,pay_type,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, order_number, tour_price, remark, start_time, end_time, pub_time, go_time, actives_status, status','unsafe','on'=>'api_update_actives_is_open'),


			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('barcode,barcode_num,pay_type,is_open,is_organizer,thrand_id,is_sale,search_time_type,search_start_time,search_end_time,id, c_id, actives_type, organizer_id, tour_type, tour_count, order_count, push, push_orgainzer, push_store, push_agent, price, number,order_number, tour_price, remark, start_time, end_time, pub_time, go_time, actives_status, status', 'safe', 'on'=>'search'),
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
			//活动关联商品表
			'Actives_Shops'=>array(self::HAS_ONE,'Shops','id'),
			//活动关联类型表 归属（多对一）
			'Actives_ShopsClassliy'=>array(self::BELONGS_TO,'ShopsClassliy','c_id'),
			//活动关联 选中项目表 (一对多)
			'Actives_Pro'=>array(self::HAS_MANY,'Pro','shops_id'),
			//活动关联 选中标签
			'Actives_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
			//活动关联组织者
			'Actives_Organizer'=>array(self::BELONGS_TO,'Organizer','organizer_id'),
			// 活动关联用户
			'Actives_User'=>array(self::BELONGS_TO, 'User', 'organizer_id'),
			//活动关联活动总订单
			'Actives_OrderActives'=>array(self::HAS_ONE,'OrderActives','actives_id'),
			//活动关联 ====创建活动点的信息
			//'Actives_ShopsInfo'=>array(self::HAS_ONE,'ShopsInfo',array('id'=>'shops_id')),
			'Actives_ShopsInfo'=>array(self::HAS_ONE,'ShopsInfo',array('shops_id'=>'id')),
			//活动报名的人
			'Actives_Attend'=>array(self::HAS_MANY,'Attend','actives_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'c_id' => '分类',
			'actives_type' => '分类',
			'organizer_id' => '用户',
			'tour_type' => '类型',
			'tour_count' => '报名人数',
			'order_count' => '下单量',
			'push' => '平台抽成 %',
			'push_orgainzer' => '代理商抽成 %',
			'push_store' => '供应商抽成 %',
			'push_agent' => '运营商抽成 %',
			'price' => '单价',
			'number' => '觅趣人数',
			'order_number'=>'剩余数量',
			'tour_price' => '服务费',
			'remark' => '简介',
			'start_time' => '报名开始',
			'end_time' => '报名结束',
			'pub_time' => '发布时间',
			'go_time' => '出游时间',
			'actives_status' => '报名状态',
			'status' => '有效',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'is_sale'=>'可卖',
			'thrand_id'=>'线ID',
			'is_organizer'=>'代理商',
			'barcode'=>'私密钥',
			'barcode_num'=>'私密浏览量',
			'pay_type'=>'付款方式',
			'is_open'=>'开放'
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
		if ($criteria ==='')
		{
			$criteria=new CDbCriteria;
			if ($this->Actives_Shops->status != Shops::status_del)
				$criteria->compare('Actives_Shops.status','<>-1');
			//关系
			$criteria->with=array(
				'Actives_Shops',
				'Actives_User'=>array(
						'with'=>array(
							'User_Organizer'
						),
				),
			);
			// 时间搜索
			if ($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			if ($this->Actives_Shops->search_time_type != '' && isset($this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type]))
			{
				if($this->Actives_Shops->search_start_time !='' && $this->Actives_Shops->search_end_time !='')
					$criteria->addBetweenCondition('Actives_Shops.'.$this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type],strtotime($this->Actives_Shops->search_start_time),strtotime($this->Actives_Shops->search_end_time)+3600*24-1);
				elseif($this->Actives_Shops->search_start_time !='' && $this->Actives_Shops->search_end_time =='')
					$criteria->compare('Actives_Shops.'.$this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type],'>='.strtotime($this->Actives_Shops->search_start_time));
				elseif($this->Actives_Shops->search_start_time =='' && $this->Actives_Shops->search_end_time !='')
					$criteria->compare('Actives_Shops.'.$this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type],'<='.strtotime($this->Actives_Shops->search_end_time)+3600*24-1);
			}
			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.c_id',$this->c_id,true);
			$criteria->compare('t.actives_type',$this->actives_type);
			
			if ($this->organizer_id != '')
			{
				$criteria->addCondition('User_Organizer.firm_phone LIKE :organizer_id OR User_Organizer.firm_name LIKE :organizer_id OR Actives_User.phone LIKE :organizer_id');
				$criteria->params[':organizer_id'] = '%'.strtr($this->organizer_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			
			$criteria->compare('t.tour_type',$this->tour_type);
			$criteria->compare('t.tour_count',$this->tour_count,true);
			$criteria->compare('t.order_count',$this->order_count,true);
			$criteria->compare('t.number',$this->number,true);
			$criteria->compare('t.order_number',$this->order_number,true);
			$criteria->compare('t.tour_price',$this->tour_price,true);
			$criteria->compare('t.remark',$this->remark,true);
			$criteria->compare('t.barcode',$this->barcode);
			$criteria->compare('t.barcode_num',$this->barcode_num,true);
			$criteria->compare('t.pay_type',$this->pay_type);
			$criteria->compare('t.is_open',$this->is_open);
			$criteria->compare('t.is_organizer',$this->is_organizer);

			if($this->start_time != '')
				$criteria->addBetweenCondition('t.start_time',strtotime($this->start_time),(strtotime($this->start_time)+3600*24-1));
			if($this->end_time != '')
				$criteria->addBetweenCondition('t.end_time',strtotime($this->end_time),(strtotime($this->end_time)+3600*24-1));
			if($this->pub_time != '')
				$criteria->addBetweenCondition('t.pub_time',strtotime($this->pub_time),(strtotime($this->pub_time)+3600*24-1));
			if($this->go_time != '')
				$criteria->addBetweenCondition('t.go_time',strtotime($this->go_time),(strtotime($this->go_time)+3600*24-1));
			$criteria->compare('t.actives_status',$this->actives_status);
			$criteria->compare('t.status',$this->status);	
			//商品			
			$criteria->compare('Actives_Shops.name',$this->Actives_Shops->name,true);
			$criteria->compare('Actives_Shops.list_info',$this->Actives_Shops->list_info,true);
			$criteria->compare('Actives_Shops.page_info',$this->Actives_Shops->page_info,true);
			$criteria->compare('Actives_Shops.cost_info',$this->Actives_Shops->cost_info,true);
			$criteria->compare('Actives_Shops.book_info',$this->Actives_Shops->book_info,true);
			$criteria->compare('Actives_Shops.selected_info',$this->Actives_Shops->selected_info,true);
			$criteria->compare('Actives_Shops.brow',$this->Actives_Shops->brow,true);
			$criteria->compare('Actives_Shops.share',$this->Actives_Shops->share,true);
			$criteria->compare('Actives_Shops.praise',$this->Actives_Shops->praise,true);
			$criteria->compare('Actives_Shops.is_sale',$this->Actives_Shops->is_sale);
			$criteria->compare('Actives_Shops.selected',$this->Actives_Shops->selected);
			$criteria->compare('Actives_Shops.tags_ids', $this->Actives_Shops->tags_ids , true);
			if($this->Actives_Shops->hot_time != '')
				$criteria->addBetweenCondition('Actives_Shops.hot_time',strtotime($this->Actives_Shops->hot_time),(strtotime($this->Actives_Shops->hot_time)+3600*24-1));
			$criteria->compare('Actives_Shops.hot',$this->Actives_Shops->hot);
			if($this->Actives_Shops->selected_time != '')
				$criteria->addBetweenCondition('Actives_Shops.selected_time',strtotime($this->Actives_Shops->selected_time),(strtotime($this->Actives_Shops->selected_time)+3600*24-1));
			$criteria->compare('Actives_Shops.tops',$this->Actives_Shops->tops);
			if($this->Actives_Shops->tops_time != '')
				$criteria->addBetweenCondition('Actives_Shops.tops_time',strtotime($this->Actives_Shops->tops_time),(strtotime($this->Actives_Shops->tops_time)+3600*24-1));
			$criteria->compare('Actives_Shops.selected_tops',$this->Actives_Shops->selected_tops);
			if($this->Actives_Shops->selected_tops_time != '')
				$criteria->addBetweenCondition('Actives_Shops.selected_tops_time',strtotime($this->Actives_Shops->selected_tops_time),(strtotime($this->Actives_Shops->selected_tops_time)+3600*24-1));
			if($this->Actives_Shops->pub_time != '')
				$criteria->addBetweenCondition('Actives_Shops.pub_time',strtotime($this->Actives_Shops->pub_time),(strtotime($this->Actives_Shops->pub_time)+3600*24-1));
			if($this->Actives_Shops->add_time != '')
				$criteria->addBetweenCondition('Actives_Shops.add_time',strtotime($this->Actives_Shops->add_time),(strtotime($this->Actives_Shops->add_time)+3600*24-1));
			if($this->Actives_Shops->up_time != '')
				$criteria->addBetweenCondition('Actives_Shops.up_time',strtotime($this->Actives_Shops->up_time),(strtotime($this->Actives_Shops->up_time)+3600*24-1));
			$criteria->compare('Actives_Shops.audit',$this->Actives_Shops->audit);
			$criteria->compare('Actives_Shops.status',$this->Actives_Shops->status);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
				'defaultOrder'=>'t.id desc', //设置默认排序
					'attributes'=>array(
							'organizer_id'=>array(
									'asc'=>'Actives_User.phone',
									'desc'=>'Actives_User.phone desc',
							),
							'Actives_Shops.name'=>array(
									'desc'=>'Actives_Shops.name desc',
							),
							'Actives_Shops.audit'=>array(
									'desc'=>'Actives_Shops.audit desc',
							),
							'Actives_Shops.is_sale'=>array(
									'desc'=>'Actives_Shops.is_sale desc',
							),
							'Actives_Shops.tops'=>array(
									'desc'=>'Actives_Shops.tops desc',
							),
							'Actives_Shops.selected'=>array(
									'desc'=>'Actives_Shops.selected desc',
							),
							'Actives_Shops.selected_tops'=>array(
									'desc'=>'Actives_Shops.selected_tops desc',
							),
							'Actives_Shops.hot'=>array(
									'desc'=>'Actives_Shops.hot desc',
							),
							'Actives_Shops.status'=>array(
									'desc'=>'Actives_Shops.status desc',
							),			
							'*',
					)
			),
		));
	}
	
	/**
	 * 选中
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function selectSearch($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if ($criteria ==='')
		{
			$criteria=new CDbCriteria;
			
			$criteria->compare('Actives_Shops.status','<>-1');
			$criteria->addColumnCondition(array(
				'Actives_Shops.status'=>Shops::status_online,
				't.is_open'=>Actives::is_open_yes,
			));
			//关系
			$criteria->with=array(
					'Actives_Shops',
					'Actives_User'=>array(
							'with'=>array(
									'User_Organizer'
							),
					),
			);
			// 时间搜索
			if ($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			if ($this->Actives_Shops->search_time_type != '' && isset($this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type]))
			{
				if($this->Actives_Shops->search_start_time !='' && $this->Actives_Shops->search_end_time !='')
					$criteria->addBetweenCondition('Actives_Shops.'.$this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type],strtotime($this->Actives_Shops->search_start_time),strtotime($this->Actives_Shops->search_end_time)+3600*24-1);
				elseif($this->Actives_Shops->search_start_time !='' && $this->Actives_Shops->search_end_time =='')
				$criteria->compare('Actives_Shops.'.$this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type],'>='.strtotime($this->Actives_Shops->search_start_time));
				elseif($this->Actives_Shops->search_start_time =='' && $this->Actives_Shops->search_end_time !='')
				$criteria->compare('Actives_Shops.'.$this->Actives_Shops->__search_time_type[$this->Actives_Shops->search_time_type],'<='.strtotime($this->Actives_Shops->search_end_time)+3600*24-1);
			}
				
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.c_id',$this->c_id,true);
			$criteria->compare('t.actives_type',$this->actives_type);
				
			if ($this->organizer_id != '')
			{
				$criteria->addCondition('User_Organizer.firm_phone LIKE :organizer_id OR User_Organizer.firm_name LIKE :organizer_id OR Actives_User.phone LIKE :organizer_id');
				$criteria->params[':organizer_id'] = '%'.strtr($this->organizer_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
				
			$criteria->compare('t.tour_type',$this->tour_type);
			$criteria->compare('t.tour_count',$this->tour_count,true);
			$criteria->compare('t.order_count',$this->order_count,true);
			$criteria->compare('t.number',$this->number,true);
			$criteria->compare('t.order_number',$this->order_number,true);
			$criteria->compare('t.tour_price',$this->tour_price,true);
			$criteria->compare('t.remark',$this->remark,true);
			$criteria->compare('t.barcode',$this->barcode);
			$criteria->compare('t.barcode_num',$this->barcode_num,true);
			$criteria->compare('t.pay_type',$this->pay_type);
			$criteria->compare('t.is_open',$this->is_open);
			$criteria->compare('t.is_organizer',$this->is_organizer);
	
			if($this->start_time != '')
				$criteria->addBetweenCondition('t.start_time',strtotime($this->start_time),(strtotime($this->start_time)+3600*24-1));
			if($this->end_time != '')
				$criteria->addBetweenCondition('t.end_time',strtotime($this->end_time),(strtotime($this->end_time)+3600*24-1));
			if($this->pub_time != '')
				$criteria->addBetweenCondition('t.pub_time',strtotime($this->pub_time),(strtotime($this->pub_time)+3600*24-1));
			if($this->go_time != '')
				$criteria->addBetweenCondition('t.go_time',strtotime($this->go_time),(strtotime($this->go_time)+3600*24-1));
			$criteria->compare('t.actives_status',$this->actives_status);
			$criteria->compare('t.status',$this->status);
			//商品
			$criteria->compare('Actives_Shops.name',$this->Actives_Shops->name,true);
			$criteria->compare('Actives_Shops.list_info',$this->Actives_Shops->list_info,true);
			$criteria->compare('Actives_Shops.page_info',$this->Actives_Shops->page_info,true);
			$criteria->compare('Actives_Shops.cost_info',$this->Actives_Shops->cost_info,true);
			$criteria->compare('Actives_Shops.book_info',$this->Actives_Shops->book_info,true);
			$criteria->compare('Actives_Shops.selected_info',$this->Actives_Shops->selected_info,true);
			$criteria->compare('Actives_Shops.brow',$this->Actives_Shops->brow,true);
			$criteria->compare('Actives_Shops.share',$this->Actives_Shops->share,true);
			$criteria->compare('Actives_Shops.praise',$this->Actives_Shops->praise,true);
			$criteria->compare('Actives_Shops.is_sale',$this->Actives_Shops->is_sale);
			$criteria->compare('Actives_Shops.selected',$this->Actives_Shops->selected);
			$criteria->compare('Actives_Shops.tags_ids', $this->Actives_Shops->tags_ids , true);
			if($this->Actives_Shops->hot_time != '')
				$criteria->addBetweenCondition('Actives_Shops.hot_time',strtotime($this->Actives_Shops->hot_time),(strtotime($this->Actives_Shops->hot_time)+3600*24-1));
			$criteria->compare('Actives_Shops.hot',$this->Actives_Shops->hot);
			if($this->Actives_Shops->selected_time != '')
				$criteria->addBetweenCondition('Actives_Shops.selected_time',strtotime($this->Actives_Shops->selected_time),(strtotime($this->Actives_Shops->selected_time)+3600*24-1));
			$criteria->compare('Actives_Shops.tops',$this->Actives_Shops->tops);
			if($this->Actives_Shops->tops_time != '')
				$criteria->addBetweenCondition('Actives_Shops.tops_time',strtotime($this->Actives_Shops->tops_time),(strtotime($this->Actives_Shops->tops_time)+3600*24-1));
			$criteria->compare('Actives_Shops.selected_tops',$this->Actives_Shops->selected_tops);
			if($this->Actives_Shops->selected_tops_time != '')
				$criteria->addBetweenCondition('Actives_Shops.selected_tops_time',strtotime($this->Actives_Shops->selected_tops_time),(strtotime($this->Actives_Shops->selected_tops_time)+3600*24-1));
			if($this->Actives_Shops->pub_time != '')
				$criteria->addBetweenCondition('Actives_Shops.pub_time',strtotime($this->Actives_Shops->pub_time),(strtotime($this->Actives_Shops->pub_time)+3600*24-1));
			if($this->Actives_Shops->add_time != '')
				$criteria->addBetweenCondition('Actives_Shops.add_time',strtotime($this->Actives_Shops->add_time),(strtotime($this->Actives_Shops->add_time)+3600*24-1));
			if($this->Actives_Shops->up_time != '')
				$criteria->addBetweenCondition('Actives_Shops.up_time',strtotime($this->Actives_Shops->up_time),(strtotime($this->Actives_Shops->up_time)+3600*24-1));
			$criteria->compare('Actives_Shops.audit',$this->Actives_Shops->audit);
			$criteria->compare('Actives_Shops.status',$this->Actives_Shops->status);
		}
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>Yii::app()->params['admin_pageSize'],
				),
				'sort'=>array(
						'defaultOrder'=>'t.id desc', //设置默认排序
						'attributes'=>array(
								'organizer_id'=>array(
										'asc'=>'Actives_User.phone',
										'desc'=>'Actives_User.phone desc',
								),
								'Actives_Shops.name'=>array(
										'desc'=>'Actives_Shops.name desc',
								),
								'Actives_Shops.audit'=>array(
										'desc'=>'Actives_Shops.audit desc',
								),
								'Actives_Shops.is_sale'=>array(
										'desc'=>'Actives_Shops.is_sale desc',
								),
								'Actives_Shops.tops'=>array(
										'desc'=>'Actives_Shops.tops desc',
								),
								'Actives_Shops.selected'=>array(
										'desc'=>'Actives_Shops.selected desc',
								),
								'Actives_Shops.selected_tops'=>array(
										'desc'=>'Actives_Shops.selected_tops desc',
								),
								'Actives_Shops.hot'=>array(
										'desc'=>'Actives_Shops.hot desc',
								),
								'Actives_Shops.status'=>array(
										'desc'=>'Actives_Shops.status desc',
								),
								'*',
						)
				),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Actives the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 验证线ID
	 */
	public function validate_thrand_id()
	{
		if(! ($this->hasErrors('thrand_id')) ){
			//线ID 必须是大于0 的数字
			if($this->thrand_id <= 0)
				$this->addError('thrand_id', '线ID不是有效值1');
			// 线ID 在shops 表里必须是审核通过并上线的
			$Shops_model = Shops::model()->findByPk($this->thrand_id,
				'`status`=:status AND `audit`=:audit ',
				 array(
					':audit'=>Shops::audit_pass,
					':status'=>Shops::status_online
			));
			if(!$Shops_model)
				$this->addError('thrand_id', '线ID不是有效值2');
		}
	}
	/**
	 * 验证报名截止时间
	 */
	public function validate_end_time()
	{
		if(! ($this->hasErrors('end_time') && $this->hasErrors('start_time')) )
		{
			$day=Yii::app()->params['order_limit']['actives_tour']['min_interval_time'];
			if($this->start_time < date('Y-m-d'))
				$this->addError('start_time', '开始时间 不能小于今天');
			elseif($this->end_time < date('Y-m-d'))
				$this->addError('end_time', '结束时间 不能小于今天');
			elseif( strtotime($this->end_time) < (strtotime($this->start_time) + 3600*24*$day) )
				$this->addError('end_time', '报名结束时间必须大于报名开始时间'.$day.'天');
		}
	}
	/**
	 * 分成比例 之和验证
	 * @param unknown $attribute
	 */
	public function push_count($attribute)
	{
		$array =  array(
				$this->push,
				$this->push_orgainzer,
				$this->push_store,
				$this->push_agent
		);		
		if(Yii::app()->controller->floorComp(Yii::app()->controller->floorAddArray($array), 100) != 0)
			$this->addError($attribute,$this->getAttributeLabel($attribute).' 它们之和只能等于100%');
	}
	
	/**
	 * 验证出发时间
	 */
	public function validate_go_time()
	{
		if(! $this->hasErrors('go_time') && $this->go_time && $this->end_time)
		{
			$day = Yii::app()->params['order_limit']['actives_tour']['max_go_time'];
			if($this->go_time <= date('Y-m-d'))
				$this->addError('go_time', '出游时间必须大于今天');
			elseif($this->go_time <= (is_numeric($this->start_time) ? date('Y-m-d',$this->start_time) : $this->start_time))
				$this->addError('go_time', '出游时间不能小于开始时间');
			elseif($this->go_time > (is_numeric($this->end_time) ? date('Y-m-d',$this->end_time+$day*3600*24) : date('Y-m-d',strtotime($this->end_time)+$day*3600*24)))
				$this->addError('go_time', '出游时间最迟不能超过结束时间'.$day.'天');
		}
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if(parent::beforeSave())
		{
			return true;
		}else
			return false;
	}
	
	/**
	 * 获取下单数据 报名
	 */
	public static function get_fare($model)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array('Shops_Actives');
		$criteria->compare('`Shops_Actives`.`order_number`', '>0');						//活动剩余数量		
		$criteria->compare('`Shops_Actives`.`start_time`', '<='.time());					//活动开始时间
		$criteria->compare('`Shops_Actives`.`end_time`', '>'.(time()-3600*24));	//动活结束时间
		$criteria->addCondition('`Shops_Actives`.`go_time`=0 OR `Shops_Actives`.`go_time`>:time');
		$criteria->params[':time']=time();
		
		$criteria->addColumnCondition(array(
				'`Shops_Actives`.`actives_status`'=>Actives::actives_status_start,		//活动开始了
				't.status'=>Shops::status_online,														//上线
				't.audit'=>Shops::audit_pass,																//审核通过
				't.is_sale'=>Shops::is_sale_yes,															//可卖
				't.c_id'=>$model->Shops_ShopsClassliy->id,									//类型
		));	
		$shops=Shops::data($model->id,$criteria);
		if($shops)
		{
			if($shops->Shops_Actives->actives_type == Actives::actives_type_farm)//农产品
				return self::data_json_farm(self::get_fare_farm($shops));
			elseif($shops->Shops_Actives->actives_type == Actives::actives_type_tour)//旅游活动
				return self::data_json_tour(self::get_fare_tour($shops));
			else 
				return null;
		}
		else			
			return null;
	}
	
	/**
	 * 获取活动（旅游）价格列表
	 * @param unknown $model
	 * @return unknown
	 */
	public static function get_fare_tour($model)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array(
				'Shops_Actives',
				'Shops_ShopsClassliy',
				'Shops_OrderActives'=>array(
						'with'=>array(
								'OrderActives_OrderItems'=>array(
										'with'=>array('OrderItems_OrderItemsFare'),
										'order'=>'OrderActives_OrderItems.shops_day_sort,OrderActives_OrderItems.shops_half_sort,OrderActives_OrderItems.shops_sort',
								),
						),
				),
		);
		$criteria->compare('`Shops_Actives`.`order_number`', '>0');//活动剩余数量
		$criteria->compare('`Shops_Actives`.`start_time`', '<='.time());//活动开始时间
		$criteria->compare('`Shops_Actives`.`end_time`', '>'.(time()-3600*24));//动活结束时间
		$criteria->addCondition('`Shops_Actives`.`go_time`=0 OR `Shops_Actives`.`go_time`>:time');
		$criteria->params[':time']=time();
		//1.2.6
		$criteria->addCondition('`Shops_Actives`.`pay_type`=:pay_type_AA OR (`Shops_Actives`.`pay_type`=:pay_type_full AND `Shops_Actives`.`organizer_id`=:user_id)');
		$criteria->params[':pay_type_AA'] = self::pay_type_AA;
		$criteria->params[':pay_type_full'] = self::pay_type_full;
		$criteria->params[':user_id'] = Yii::app()->api->id;
		
		$criteria->addColumnCondition(array(
				'`Shops_Actives`.`actives_type`'=>Actives::actives_type_tour,//活动分类（旅游）
				'`Shops_Actives`.`actives_status`'=>Actives::actives_status_start,//活动开始了
				'`t`.`status`'=>Shops::status_online,//上线
				'`t`.`audit`'=>Shops::audit_pass,//审核通过
				'`t`.`is_sale`'=>Shops::is_sale_yes,//可卖
				'`t`.`c_id`'=>$model->Shops_ShopsClassliy->id,
		));
		return Shops::data($model->id,$criteria);
	}
	
	/**
	 * 
	 * @param unknown $model
	 * @return multitype:
	 */
	public static function data_json_tour($model)
	{
		$return=array();
		if($model)
		{
			if(!isset($model->Shops_ShopsClassliy) || empty($model->Shops_ShopsClassliy))
				return array();
			if(!isset($model->Shops_Actives) || empty($model->Shops_Actives))
				return array();
			if(!isset($model->Shops_OrderActives) || empty($model->Shops_OrderActives))
				return array();
			if(!isset($model->Shops_OrderActives->OrderActives_OrderItems) || empty($model->Shops_OrderActives->OrderActives_OrderItems) || !is_array($model->Shops_OrderActives->OrderActives_OrderItems))
				return array();
			//活动名称
			$return['name'] = $model->name;
			// 活动id
			$return['value'] = $model->id;
			// 商品类型（活动）
			$return['classliy'] = array(
					'name' => $model->Shops_ShopsClassliy->name,
					'value' => $model->Shops_ShopsClassliy->id,
			);
			$return['price']=array(
				'name'=>'服务费/人',
				'value'=>$model->Shops_Actives->tour_price,
			);
			$return['tour_type']=array(
					'name'=>Actives::$_tour_type[$model->Shops_Actives->tour_type],
					'value'=>$model->Shops_Actives->tour_type,
			);
			$return['actives_type']=array(
					'name'=>Actives::$_actives_type[$model->Shops_Actives->actives_type],
					'value'=>$model->Shops_Actives->actives_type,
			);	
			// 是否是组织者
			$return['is_organizer'] = array(
					'name'=>Actives::$_is_organizer[$model->Shops_Actives->is_organizer],
					'value'=>$model->Shops_Actives->is_organizer,
			);
			$return['number']=array(
					'name'=>'觅趣数量',
					'value'=>$model->Shops_Actives->number,
			);
			$return['order_number']=array(
				'name'=>'剩余数量',
				'value'=>$model->Shops_Actives->order_number,
			);
			$return['start_time']=array(
					'name'=>'觅趣开始时间',
					'value'=>date('Y-m-d',$model->Shops_Actives->start_time),
			);
			$return['end_time']=array(
					'name'=>'觅趣结束时间',
					'value'=>date('Y-m-d',$model->Shops_Actives->end_time),
			);
			$return['go_time']=array(
					'name'=>'觅趣出游时间',
					'value'=>$model->Shops_Actives->go_time==0?'未定':date('Y-m-d',$model->Shops_Actives->go_time),
			);
			$return['remark']=array(
					'name'=>'觅趣简介',
					'value'=>$model->Shops_Actives->remark,
			);	
			foreach($model->Shops_OrderActives->OrderActives_OrderItems as $key=>$items)
			{
				if(!isset($items->OrderItems_OrderItemsFare) || empty($items->OrderItems_OrderItemsFare))
					return array();
				foreach ($items->OrderItems_OrderItemsFare as $fare)
				{
					if(! isset($fare->id))
						return array();
					// 项目名称
					$return['dot_list'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_dot_id][$items->shops_sort]['name'] = $items->items_name;
					// 项目id
					$return['dot_list'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_dot_id][$items->shops_sort]['value'] = $items->items_id;
					// 免费项目
					$return['dot_list'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_dot_id][$items->shops_sort]['free_status'] = array(
							'name'=>Items::$_free_status[$items->items_free_status],
							'value'=> $items->items_free_status,
					);
					// 项目类型
					$return['dot_list'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_dot_id][$items->shops_sort]['classliy'] = array(
							'name' => $items->items_c_name,
							'value' => $items->items_c_id,
					);
					// 拼接的约定的规范格式
					$return['OrderItems'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_dot_id]
					[$items->shops_sort][$items->items_id][][$fare->id]= array(
							'price' =>$fare->fare_price,
							'number' => '0',
							'count' => '0.00',
					);
					// 价格信息统计
					if ($items->items_c_id != Items::items_hotel)
					{
						$return['OrderItemsFare'][$fare->fare_info]=array(
								'info'=>$fare->fare_info,
								'number'=>0,
								'is_room'=>isset(Fare::$info_number_room[$fare->fare_info])?Fare::$info_number_room[$fare->fare_info]:Fare::info_adult,
						);
					}
					// 价格信息
					$return['dot_list'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_dot_id][$items->shops_sort]['fare'][] = array(
							'value'=> $fare->id,
							'name' => $fare->fare_name,
							'info' =>  $fare->fare_info,
							'number' => 0,
							'room_number'=> $fare->fare_number,
							'price' =>  $fare->fare_price,
					);
				}
			}
			if(isset($return['OrderItemsFare']))
				$return['OrderItemsFare']=array_values($return['OrderItemsFare']);
			$return['OrderItems']['value']=$model->id;			//活动id		
		}
		return $return;
	}
	
	/**
	 * 获取
	 * @param unknown $shops_id
	 */
	public static function get_actives_tour($shops_id)
	{
		Yii::app()->controller->_class_model='Actives';
		$shops_classliy=ShopsClassliy::getClass();
		$criteria = new CDbCriteria;
		$criteria->with=array(
				'Shops_Actives',
				'Shops_ShopsClassliy',
				'Shops_OrderActives'=>array(
						'with'=>array(
								'OrderActives_OrderItems'=>array(
										'with'=>array('OrderItems_OrderItemsFare'),
										'order'=>'OrderActives_OrderItems.shops_day_sort,OrderActives_OrderItems.shops_half_sort,OrderActives_OrderItems.shops_sort',
								),
						),
				),
		);
		$criteria->compare('`Shops_Actives`.`order_number`', '>0');							//活动剩余数量
		$criteria->compare('`Shops_Actives`.`start_time`', '<='.time());						//活动开始时间
		$criteria->compare('`Shops_Actives`.`end_time`', '>'.(time()-3600*24));		//动活结束时间		
		$criteria->addCondition('`Shops_Actives`.`go_time`=0 OR `Shops_Actives`.`go_time`>:time');
		$criteria->params[':time']=time();
		
		$criteria->addColumnCondition(array(
				'`Shops_Actives`.`pay_type`'=>Actives::pay_type_AA,							//活动AA
				'`Shops_Actives`.`actives_type`'=>Actives::actives_type_tour,				//活动分类（旅游）
				'`Shops_Actives`.`actives_status`'=>Actives::actives_status_start,			//活动开始了
				't.status'=>Shops::status_online,															//上线
				't.audit'=>Shops::audit_pass,																	//审核通过
				't.is_sale'=>Shops::is_sale_yes,																//可卖
				't.c_id'=>$shops_classliy->id,
		));
		$model=Shops::data($shops_id,$criteria);														//加载活动（旅游）信息
		if(! $model)
			return array();
		if(!isset($model->Shops_ShopsClassliy) || empty($model->Shops_ShopsClassliy))
			return array();
		if(! isset($model->Shops_Actives) || empty($model->Shops_Actives))
			return array();
		if(! isset($model->Shops_OrderActives) || empty($model->Shops_OrderActives))
			return array();		
		if(! isset($model->Shops_OrderActives->OrderActives_OrderItems) || empty($model->Shops_OrderActives->OrderActives_OrderItems) || !is_array($model->Shops_OrderActives->OrderActives_OrderItems))
			return array();
		
		$return=array();
		$return['actives_tour_model']=$model;
		Order::$actives_model=$model;
		//剩余的数量
		Order::$order_number=$model->Shops_Actives->order_number;
		//服务费用
		Order::$actives_tour_price=$model->Shops_Actives->tour_price;
		Order::$actives_go_time=$model->Shops_Actives->go_time;
		//遍历数据
		foreach($model->Shops_OrderActives->OrderActives_OrderItems as $key=>$items)
		{
			if(!isset($items->OrderItems_OrderItemsFare) || empty($items->OrderItems_OrderItemsFare) || !is_array($items->OrderItems_OrderItemsFare))
				return array();

			$return['items_list'][$key][$items->items_id]['item_model']=$items;
		
			foreach ($items->OrderItems_OrderItemsFare as $fare)
			{
				$return['items_list'][$key][$items->items_id]['fare_model'][][$fare->id]=$fare;
				// 拼接的约定的规范格式
				$return['data']['OrderItems'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_dot_id]
				[$items->shops_sort][$items->items_id][][$fare->id]= array(
						'price' =>$fare->fare_price,
						'number' => '0',
						'count' => '0.00',
				);
				// 价格信息统计
				if ($items->items_c_id != Items::items_hotel)
				{
					$return['data']['OrderItemsFare'][$fare->fare_info]=array(
							'info'=>$fare->fare_info,
							'number'=>0,
							'is_room'=>isset(Fare::$info_number_room[$fare->fare_info])?Fare::$info_number_room[$fare->fare_info]:Fare::info_adult,
					);
				}
			}
		}
		if(isset($return['data']['OrderItemsFare']))
			$return['data']['OrderItemsFare']=array_values($return['data']['OrderItemsFare']);
		else
			return array();
		return $return;
	}
	
	/**
	 * 农产品
	 */
	public static function get_fare_farm($model)
	{
		$return=array();
		
		return $return;
	}
	
	/**
	 * 农产品 
	 * @param unknown $model
	 * @return multitype:
	 */
	public static function data_json_farm($model)
	{
		$return=array();
	
		return $return;
	}
	
	/**
	 * 验证数据
	 * @param unknown $model
	 */
	public static function validate_actives_tour($model)
	{
		//获取活动id
		if(!isset($_POST['OrderItems']['value']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','AT04');
			return false;
		}
		$shops_id=$_POST['OrderItems']['value'];
		unset($_POST['OrderItems']['value']);

		//获取活动数据array(model,data)
		$shops_data=Actives::get_actives_tour($shops_id);
		Order::$actives_id=$shops_id;

		if(empty($shops_data) || !isset($shops_data['data']['OrderItemsFare']) || empty($shops_data['data']['OrderItemsFare']) || ! is_array($shops_data['data']['OrderItemsFare']))
		{
			$model->addError('order_price','商品卖完了！！！');
			$model->addError('status','AT05');
			return false;
		}
		if(!isset($shops_data['actives_tour_model']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','AT05');
			return false;
		}
		//活动 2016 1 28 组织者不能购买自己的活动  用户可以
		$shops_model=$shops_data['actives_tour_model'];
		if ($shops_model->Shops_Actives->is_organizer == self::is_organizer_yes && $shops_model->Shops_Actives->organizer_id == Yii::app()->api->id)
		{
			$model->addError('order_price','不能购买自己主办的觅趣');
			$model->addError('status','AT05');
			return false;
		}
		if(self::actives_tour_exist_order($shops_id))
		{
			$model->addError('order_price','已经报名了！！！');
			$model->addError('status','AT05');
			return false;
		}
		//订单归属于活动总订单
		$model->order_organizer_id=$shops_model->Shops_OrderActives->id;
		//复制
		$order_shops_model=Shops::set_order_shops($shops_model);
		//价格数据信息
		$fare_data=$shops_data['data']['OrderItemsFare'];
		
		if(count($fare_data) != count($_POST['OrderItemsFare']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','AT06');
			return false;
		}
		//遍历价格选择
		$room_or_info=0;//类型排序
		foreach ($_POST['OrderItemsFare'] as $main_key=>$info)
		{
			if($main_key != $room_or_info || $room_or_info > count(Fare::$info_number_room))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT07');
				return false;
			}
			if(empty($info) || !is_array($info) || !isset($info['info'],$info['number'],$info['is_room']) || count($info) != 3)
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT08');
				return false;
			}
			if(!isset($fare_data[$main_key]['info'],$fare_data[$main_key]['number'],$fare_data[$main_key]['is_room']))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT09');
				return false;
			}
			if(! isset(Fare::$info_number_room[$info['info']]))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT10');
				return false;
			}
			if($fare_data[$main_key]['info'] != $info['info'] || $fare_data[$main_key]['is_room'] != $info['is_room'])
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT11');
				return false;
			}
			if(! Yii::app()->controller->isNumeric($info['number']))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT12');
				return false;
			}
			//购买数量
			if(0 > $info['number'] || $info['number'] > Yii::app()->params['order_limit']['actives_tour']['number'])
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT13');
				return false;
			}
			//成人
			if(Fare::info_adult==Fare::$info_number_room[$info['info']])
			{
				Order::$adult_number = $info['number'];
			}
			elseif(Fare::info_children==Fare::$info_number_room[$info['info']])
			{
				Order::$children_number = $info['number'];
			}
			else
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','AT14');
				return false;
			}
			$room_or_info++;
		}
		//统计随行人员数量
		//$this->retinue_count=$this->children_number+$this->adult_number;
		
		if(!isset($shops_data['data']['OrderItems']) || empty($shops_data['data']['OrderItems']) || ! is_array($shops_data['data']['OrderItems']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','AT15');
			return false;
		}
		//查看数据是否非法
		$items_data=$shops_data['data']['OrderItems'];
		
		//遍历价格选择
		if(isset($_POST['OrderItems']) && is_array($_POST['OrderItems']))
		{
			$OrderShops=array();//商品的数据模型
			//遍历选中点的项目
			$OrderItems=array();
			$items_number=0;//项目
			//天
			foreach ($_POST['OrderItems'] as $day_key=>$dot_key_dot_id)//
			{
				if(empty($dot_key_dot_id) || !is_array($dot_key_dot_id))
				{
					$model->addError('order_price','订单中商品 不是有效值');
					$model->addError('status','AT16');
					return false;
				}
				//点的排序
				foreach ($dot_key_dot_id as $dot_key=>$dot_id_items_key)//
				{
					if(empty($dot_id_items_key) || ! is_array($dot_id_items_key))
					{
						$model->addError('order_price','订单中商品 不是有效值');
						$model->addError('status','AT17');
						return false;
					}
					//点
					foreach ($dot_id_items_key as $dot_id=>$items_key_items_id)//
					{
						if(empty($items_key_items_id) || ! is_array($items_key_items_id))
						{
							$model->addError('order_price','订单中商品 不是有效值');
							$model->addError('status','AT18');
							return false;
						}
						//项目排序
						foreach ($items_key_items_id as $items_key=>$items_id_fare_key)//
						{
							if(empty($items_id_fare_key) || ! is_array($items_id_fare_key))
							{
								$model->addError('order_price','订单中商品 不是有效值');
								$model->addError('status','AT19');
								return false;
							}
							foreach ($items_id_fare_key as $items_id=>$fare_key_fare_id)//
							{
								if(empty($fare_key_fare_id) || ! is_array($fare_key_fare_id))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','AT20');
									return false;
								}
								//判断项目是否存在
								if(! isset($items_data[$day_key][$dot_key][$dot_id][$items_key][$items_id]))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','AT21');
									return false;
								}
								//验证项目
								if(! isset($shops_data['items_list'][$items_number][$items_id]['item_model']))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','AT22');
									return false;
								}
								//项目的对象
								$items_model=$shops_data['items_list'][$items_number][$items_id]['item_model'];
								//复制项目
								$order_shops_items=Items::set_order_items($items_model,array('shops_model'=>$shops_model));
								//遍历项目中的价格
								$OrderItemsFare=array();
								foreach ($fare_key_fare_id as $fare_key=>$fare_id_price_number)
								{
									if(empty($fare_id_price_number) || ! is_array($fare_id_price_number))
									{
										$model->addError('order_price','订单中商品 不是有效值');
										$model->addError('status','AT23');
										return false;
									}
									foreach ($fare_id_price_number as $fare_id=>$price_number)
									{
										//判断价格是否存在
										if(! isset($items_data[$day_key][$dot_key][$dot_id][$items_key][$items_id][$fare_key][$fare_id]))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','AT24');
											return false;
										}
										if(!isset($price_number['price'],$price_number['number'],$price_number['count']))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','AT25');
											return false;
										}
										//价格信息验证
										$price_info=$items_data[$day_key][$dot_key][$dot_id][$items_key][$items_id][$fare_key][$fare_id];
										if(!isset($price_info['price'],$price_info['number'],$price_info['count']))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','AT26');
											return false;
										}
										//价格信息验证
										if( !Yii::app()->controller->isNumeric($price_number['number']) || Yii::app()->controller->floorComp($price_number['price'], $price_info['price']) != 0)
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','AT27');
											return false;
										}
										if (Yii::app()->controller->floorComp(Yii::app()->controller->floorMul($price_number['number'], $price_number['price']), $price_number['count']) != 0)
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','AT28');
											return false;
										}
										//获取价格数据
										if(! isset($shops_data['items_list'][$items_number][$items_id]['fare_model'][$fare_key][$fare_id]))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','AT29');
											return false;
										}
										$fare_model=$shops_data['items_list'][$items_number][$items_id]['fare_model'][$fare_key][$fare_id];//项目价格的对象
											
										$order_items_fare=Fare::set_order_items_fare($fare_model,array(
												'pro_items_model'=>$items_model,
												'price_number'=>$price_number,
												'shops_model'=>$shops_model,
										));
										//线路
										if(! Fare::validate_fare_actives_tour($items_model,$fare_model,$price_number))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','AT29');
											return false;
										}
										//记录项目的总价
										Order::$order_items_money = Yii::app()->controller->floorAdd(Order::$order_items_money, $price_number['count']);
										$OrderItemsFare[] = $order_items_fare;
										continue;
									}
								}
								if(! isset($order_shops_items) || empty($order_shops_items))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','AT30');
									return false;
								}
								if(! isset($OrderItemsFare) || empty($OrderItemsFare))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','AT30');
									return false;
								}
								$order_shops_items->total = Order::$order_items_money;//项目的价格赋值
								Order::$order_items_money = 0.00; //清除项目统计价格
								$order_shops_items->OrderItems_OrderItemsFare = $OrderItemsFare;
								$OrderItems[] = $order_shops_items;
								continue;
							}
							$items_number++;//项目的排序
						}
						if(!isset($OrderItems) || empty($OrderItems))
						{
							$model->addError('order_price','订单中商品 不是有效值');
							$model->addError('status','AT31');
							return false;
						}
						$order_shops_model->OrderShops_OrderItems=$OrderItems;
						continue;
					}
				}
			}
		}
		$OrderShops[]=$order_shops_model;
		if(!isset($OrderShops) || empty($OrderShops) || count($OrderShops) !=1 )
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','AT32');
			return false;
		}
		$model->Order_OrderShops = $OrderShops;
		return true;
	}

	/**
	 * 必须在在事物中 调用
	 *  更新购买的个数
	 * @param unknown $actives_id
	 * @param unknown $number
	 * @return boolean
	 */
	public static function update_order_number($actives_id,$number)
	{
		if($number <= 0)
			return false;
		$criteria = new CDbCriteria;
		$criteria->with=array(
				'Shops_Actives',
		);
		$time=time();
		$criteria->compare('`Shops_Actives`.`order_number`', '>0');//活动剩余数量
		$criteria->compare('`Shops_Actives`.`start_time`', '<='.$time);//活动开始时间
		$criteria->compare('`Shops_Actives`.`end_time`', '>'.($time-3600*24));//动活结束时间	
		$criteria->addColumnCondition(array(
				'`Shops_Actives`.`actives_status`'=>Actives::actives_status_start,//活动开始了
				't.status'=>Shops::status_online,//上线
				't.audit'=>Shops::audit_pass,//审核通过
				't.is_sale'=>Shops::is_sale_yes,//可卖
		));
		$model = Shops::data($actives_id,$criteria);
		if($model && isset($model->Shops_Actives) && !empty($model->Shops_Actives))
		{
			if(($model->Shops_Actives->order_number - $number) >=0)
			{
				if(Actives::model()->updateByPk($actives_id, array(
						'order_number'=>new CDbExpression('`order_number`-:number',array(':number'=>$number))
				)))
				{
					$actives = Actives::model()->findByPk($actives_id);
					if($actives)
						return $actives->order_number >=0;
				}
			}		
		}
		return false;
	}

	/**
	 * 批量=====  必须在在事物中 调用  还原数量和报名人数
	 *
	 * @param unknown $ids array
	 * @return boolean
	 */
	public static function restore_order_number_arr($ids)
	{
		$criteria = new CDbCriteria;
		$criteria->with = array(
			'Order_OrderActives'=>array(
				'select'=>'actives_id',
			),
		);
		$criteria->select = 'user_go_count';
		$criteria->addInCondition('`t`.`id`',$ids);
		$models = Order::model()->findAll($criteria);
		foreach($models as $v)
		{
			if(! self::restore_order_number($v->Order_OrderActives->actives_id,$v->user_go_count))
				return false;
			 if(! self::actives_tour_count_out($v->Order_OrderActives->actives_id))
				 return false;
		}
		return true;
	}
	
	/**
	 * 必须在在事物中 调用  还原数量
	 *
	 * @param unknown $actives_id
	 * @param unknown $number
	 * @return boolean
	 */
	public static function restore_order_number($actives_id,$number)
	{
		if($number <= 0)
			return false;
		$criteria = new CDbCriteria;
		$criteria->compare('`t`.`order_number`', '>=0');			//活动剩余数量
		$criteria->compare('`t`.`start_time`', '<='.time());			//活动开始时间
		$model = Actives::model()->findByPk($actives_id,$criteria);
		if($model && ($model->order_number+$number <= $model->number))
		{
			$return = Actives::model()->updateByPk($actives_id, array(
					'order_number'=>new CDbExpression('`order_number`+:number',array(':number'=>$number))
			));
			if($return)
			{
				$actives = Actives::model()->findByPk($actives_id);
				if($actives)
					return $actives->order_number <= $actives->number;
			}
		}
		return false;
	}
	
	/**
	 * 报名数量 +1
	 * @param unknown $actives_id
	 * @return boolean
	 */
	public static function actives_tour_count_sign($actives_id)
	{
		return self::model()->updateByPk($actives_id, array(
				'tour_count'=>new CDbExpression('`tour_count`+1')
		));
	}
	
	/**
	 * 	 取消报名 取消订单 还原报名统计
	 * @param unknown $actives_id
	 * @param unknown $actives_id
	 * @return boolean
	 */
	public static function actives_tour_count_out($actives_id,$number=1)
	{
		if( self::model()->updateByPk($actives_id, array(
				'tour_count'=>new CDbExpression('`tour_count`-:number',array(':number'=>$number))
		)))
		{
			$model=self::model()->findByPk($actives_id);
			if($model)
			{
				return $model->tour_count >= 0;
			}
		}
		return false;
	}
	
	/**
	 * 支付成功回调的函数
	 * @param unknown $actives_id
	 * @return boolean
	 */
	public static function actives_order_count($actives_id)
	{
		return self::model()->updateByPk($actives_id, array(
				'order_count'=>new CDbExpression('`order_count`+1')
		));
	}
	
	/**
	 * 验证用户是否在这个活动是否有有效的报名
	 * @param unknown $actives_id
	 * @return model
	 */
	public static function actives_tour_exist_order($actives_id)
	{
		if(! Yii::app()->api->id)
			return null;
		$criteria = new CDbCriteria;
		$criteria->with=array(
			'Actives_OrderActives'=>array(
				'with'=>array('OrderActives_Order'),
			),
		);
		$criteria->compare('`t`.`start_time`', '<='.time());//活动开始时间
		$column='`OrderActives_Order`.`order_status`';
		$criteria->addCondition($column.'=:yes OR '.$column.'=:pay OR '.$column.'=:use');
		$criteria->params[':yes']=Order::order_status_store_yes;
		$criteria->params[':pay']=Order::order_status_user_pay;
		$criteria->params[':use']=Order::order_status_user_use;
		$criteria->addColumnCondition(array(
				'`OrderActives_Order`.`status`'=>Order::status_yes,
				'`OrderActives_Order`.`user_id`'=>Yii::app()->api->id,
		));
			
		return self::model()->findByPk($actives_id,$criteria);
	}

	/**
	 * 计算活动的成人 总价======前
	 * @param $id
	 * @return int
	 */
	public static function shops_price_num($id)
	{
		$criteria = new CDbCriteria;
		$criteria->select='`t`.`id`';
		$criteria->with=array(
			'Pro_ProFare'=>array(
				'select'=>'`id`',
				'with'=>array(	
					'ProFare_Fare'=>array('select'=>'`price`')
				),
			),
		);
		$criteria->addColumnCondition(array(
				'`t`.`shops_id`'=>$id,
		));
		$criteria->addColumnCondition(array(
			'`ProFare_Fare`.`c_id`'=>Items::items_hotel, 							//住
			'`ProFare_Fare`.`info`'=>Fare::$__info[Fare::info_adult] 			//成人
		),'OR');
	
		$models=Pro::model()->findAll($criteria);	
		$num = 0.00;
		foreach ($models as $model)
		{
			foreach ($model->Pro_ProFare as $fare)
				$num = Yii::app()->controller->floorAdd($num, $fare->ProFare_Fare->price);
		}
		return $num;
	}

	/**
	 * 计算活动的成人 总价======后
	 * @param $id
	 * @return int
	 */
	public static function shops_price_num_after($id)
	{
		$criteria = new CDbCriteria;
		$criteria->select='`t`.`fare_price`';
		$criteria->with=array(
			'OrderItemsFare_OrderActives'=>array('select'=>'`id`',),
		);	
		$criteria->addColumnCondition(array(
				'`OrderItemsFare_OrderActives`.`actives_id`'=>$id,
		));
		$criteria->addColumnCondition(array(
				'`t`.`items_c_id`'=>Items::items_hotel, 					//住
				'`t`.`fare_info`'=>Fare::$__info[Fare::info_adult] 		//成人
		),'OR');
		$models=OrderItemsFare::model()->findAll($criteria);
		
		$num = 0.00;	
		foreach ($models as $model)
			$num = Yii::app()->controller->floorAdd($num, $model->fare_price);

		return $num;
	}

	/**
	 * 获取下单量 之后的
	 */
	public static function get_down($id)
	{
		$criteria =new CDbCriteria;
 		$criteria->select='SUM(`OrderItems_Items`.`down`) AS search_end_time';
		$criteria->with=array(
				'Actives_OrderActives'=>array(
						'select'=>'`id`',
						'with'=>array(
								'OrderActives_OrderItems'=>array(
										'select'=>'`id`',
										'with'=>array('OrderItems_Items'=>array('select'=>'`id`')),
								),
						),
				),
		);
		$model=self::model()->findByPk($id,$criteria);
		if($model && $model->search_end_time)
			return $model->search_end_time;
		return 0;
	}
	
	/**
	 * 获取下单量 之前的
	 */
	public static function get_down_before($id)
	{
		$criteria =new CDbCriteria;
		$criteria->select='SUM(`Pro_Items`.`down`) AS search_end_time';
		$criteria->with=array(
				'Actives_Pro'=>array(
						'select'=>'`id`',
						'with'=>array(
								'Pro_Items'=>array(
										'select'=>'`id`',
								),
						),
				),
		);
		$model=self::model()->findByPk($id,$criteria);
		
		if($model && $model->search_end_time)
			return $model->search_end_time;
		
		return 0;
	}
	
	/**
	 * 活动是否有创建订单
	 * @param unknown $id
	 * @return boolean
	 */
	public static function isCreateOrder($id)
	{
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array(
			'actives_type'=>OrderActives::actives_type_tour,
			'user_order_count'=>0,
			'actives_id'=>$id,
		));
		if (OrderActives::model()->find($criteria))
			return false;
		return true;
	}
}
