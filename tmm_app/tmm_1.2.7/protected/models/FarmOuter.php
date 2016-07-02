<?php

/**
 * This is the model class for table "{{farm_outer}}".
 *
 * The followings are the available columns in table '{{farm_outer}}':
 * @property string $id
 * @property string $dot_id
 * @property string $name
 * @property string $info
 * @property string $img
 * @property string $link
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class FarmOuter extends CActiveRecord
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
	public static $_status=array(-1=>'删除','禁用','开启');
	
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('创建时间','更新时间'); 
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
	 * 类型
	 */
	const outer_type=-1;

	const outer_name='农产品外链';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{farm_outer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('dot_id, name, info, img, link', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('dot_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>64),
			array('info, img', 'length', 'max'=>128),
			array('link', 'length', 'max'=>255),
			array('add_time, up_time', 'length', 'max'=>10),
			
			array('name, info, link', 'required','on'=>'create,update'),
			array(
					'img','file','allowEmpty'=>true,
					'types'=>'jpg,png', 'maxSize'=>1024*1024*2,
					'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'图片格式错误',
					'on'=>'create,update',
			),					
			//array('link','match','pattern'=>'','message'=>'{attribute} 不是有效的','on'=>'create,update'),		
			array('name, info, link','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, dot_id, img, add_time, up_time, status','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, dot_id, name, info, img, link, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				// 归属点
				'FarmOuter_Dot'=>array(self::BELONGS_TO,'Dot','dot_id'),
				// 归属线路主要表
				'FarmOuter_Shops'=>array(self::BELONGS_TO,'Shops','dot_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'dot_id' => '点的名称',
			'name' => '名称',
			'info' => '描述',
			'img' => '图片',
			'link' => '外部链接',
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
		if($criteria ===''){
			$criteria=new CDbCriteria;
			$criteria->with=array('FarmOuter_Shops');
			$criteria->compare('t.status','<>-1');
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
			if(is_numeric($this->dot_id))
				$criteria->compare('t.dot_id',$this->dot_id,true);
			else
				$criteria->compare('FarmOuter_Shops.name',$this->dot_id,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.info',$this->info,true);
			$criteria->compare('t.img',$this->img,true);
			$criteria->compare('t.link',$this->link,true);
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
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FarmOuter the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 查询点对应外部链接
	 */
	public static function get_farm_outer($dot_id)
	{
		return self::model()->findAll(
			'`dot_id`=:dot_id AND `status`=:status ',
			array(':dot_id'=>$dot_id,':status'=>1)
		);
	}
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if	(parent::beforeSave())
		{		
			if	($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}
		else
			return false;
	}
}
