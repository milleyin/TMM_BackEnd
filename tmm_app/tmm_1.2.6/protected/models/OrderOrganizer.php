<?php

/**
 * This is the model class for table "{{order_organizer}}".
 *
 * The followings are the available columns in table '{{order_organizer}}':
 * @property string $id
 * @property string $group_no
 * @property string $organizer_id
 * @property string $group_id
 * @property string $shops_name
 * @property string $shops_c_id
 * @property string $shops_c_name
 * @property string $shops_list_img
 * @property string $shops_page_img
 * @property string $shops_list_info
 * @property string $shops_page_info
 * @property integer $shops_pub_time
 * @property string $shops_up_time
 * @property string $shops_add_time
 * @property string $group_price
 * @property string $group_remark
 * @property string $group_start_time
 * @property string $group_end_time
 * @property string $group_group_time
 * @property integer $group_group
 * @property string $user_order_count
 * @property string $user_pay_count
 * @property string $user_submit_count
 * @property string $user_price
 * @property string $user_go_count
 * @property string $user_price_count
 * @property string $total
 * @property integer $is_organizer
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class OrderOrganizer extends CActiveRecord
{
	
	/*********结伴中 => 用户确认中 =>商家确认中 => 已结伴 (未结伴) *******/
	/**
	 * 未结伴
	 * @var integer
	 */
	const group_group_no_peer=-3;
	/**
	 * 创建 待审核或审核未通过
	 * @var integer
	 */
	const group_none=-2;
	/**
	 * 已取消
	 * @var integer
	 */
	const group_group_cancel=-1;
	/**
	 * 结伴中
	 * @var integer
	 */
	const group_group_peering=0;
	/**
	 * 用户确认中
	 * @var integer
	 */
	const group_group_user_confirm=1;
	/**
	 * 商家确认中
	 * @var integer
	 */
	const group_group_store_confirm=2;
	/**
	 * 已结伴
	 * @var integer
	 */
	const group_group_already_peer=3;

	/**
	 * 解释字段 group 的含义
	 * @var array
	 */
	public static $_group_group=array(
			-3=>'未结伴',
			-2=>'待审核或审核未通过',
			-1=>'已取消',
			0=>'结伴中',
			1=>'用户确认中',
			2=>'商家确认中',
			3=>'已结伴',
	);

	public static $_is_organizer=array(-1=>'不去','没','选择去');
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','下线','上线');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('shops_pub_time','shops_up_time','shops_add_time','group_start_time','group_end_time','group_group_time','add_time','up_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('shops_pub_time','shops_up_time','shops_add_time','group_start_time','group_end_time','group_group_time','add_time','up_time'); 
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
		return '{{order_organizer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_no, organizer_id, group_id, shops_pub_time, group_remark, user_order_count, user_pay_count, user_submit_count, user_go_count', 'required'),
			array('shops_pub_time, group_group, is_organizer, status', 'numerical', 'integerOnly'=>true),
			array('group_no, shops_list_img, shops_page_img, shops_list_info, shops_page_info', 'length', 'max'=>128),
			array('organizer_id, group_id, user_order_count, user_pay_count, user_submit_count, user_go_count', 'length', 'max'=>11),
			array('shops_name', 'length', 'max'=>24),
			array('shops_up_time, shops_add_time, group_start_time, group_end_time, group_group_time, add_time, up_time', 'length', 'max'=>10),
			array('group_price, user_price, user_price_count, total', 'length', 'max'=>13),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,shops_c_id,shops_c_name,id, group_no, organizer_id, group_id, shops_name, shops_list_img, shops_page_img, shops_list_info, shops_page_info, shops_pub_time, shops_up_time, shops_add_time, group_price, group_remark, group_start_time, group_end_time, group_group_time, group_group, user_order_count, user_pay_count, user_submit_count, user_price, user_go_count, user_price_count, total, is_organizer, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			'OrderOrganizer_OrderItems'=>array(self::HAS_MANY,'OrderItems','order_organizer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'group_no' => '结伴 id 号',
			'organizer_id' => '组织者id',
			'group_id' => '结伴游商品id',
			'shops_c_id' => '商品类型ID',
			'shops_c_name' => '商品类型名称',
			'shops_name' => '商品名称',
			'shops_list_img' => '列表头图',
			'shops_page_img' => '详情头图',
			'shops_list_info' => '列表简介',
			'shops_page_info' => '详情简介',
			'shops_pub_time' => '发布时间',
			'shops_up_time' => '更新时间',
			'shops_add_time' => '创建时间',
			'group_price' => '服务费',
			'group_remark' => '结伴游备注',
			'group_start_time' => '报名开始时间',
			'group_end_time' => '报名结束时间',
			'group_group_time' => '出游时间',
			'group_group' => '团状态',
			'user_order_count' => '用户下单数量',
			'user_pay_count' => '用户支付数量',
			'user_submit_count' => '用户确认出游数',
			'user_price' => '实际服务费用',
			'user_go_count' => '用户出游数量',
			'user_price_count' => '下单总额',
			'total' => '总计总额',
			'is_organizer' => '组织者确认游',
			'add_time' => '下单时间',
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
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('group_no',$this->group_no,true);
			$criteria->compare('organizer_id',$this->organizer_id,true);
			$criteria->compare('group_id',$this->group_id,true);
			$criteria->compare('shops_name',$this->shops_name,true);
			$criteria->compare('shops_list_img',$this->shops_list_img,true);
			$criteria->compare('shops_page_img',$this->shops_page_img,true);
			$criteria->compare('shops_list_info',$this->shops_list_info,true);
			$criteria->compare('shops_page_info',$this->shops_page_info,true);
			if($this->shops_pub_time != '')
				$criteria->addBetweenCondition('shops_pub_time',strtotime($this->shops_pub_time),(strtotime($this->shops_pub_time)+3600*24-1));
			if($this->shops_up_time != '')
				$criteria->addBetweenCondition('shops_up_time',strtotime($this->shops_up_time),(strtotime($this->shops_up_time)+3600*24-1));
			if($this->shops_add_time != '')
				$criteria->addBetweenCondition('shops_add_time',strtotime($this->shops_add_time),(strtotime($this->shops_add_time)+3600*24-1));
			$criteria->compare('group_price',$this->group_price,true);
			$criteria->compare('group_remark',$this->group_remark,true);
			if($this->group_start_time != '')
				$criteria->addBetweenCondition('group_start_time',strtotime($this->group_start_time),(strtotime($this->group_start_time)+3600*24-1));
			if($this->group_end_time != '')
				$criteria->addBetweenCondition('group_end_time',strtotime($this->group_end_time),(strtotime($this->group_end_time)+3600*24-1));
			if($this->group_group_time != '')
				$criteria->addBetweenCondition('group_group_time',strtotime($this->group_group_time),(strtotime($this->group_group_time)+3600*24-1));
			$criteria->compare('group_group',$this->group_group);
			$criteria->compare('user_order_count',$this->user_order_count,true);
			$criteria->compare('user_pay_count',$this->user_pay_count,true);
			$criteria->compare('user_submit_count',$this->user_submit_count,true);
			$criteria->compare('user_price',$this->user_price,true);
			$criteria->compare('user_go_count',$this->user_go_count,true);
			$criteria->compare('user_price_count',$this->user_price_count,true);
			$criteria->compare('total',$this->total,true);
			$criteria->compare('is_organizer',$this->is_organizer);
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
	 * @return OrderOrganizer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * OMQ+201509011   OMQ + date + id
	 */
	public static function get_group_no($id)
	{
		$date=date('Ymd');

		$front='OMQ';
		$id=$front.$date.self::get_order_no_default($id);

		return $id;
	}
	/**
	 * 默认
	 * @param unknown $id
	 * @return string|unknown
	 */
	public static function get_order_no_default($id)
	{
		$number=Yii::app()->params['order_organizer_no_default'];
		if(strlen($id)<=$number)
			return sprintf('%0'.$number.'s', $id);
		return $id;
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
