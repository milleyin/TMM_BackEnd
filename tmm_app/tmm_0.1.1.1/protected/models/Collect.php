<?php

/**
 * This is the model class for table "{{collect}}".
 *
 * The followings are the available columns in table '{{collect}}':
 * @property string $id
 * @property string $c_id
 * @property string $shops_id
 * @property string $user_id
 * @property string $user_ip
 * @property string $user_address
 * @property integer $collect_type
 * @property integer $is_collect
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Collect extends CActiveRecord
{
	/**
	 * not_is_collect 已赞
	 */
	const is_collect=1;
	/**
	 * not_is_collect 已取消赞
	 */
	const not_is_collect=0;

	/**
	 * 点赞
	 */
	const collect_type_praise=1;
	/**
	 * 浏览
	 */
	const collect_type_view=2;
	/**
	 * 解释字段 is_collect 的含义
	 * @var array
	 */
	public static $_is_collect=array('取消赞','点赞');
	/**
	 * 解释字段 collect_type 的含义
	 * @var array
	 */
	public static $_collect_type=array(1=>'点赞',2=>'浏览');
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
	public static $_search_time_type=array('add_time','up_time'); 
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
		return '{{collect}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('c_id, shops_id, user_id', 'required'),
			array('collect_type, is_collect, status', 'numerical', 'integerOnly'=>true),
			array('c_id, shops_id, user_id', 'length', 'max'=>11),
			array('user_ip', 'length', 'max'=>15),
			array('user_address', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>10),

			array('shops_id', 'required','on'=>'create'),
			array('shops_id,user_address','safe','on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id, c_id, user_id, user_ip , collect_type, is_collect, add_time, up_time, status', 'unsafe', 'on'=>'create'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, c_id, shops_id, user_id, user_ip, user_address, collect_type, is_collect, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			'Collect_Shops'=>array(self::BELONGS_TO,'Shops','shops_id'),
			'Collect_User'=>array(self::BELONGS_TO,'User','user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'c_id' => '关联商品数据模型表（shops_classliy）主键id',
			'shops_id' => '商品id',
			'user_id' => 'APP用户ID',
			'user_ip' => '用户（赞、浏览）的ip',
			'user_address' => '用户（赞、浏览）的地址',
			'collect_type' => '收集类型',
			'is_collect' => '赞的状态0取消1已赞',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态0禁用1启用',
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
			$criteria->compare('c_id',$this->c_id,true);
			$criteria->compare('shops_id',$this->shops_id,true);
			$criteria->compare('user_id',$this->user_id,true);
			$criteria->compare('user_ip',$this->user_ip,true);
			$criteria->compare('user_address',$this->user_address,true);
			$criteria->compare('collect_type',$this->collect_type);
			$criteria->compare('is_collect',$this->is_collect);
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
	 * @return Collect the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 查询赞的信息
	 */
	public static function get_collect_praise($collect_type,$shop_id,$user_id){
		return self::model()->find(
			 '`shops_id`=:shop_id AND `user_id`=:user_id AND `collect_type`=:collect_type',
			 array(':shop_id'=>$shop_id,':user_id'=>$user_id,':collect_type'=>$collect_type)
		);

	}
	/**
	 * 查询赞的总数
	 */
	public static function get_collect_count($collect_type,$shop_id){
		return self::model()->count(
			'`shops_id`=:shop_id AND  `collect_type`=:collect_type AND `status`=1 AND `is_collect`=:is_collect',
			array(':shop_id'=>$shop_id,':collect_type'=>$collect_type,':is_collect'=>self::is_collect)
		);
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
			$this->user_ip = Yii::app()->request->userHostAddress;
			return true;
		}else
			return false;
	}
}
