<?php
/**
 * This is the model class for table "{{shops}}".
 * The followings are the available columns in table '{{shops}}':
 * @property string $id
 * @property string $c_id
 * @property string $agent_id
 * @property string $name
 * @property string $list_img
 * @property string $page_img
 * @property string $list_info
 * @property string $page_info
 * @property string $brow
 * @property string $share
 * @property string $praise
 * @property integer $pub_time
 * @property integer $audit
 * @property integer $selected_time
 * @property integer $selected
 * @property text $tags_ids
 * @property string 	$selected_info
 * @property integer $is_sale
 * @property integer $add_time
 * @property integer $up_time
 * @property integer $status
 * @property integer $tops
 * @property integer $tops_time
 * @property integer $selected_tops
 * @property integer $selected_tops_time
 * @property integer $cost_info
 * @property integer $book_info
 * @property integer $hot
 * @property integer $hot_time
 */
class Shops extends CActiveRecord
{
	/**
	 * 上线
	 * @var integer
	 */
	const status_online=1;
	/**
	 * 下线
	 * @var integer
	 */
	const status_offline=0;
	/**
	 * 删除
	 * @var integer
	 */
	const status_del=-1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','下线','上线');
	
	/**
	 * 未推荐
	 * @var unknown
	 */
	const selected_not=0;
	/**
	 * 推荐
	 * @var unknown
	 */
	const selected=1;
	/**
	 * 解释字段 selected 的含义
	 * @var array
	 */
	public static $_selected=array('未推荐','已推荐');
	
	/**
	 * 不可卖
	 * @var unknown
	 */
	const is_sale_not=0;
	/**
	 * 可卖
	 * @var unknown
	 */
	const is_sale_yes=1;	
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_is_sale=array('非卖品','可卖');
	
	/**
	 * 置顶状态 0 未置顶
	 * @var unknown
	 */
	const tops_no = 0;
	/**
	 * 置顶状态 1 置顶
	 * @var unknown
	 */
	const tops_yes = 1;
	/**
	 * 解释字段 $_is_tops 的含义
	 * @var array
	 */
	public static $_tops=array(0=>'未置顶','已置顶');

	/**
	 * 推荐置顶状态  未置顶
	 * @var unknown
	 */
	const selected_tops_no = 0;
	/**
	 * 置推荐顶状态  置顶
	 * @var int
	 */
	const selected_tops_yes = 1;
	/**
	 * 解释字段 $_is_selected_tops 的含义
	 * @var array
	 */
	public static $_selected_tops=array(0=>'未置顶','已置顶');

	/**
	 * 热门状态  未热门
	 * @var unknown
	 */
	const hot_no = 0;
	/**
	 * 热门状态  热门
	 * @var int
	 */
	const hot_yes = 1;
	/**
	 * 解释字段 $_hot 的含义
	 * @var array
	 */
	public static $_hot=array(0=>'未热门','已热门');
	
	/**
	 * 商品类型 点
	 * @var int
	 */
 	const shops_dot = 1;
 	/**
 	 * 商品类型 线
 	 * @var int
 	 */
 	const shops_thrand = 2;
 	/**
 	 * 商品类型 活动
 	 * @var int
 	 */
 	const shops_actives = 3;
 	/**
 	 * 商品的类型
 	 * @var array
 	 */
 	public static $_shops=array(
		 	self::shops_dot => '景点',
		 	self::shops_thrand => '线路',
		 	self::shops_actives => '觅趣',
 	);
 	
 	/**
 	 * 商家审核未通过
 	 * @var integer
 	 */
 	const audit_store_nopass=-4;
 	/**
 	 * 商家审核中
 	 * @var integer
 	 */
 	const audit_store_pending=-3;
 	/**
 	 * 未提交
 	 * @var integer
 	 */
 	const audit_draft=-2;
 	/**
 	 * 平台审核未通过
 	 * @var integer
 	 */
 	const audit_nopass=-1;
 	/**
 	 * 平台审核中
 	 * @var integer
 	 */
 	const audit_pending=0;
 	/**
 	 *平台审核通过
 	 * @var unknown
 	 */
 	const audit_pass=1;
	/**
	 * 解释字段 audit 的含义
	 * @var array
	 */
	public static $_audit=array(
			-2=>'未提交',
			-1=>'未通过',
			0=>'待审核',
			1=>'已通过'
	);
	/**
	 * 解释字段 audit 日志类型
	 * @var array
	 */
	public static $__audit = array(
			self::shops_dot 	=> AuditLog::shops_dot,
			self::shops_thrand => AuditLog::shops_thrand,
			self::shops_actives => AuditLog::shops_actives,
	);
	
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('发布时间', '创建时间', '更新时间', '置顶时间', '推荐时间', '推荐置顶时间', '热门时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('pub_time', 'add_time', 'up_time', 'tops_time', 'selected_time', 'selected_tops_time', 'hot_time'); 
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
		return '{{shops}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('c_id, agent_id, pub_time', 'required'),
			array('hot, hot_time, selected_tops_time,selected_tops,tops_time,tops,audit,selected,selected_time,add_time,up_time,pub_time, status', 'numerical', 'integerOnly'=>true),
			array('c_id, agent_id, brow, share, praise', 'length', 'max'=>11),
			//array('name', 'length', 'max'=>24),
			array('list_img, page_img, list_info, page_info,selected_info', 'length', 'max'=>128),
			array('audit, add_time, up_time,selected_time,tops_time,selected_tops_time', 'length', 'max'=>10),
			
			array('name', 'length', 'max'=>15),
			array('list_info', 'length', 'max'=>30),
			array('page_info', 'length', 'max'=>50),
				
			//创建点 修改点 /*,cost_info,book_info*/
			array('name', 'required','on'=>'create_dot,update_dot'),
			array('name,cost_info,book_info','safe','on'=>'create_dot,update_dot'),
			array('hot, hot_time,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,selected,selected_time,selected_info,search_start_time,search_end_time,id,c_id, agent_id, list_img, page_img, list_info, page_info, brow, share, praise, pub_time, audit, add_time, up_time, status','unsafe','on'=>'create_dot,update_dot'),
			
			//选择创建点、线
			array('c_id', 'required','on'=>'select_create'),
			array('c_id','safe','on'=>'select_create'),
			array('hot, hot_time,tags_ids,search_time_type,search_start_time,search_end_time,id, agent_id,name, list_img, page_img, list_info, page_info, brow, share, praise, pub_time, audit, add_time, up_time, status','unsafe','on'=>'select_create'),

			//包装 点
			array('list_info, page_info','required','on'=>'pack_dot'),
			array(
				'list_img, page_img','file','allowEmpty'=>true,
				'types'=>'jpg,png', 'maxSize'=>1024*1024*2,
				'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'{attribute} 格式错误',
				'on'=>'pack_dot',
			),
			array('list_info, page_info', 'safe', 'on'=>'pack_dot'),
			array('hot, hot_time,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,selected,selected_time,selected_info,search_start_time,search_end_time,id, c_id, agent_id, name, list_img, page_img, brow, share, praise, pub_time, audit, add_time, up_time, status', 'unsafe', 'on'=>'pack_dot'),

			//包装 线
			array('list_info, page_info','required','on'=>'pack_thrand'),
			array(
				'list_img, page_img','file','allowEmpty'=>true,
				'types'=>'jpg,png', 'maxSize'=>1024*1024*2,
				'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'{attribute} 格式错误',
				'on'=>'pack_thrand',
			),
			array('list_info, page_info', 'safe', 'on'=>'pack_thrand'),
			array('hot, hot_time,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,search_start_time,selected,selected_time,selected_info,search_end_time,id, c_id, agent_id, name, list_img, page_img, brow, share, praise, pub_time, audit, add_time, up_time, status', 'unsafe', 'on'=>'pack_thrand'),

			//包装 结伴游
			array('list_info, page_info','required','on'=>'pack_group'),
			array(
				'list_img, page_img','file','allowEmpty'=>true,
				'types'=>'jpg,png', 'maxSize'=>1024*1024*2,
				'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'{attribute} 格式错误',
				'on'=>'pack_group',
			),
			array('list_info, page_info', 'safe', 'on'=>'pack_group'),
			array('hot, hot_time,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,search_start_time,selected,selected_time,selected_info,search_end_time,id, c_id, agent_id, name, list_img, page_img, brow, share, praise, pub_time, audit, add_time, up_time, status', 'unsafe', 'on'=>'pack_group'),

			//包装 活动
			array('list_info, page_info','required','on'=>'pack_actives'),
			array(
				'list_img, page_img','file','allowEmpty'=>true,
				'types'=>'jpg,png', 'maxSize'=>1024*1024*2,
				'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'{attribute} 格式错误',
				'on'=>'pack_actives',
			),
			array('list_info, page_info', 'safe', 'on'=>'pack_actives'),
			array('hot, hot_time,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,search_start_time,selected,selected_time,selected_info,search_end_time,id, c_id, agent_id, name, list_img, page_img, brow, share, praise, pub_time, audit, add_time, up_time, status', 'unsafe', 'on'=>'pack_actives'),


			//创建 修改线路(线)
			array('name','required','on'=>'create_thrand,update_thrand,create_actives,update_actives'),
			array('name,cost_info,book_info', 'safe', 'on'=>'create_thrand,update_thrand,create_actives,update_actives'),
			array('hot, hot_time,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,search_start_time,search_end_time,id, selected,selected_time,selected_info,c_id, agent_id, list_img, page_img, list_info, page_info, brow, share, praise, pub_time, audit, add_time, up_time, status', 'unsafe', 'on'=>'create_thrand,update_thrand,create_actives,update_actives'),
				
			//推荐线路
			array('selected_info','required','on'=>'selected'),
			array('selected_info', 'safe', 'on'=>'selected'),
			array('hot, hot_time,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,search_start_time,search_end_time,id, selected,selected_time,name,c_id, agent_id, list_img, page_img, list_info, page_info, brow, share, praise, pub_time, audit, add_time, up_time, status', 'unsafe', 'on'=>'selected'),

			// api 修改线路  2016-2-23
			array('name','required','on'=>'api_actives_shops_name'),
			array('name', 'safe', 'on'=>'api_actives_shops_name'),
			array('hot, hot_time,cost_info,book_info,selected_tops_time,selected_tops,tops_time,tops,tags_ids,search_time_type,search_start_time,search_end_time,id, selected,selected_time,selected_info,c_id, agent_id, list_img, page_img, list_info, page_info, brow, share, praise, pub_time, audit, add_time, up_time, status', 'unsafe', 'on'=>'api_actives_shops_name'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tags_ids, hot, hot_time, cost_info,book_info,selected_tops_time,selected_tops,tops_time,tops,is_sale,tags_ids,search_time_type,search_start_time,selected,selected_time,selected_info,search_end_time,id, c_id, agent_id, name, list_img, page_img, list_info, page_info, brow, share, praise, pub_time, audit, add_time, up_time, status', 'safe', 'on'=>'search'),
			//运营商搜索
			array('tags_ids, hot, hot_time, cost_info,book_info,selected_tops_time,selected_tops,tops_time,tops,is_sale,tags_ids,search_time_type,search_start_time,selected,selected_time,selected_info,search_end_time,id, c_id, agent_id, name, list_img, page_img, list_info, page_info, brow, share, praise, pub_time, audit, add_time, up_time, status', 'safe', 'on'=>'operatorSearch'),
			//创建线路 搜索
			array('cost_info,book_info,is_sale, id, agent_id, name, list_info, page_info, brow, share, praise, pub_time, add_time, up_time', 'safe', 'on'=>'createThrandSearch'),
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
			// 点，线，结伴游
			'Shops_Thrand'=>array(self::HAS_ONE,'Thrand','id'),
			//关联点
			'Shops_Dot'=>array(self::HAS_ONE,'Dot','id'),
			// 关联结伴游
			'Shops_Group'=>array(self::HAS_ONE, 'Group', 'id'),
			//关联活动表
			'Shops_Actives'=>array(self::HAS_ONE, 'Actives', 'id'),
			//对应线路类型
			'Shops_ShopsClassliy'=>array(self::BELONGS_TO,'ShopsClassliy','c_id'),
			//所属代理商
			'Shops_Agent'=>array(self::BELONGS_TO,'Agent','agent_id'),
			//线路关联的标签        一对多
			'Shops_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),				
			//线路(点)关联 选中项目表 (一对多)
			'Shops_Pro'=>array(self::HAS_MANY,'Pro','shops_id'),
			//一对一
			'Shops_Pro_ONE'=>array(self::HAS_ONE, 'Pro', 'shops_id'),
			//商品与点赞关系表
			'Shops_Collect'=>array(self::HAS_MANY,'Collect','shops_id'),
			//活动关联活动总订单
			'Shops_OrderActives'=>array(self::HAS_ONE,'OrderActives','actives_id'),
			//被多个选择
			'Shops_Select'=>array(self::HAS_MANY, 'Select', 'select_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'c_id' => '类型',
			'agent_id' => '运营商',
			'name' => '名称',
			'list_img' => '列表图',
			'page_img' => '详情图',
			'list_info' => '列表简介',
			'page_info' => '详情简介',
			'brow' => '浏览量',
			'share' => '分享量',
			'praise' => '点赞量',
			'pub_time' => '通过时间',
			'audit' => '审核',
			'selected'=>'推荐',
			'tags_ids'=>'TAG',
			'selected_time'=>'推荐时间',
			'selected_info'=>'推荐理由',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'is_sale' => '可卖',
			'status' => '状态',
			'hot'=>'热门',
			'hot_time'=>'热门时间',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'tops'=> '置顶',
			'tops_time'=> '置顶时间',
			'selected_tops'=> '推荐置顶',
			'selected_tops_time'=>'推顶时间',
			'cost_info'=>'费用包含',
			'book_info'=>'预订须知',
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
			if ($this->status != self::status_del)
				$criteria->compare('t.status','<>' . self::status_del);			
			$criteria->with=array(
					'Shops_Agent',
					'Shops_ShopsClassliy',
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
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.c_id',$this->c_id,true);	
			if ($this->agent_id != '')
			{
				$criteria->addCondition('Shops_Agent.phone LIKE :agent_id OR Shops_Agent.firm_name LIKE :agent_id');
				$criteria->params[':agent_id'] = '%'.strtr($this->agent_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.list_info',$this->list_info,true);
			$criteria->compare('t.page_info',$this->page_info,true);
			$criteria->compare('t.cost_info',$this->cost_info,true);
			$criteria->compare('t.book_info',$this->book_info,true);
			$criteria->compare('t.selected_info',$this->selected_info,true);
			$criteria->compare('t.brow',$this->brow,true);
			$criteria->compare('t.share',$this->share,true);
			$criteria->compare('t.praise',$this->praise,true);
			$criteria->compare('t.is_sale',$this->is_sale);
			$criteria->compare('t.selected',$this->selected);
			$criteria->compare('t.tags_ids', $this->tags_ids , true);
			if($this->hot_time != '')
				$criteria->addBetweenCondition('t.hot_time',strtotime($this->hot_time),(strtotime($this->hot_time)+3600*24-1));
			$criteria->compare('t.hot',$this->hot);
			if($this->selected_time != '')
				$criteria->addBetweenCondition('t.selected_time',strtotime($this->selected_time),(strtotime($this->selected_time)+3600*24-1));
			$criteria->compare('t.tops',$this->tops);
			if($this->tops_time != '')
				$criteria->addBetweenCondition('t.tops_time',strtotime($this->tops_time),(strtotime($this->tops_time)+3600*24-1));
			$criteria->compare('t.selected_tops',$this->selected_tops);
			if($this->selected_tops_time != '')
				$criteria->addBetweenCondition('t.selected_tops_time',strtotime($this->selected_tops_time),(strtotime($this->selected_tops_time)+3600*24-1));
			if($this->pub_time != '')
				$criteria->addBetweenCondition('t.pub_time',strtotime($this->pub_time),(strtotime($this->pub_time)+3600*24-1));
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			
			$criteria->compare('audit',$this->audit);
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
	 * 选择觅境搜索
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function selectSearch($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if ($criteria ==='')
		{
			$criteria=new CDbCriteria;
			$criteria->with=array(
					'Shops_Agent',
					'Shops_ShopsClassliy',
			);
			$criteria->addColumnCondition(array(
				't.status'=>self::status_online,
			));
			//觅境
			$criteria->addCondition('t.c_id=:dot OR t.c_id=:thrand');
			$criteria->params[':dot'] = self::shops_dot;
			$criteria->params[':thrand'] = self::shops_thrand;
			
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.c_id',$this->c_id,true);
			if ($this->agent_id != '')
			{
				$criteria->addCondition('Shops_Agent.phone LIKE :agent_id OR Shops_Agent.firm_name LIKE :agent_id');
				$criteria->params[':agent_id'] = '%'.strtr($this->agent_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.list_info',$this->list_info,true);
			$criteria->compare('t.page_info',$this->page_info,true);
			$criteria->compare('t.cost_info',$this->cost_info,true);
			$criteria->compare('t.book_info',$this->book_info,true);
			$criteria->compare('t.selected_info',$this->selected_info,true);
			$criteria->compare('t.brow',$this->brow,true);
			$criteria->compare('t.share',$this->share,true);
			$criteria->compare('t.praise',$this->praise,true);
			$criteria->compare('t.is_sale',$this->is_sale);
			$criteria->compare('t.selected',$this->selected);
			$criteria->compare('t.tags_ids', $this->tags_ids , true);
			if($this->hot_time != '')
				$criteria->addBetweenCondition('t.hot_time',strtotime($this->hot_time),(strtotime($this->hot_time)+3600*24-1));
			$criteria->compare('t.hot',$this->hot);
			if($this->selected_time != '')
				$criteria->addBetweenCondition('t.selected_time',strtotime($this->selected_time),(strtotime($this->selected_time)+3600*24-1));
			$criteria->compare('t.tops',$this->tops);
			if($this->tops_time != '')
				$criteria->addBetweenCondition('t.tops_time',strtotime($this->tops_time),(strtotime($this->tops_time)+3600*24-1));
			$criteria->compare('t.selected_tops',$this->selected_tops);
			if($this->selected_tops_time != '')
				$criteria->addBetweenCondition('t.selected_tops_time',strtotime($this->selected_tops_time),(strtotime($this->selected_tops_time)+3600*24-1));
			if($this->pub_time != '')
				$criteria->addBetweenCondition('t.pub_time',strtotime($this->pub_time),(strtotime($this->pub_time)+3600*24-1));
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
				
			$criteria->compare('audit',$this->audit);
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
	 * 运营商的搜索
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
 	public function operatorSearch($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if ($criteria ==='')
		{
			$criteria=new CDbCriteria;
			//秘境
			$criteria->addCondition('c_id=:dot OR c_id=:thrand');
			$criteria->params[':dot'] = Shops::shops_dot;
			$criteria->params[':thrand'] = Shops::shops_thrand;
			//删除不显示
			$criteria->compare('status','<>' . self::status_del);
			//标准条件
			$criteria->addColumnCondition(array(
				'agent_id'=>Yii::app()->operator->id,
			));
			//时间
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
			$criteria->compare('c_id',$this->c_id);		
			$criteria->compare('name',$this->name,true);
			$criteria->compare('list_info',$this->list_info,true);
			$criteria->compare('page_info',$this->page_info,true);
			$criteria->compare('cost_info',$this->cost_info,true);
			$criteria->compare('book_info',$this->book_info,true);
			$criteria->compare('is_sale',$this->is_sale);
			if($this->pub_time != '')
				$criteria->addBetweenCondition('pub_time',strtotime($this->pub_time),(strtotime($this->pub_time)+3600*24-1));
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('audit',$this->audit);
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
	 * @return Shops the static model class
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
		if(parent::beforeSave()){		
			if($this->isNewRecord)
				$this->up_time = $this->add_time = $this->pub_time = time();
			else
				$this->up_time = time();
			return true;
		}else
			return false;
	}

	/**
	 * 设置 浏览量
	 * @param $shops_id
	 * @return int
	 */
	public static function set_shops_brow($shops_id)
	{
		return self::model()->updateByPk($shops_id, array(
			'brow'=>new CDbExpression('`brow` + :brow',array(':brow'=>1)),
		));
	}

	/**
	 * 设置点赞数
	 * @param $shops_id
	 * @param $collect_status
	 * @return int
	 */
	public static  function set_shops_collect($shops_id,$collect_status)
	{
		$symbol = $collect_status==Collect::is_collect ? '+':'-';
		return self::model()->updateByPk($shops_id, array(
			'praise'=>new CDbExpression('`praise` '.$symbol.' :praise',array(':praise'=>1)),
		));
	}

	/**
	 * 设置分享数
	 * @param $shops_id
	 * @param $share_status
	 * @return int
	 */
	public static  function set_shops_share($shops_id,$share_status=1)
	{
		$symbol = $share_status ? '+':'-';
		return self::model()->updateByPk($shops_id, array(
			'share'=>new CDbExpression('`share` '.$symbol.' :share',array(':share'=>1)),
		));
	}
	/**
	 * 获取订单数据
	 * @param unknown $id
	 * @param string $_class_model
	 */
	public static function fare_shops($id)
	{
		$criteria = new CDbCriteria;
		$criteria->with = 'Shops_ShopsClassliy';
		$criteria->addColumnCondition(array(
				't.status'=>Shops::status_online,		//上线
				't.audit'=>Shops::audit_pass,				//审核通过
				't.is_sale'=>Shops::is_sale_yes,			//可卖
		));
		$model = self::model()->findByPk($id,$criteria);
		if($model)
		{
			$append=$model->Shops_ShopsClassliy->append;
			return $append::get_fare($model);		//附加模型中处理数据
		}else
			return null;
	}
	
	/**
	 * 获取数据
	 * @param unknown $pk
	 * @param string $condition
	 */
	public static  function data($pk,$condition='')
	{
		return self::model()->findByPk($pk,$condition);
	}
	
	/**
	 * 订单中验证商品
	 */
	public static function validate_shops($model)
	{
		if(isset($_POST['OrderItems']) && is_array($_POST['OrderItems']))
		{
			if($model->order_type==Order::order_type_dot) //自助游多个点
			{
				if(count($_POST['OrderItems']) != 1 || key($_POST['OrderItems']) != 0)
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','S01');
					return false;
				}
				if(! Dot::validate_dot($model))
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','S02');
					return false;
				}
				if(Order::$order_items_count >Yii::app()->params['order_limit']['dot']['items_count'] || Order::$order_items_count<1)
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','S02');
					return false;
				}
				return true;
			}
			elseif($model->order_type == Order::order_type_thrand) //自助游一条线
			{
				if(count($_POST['OrderItems']) < 1 || key($_POST['OrderItems']) < 1)
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','S01');
					return false;
				}
				if(!isset($_POST['OrderItemsFare']) || empty($_POST['OrderItemsFare']) || !is_array($_POST['OrderItemsFare']))
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','T01');
					return false;
				}
				if(! Thrand::validate_thrand($model))
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','T02');
					return false;
				}else
					return true;
			}
			elseif($model->order_type == Order::order_type_actives_tour)//活动（旅游）
			{
				if(count($_POST['OrderItems']) < 1 || key($_POST['OrderItems']) < 1)
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','AT01');
					return false;
				}
				if(!isset($_POST['OrderItemsFare']) || empty($_POST['OrderItemsFare']) || !is_array($_POST['OrderItemsFare']))
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','AT01');
					return false;
				}
				if(! Actives::validate_actives_tour($model))
				{
					$model->addError('order_type','订单中的商品 不是有效值');
					$model->addError('status','T02');
					return false;
				}else
					return true;		
			}
			elseif($model->order_type == Order::order_type_actives_farm)
			{
				
				return false;
			}
			else
			{
				$model->addError('order_type','订单类型 不是有效值');
				$model->addError('status','T01');
				return false;
			}
		}
		else
		{
			$model->addError('order_price','订单中的商品 不是有效值');
			$model->addError('status','S03');
			return false;
		}
	}
	
	/**
	 * 设置订单复制商品表
	 */
	public static function set_order_shops($data,$params=array())
	{
		$model=new OrderShops;
		$model->scenario='create';
		$model->user_id=Yii::app()->api->id;
		$model->shops_id=$data->id;
		$model->shops_c_id=$data->Shops_ShopsClassliy->id;
		$model->shops_c_name=$data->Shops_ShopsClassliy->name;
		$model->shops_agent_id=$data->agent_id;
		$model->shops_name=$data->name;
		$model->shops_list_img=$data->list_img;
		$model->shops_page_img=$data->page_img;
		$model->shops_list_info=$data->list_info;
		$model->shops_page_info=$data->page_info;
		$model->shops_cost_info=$data->cost_info;
		$model->shops_book_info=$data->book_info;
		$model->shops_pub_time=$data->pub_time;
		$model->shops_add_time=$data->add_time;
		$model->shops_up_time=$data->up_time;
		if(isset($data->Shops_Actives))//活动的商品复制
		{
			$model->actives_type=$data->Shops_Actives->actives_type;
			$model->actives_organizer_id=$data->Shops_Actives->organizer_id;
			$model->actives_tour_type=$data->Shops_Actives->tour_type;
			$model->tour_price=$data->Shops_Actives->tour_price;
			$model->remark=$data->Shops_Actives->remark;
			$model->start_time=$data->Shops_Actives->start_time;
			$model->end_time=$data->Shops_Actives->end_time;
			$model->pub_time=$data->Shops_Actives->pub_time;
		}
		return $model;
	}
	
	/**
	 * 获取下单量
	 * @param unknown $id
	 */
	public static function get_down($id,$classliy='',$before=false)
	{
		if($classliy == '')
		{
			$criteria =new CDbCriteria;
			$criteria->with=array(
				'Shops_ShopsClassliy',
			);
			$model=self::model()->findByPk($id,$criteria);
			if($model)
				$classliy=$model->Shops_ShopsClassliy->append;
		}
		$action='get_down';
		if($classliy == 'Actives' && $before)
			$action='get_down_before';
			
		if(in_array($classliy, array('Dot','Thrand','Actives')))
			return $classliy::get_down($id);	
		return 0;
	}

	/**
	 *获取价格
	 */
	public static function get_price_num($id,$classliy='',$before=false)
	{
		if($classliy == '')
		{
			$criteria =new CDbCriteria;
			$criteria->with=array(
				'Shops_ShopsClassliy',
			);
			$model=self::model()->findByPk($id,$criteria);
			if($model)
				$classliy=$model->Shops_ShopsClassliy->append;
		}
		$fun_type = 'shops_price_num';
		if( $classliy == 'Actives' &&  $before )
			 $fun_type = 'shops_price_num_after';
		if(in_array($classliy, array('Dot','Thrand','Actives')))
			  return $classliy::$fun_type($id);

		return 0;
	}


}

