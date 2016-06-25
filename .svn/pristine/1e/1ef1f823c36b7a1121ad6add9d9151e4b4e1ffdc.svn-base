<?php

/**
 * This is the model class for table "{{group}}".
 *
 * The followings are the available columns in table '{{group}}':
 * @property string $id
 * @property string $c_id
 * @property integer $user_id
 * @property integer $group_thrand
 * @property string $price
 * @property string $remark
 * @property string $start_time
 * @property string $end_time
 * @property string $pub_time
 * @property string $group_time
 * @property integer $group
 * @property integer $status
 */
class Group extends CActiveRecord
{
	
	//结伴中 => 用户确认中 =>商家确认中 => 已结伴 (未结伴)
	/**
	 * 未结伴
	 * @var integer
	 */
	const group_no_peer=-3;
	/**
	 * 创建 待审核或审核未通过
	 * @var integer
	 */
	const group_none=-2;
	/**
	 * 已取消
	 * @var integer
	 */
	const group_cancel=-1;
	/**
	 * 结伴中
	 * @var integer
	 */
	const group_peering=0;
	/**
	 * 用户确认中
	 * @var integer
	 */
	const group_user_confirm=1;
	/**
	 * 商家确认中
	 * @var integer
	 */
	const group_store_confirm=2;
	/**
	 * 已结伴
	 * @var integer
	 */
	const group_already_peer=3;

	/**
	 * 解释字段 group 的含义
	 * @var array
	 */
	public static $_group=array(
			-3=>'未结伴',
			-2=>'待审核或审核未通过',		
			-1=>'已取消',
			0=>'结伴中',
			1=>'用户确认中',
			2=>'商家确认中',
			3=>'已结伴',		
	);
	
	/**
	 * 删除
	 * @var integer
	 */
	const status_del=-1;
	/**
	 * 已下线
	 * @var integer
	 */
	const status_down=0;
	/**
	 * 已上线
	 * @var integer
	 */
	const status_up=1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','已下线','已上线');
	/**
	 * 解释字段 group 的含义
	 * @var array
	 */
	public static $_group_status=array(-2=>'没有状态',-1=>'已取消',0=>'结伴中',1=>'用户确认中',2=>'商家确认中',3=>'已结伴',4=>'未结伴');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('创建时间','更新时间','发布时间','出游时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('start_time','end_time','pub_time','group_time'); 
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
		return '{{group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id, c_id, user_id,group_thrand, remark, pub_time, group_time', 'required'),
			array('group, status,group_thrand', 'numerical', 'integerOnly'=>true),
			array('id, c_id,group_thrand, user_id', 'length', 'max'=>11),
			array('price', 'length', 'max'=>13),
			array('start_time, end_time, pub_time, group_time', 'length', 'max'=>10),
			
			//创建
			array('group_thrand,price,remark,end_time', 'required','on'=>'create'),
			//array('group_thrand','is_group_thrand','on'=>'create'),
			array('remark', 'length', 'max'=>100,'on'=>'create'),
			array('end_time','type','dateFormat'=>'yyyy-MM-dd','type'=>'date','on'=>'create'),
			//array('end_time','is_end_time','on'=>'create'),
			array('group_thrand,price,remark,end_time','safe','on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id, c_id, user_id, start_time, pub_time, group_time, group, status','unsafe','on'=>'create'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,group_thrand,search_start_time,search_end_time,id, c_id, user_id, price, remark, start_time, end_time, pub_time, group_time, group, status', 'safe', 'on'=>'search'),
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
			// 商品（结伴游）关联主表 一对一
			'Group_Shops'=>array(self::HAS_ONE, 'Shops', 'id'),
			// 商品（结伴游）关联类型表 归属（多对一）
			'Group_ShopsClassliy'=>array(self::BELONGS_TO, 'ShopsClassliy', 'c_id'),
			//线路(结伴游)关联 选中项目表 (一对多)
			'Group_Pro'=>array(self::HAS_MANY,'Pro','shops_id'),
			// 商品（结伴游）关联用户表 归属（多对一）
			'Group_User'=>array(self::BELONGS_TO, 'User', 'user_id'),
			//线路(点)关联 选中标签
			'Group_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'c_id' => '关联项目数据模型表（shops_classliy）主键id',
			'group_thrand'=>'结伴游对应线的ID',
			'user_id' => '组织者',
			'price' => '服务费',
			'remark' => '结伴游备注',
			'start_time' => '创建时间',
			'end_time' => '报名截止时间',
			'pub_time' => '发布时间',
			'group_time' => '出游时间',
			'group' => '团状态',
			'status' => '发布状态',
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

			$criteria->with = array(
				'Group_Shops',
				'Group_User'=>array('select'=>'phone,nickname'),
			);

			$criteria->compare('Group_Shops.status','<>-1');
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
			$criteria->compare('Group_User.phone',$this->user_id,true);
			$criteria->compare('t.price',$this->price,true);
			$criteria->compare('t.remark',$this->remark,true);
		//	$criteria->compare('t.group_thrand',$this->group_thrand,true);
			$criteria->compare('Group_Shops.name',$this->Group_Shops->name,true);
			$criteria->compare('Group_Shops.brow',$this->Group_Shops->brow,true);
			$criteria->compare('Group_Shops.share',$this->Group_Shops->share,true);
			$criteria->compare('Group_Shops.praise',$this->Group_Shops->praise,true);
			$criteria->compare('Group_Shops.audit',$this->Group_Shops->audit,true);

			if($this->start_time != '')
				$criteria->addBetweenCondition('t.start_time',strtotime($this->start_time),(strtotime($this->start_time)+3600*24-1));
			if($this->end_time != '')
				$criteria->addBetweenCondition('t.end_time',strtotime($this->end_time),(strtotime($this->end_time)+3600*24-1));
			if($this->pub_time != '')
				$criteria->addBetweenCondition('t.pub_time',strtotime($this->pub_time),(strtotime($this->pub_time)+3600*24-1));
			if($this->group_time != '')
				$criteria->addBetweenCondition('t.group_time',strtotime($this->group_time),(strtotime($this->group_time)+3600*24-1));
			$criteria->compare('t.group',$this->group);
			$criteria->compare('Group_Shops.status',$this->Group_Shops->status,true);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Group the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave(){
		if(parent::beforeSave()){
			return true;
		}else
			return false;
	}
}
