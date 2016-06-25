<?php

/**
 * This is the model class for table "{{privilege}}".
 *
 * The followings are the available columns in table '{{privilege}}':
 * @property string $id
 * @property string $code
 * @property string $use_time
 * @property integer $status
 */
class Privilege extends CActiveRecord
{
	/**
	 * 删除
	 */
	const status_del = -1;
	/**
	 * 正常
	 */
	const status_dis = 0;
	/**
	 * 禁用
	 */
	const status_suc = 1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status = array(-1=>'删除', '禁用', '正常');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type = array('use_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type = array('use_time'); 
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
		return '{{privilege}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>64),
			array('use_time', 'length', 'max'=>10),
			
			//array('', 'safe', 'on'=>'create, update'),
			//array('', 'unsafe', 'on'=>'create, update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type, search_start_time, search_end_time, id, code, use_time, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'code' => '密码',
			'use_time' => '使用时间',
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
		if ($criteria === '')
		{
			$criteria = new CDbCriteria;
			if ($this->status != self::status_del)
				$criteria->compare('status', '<>-1');
			if ($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if ($this->search_start_time != '' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type], strtotime($this->search_start_time), strtotime($this->search_end_time)+3600*24-1);
				else if ($this->search_start_time != '' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type], '>=' . strtotime($this->search_start_time));
				else if ($this->search_start_time == '' && $this->search_end_time != '')
					$criteria->compare($this->__search_time_type[$this->search_time_type], '<=' . (strtotime($this->search_end_time)+3600*24-1));
			}		
			$criteria->compare('id', $this->id, true);
			$criteria->compare('code', $this->code, true);
			if ($this->use_time != '')
				$criteria->addBetweenCondition('use_time', strtotime($this->use_time), (strtotime($this->use_time)+3600*24-1));
			$criteria->compare('status', $this->status);
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
	 * @return Privilege the static model class
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
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
				$this->up_time = $this->add_time = time();
			else
				$this->up_time = time();
			return true;
		}
		else
			return false;
	}
}
