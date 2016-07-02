<?php

/**
 * This is the model class for table "{{software}}".
 *
 * The followings are the available columns in table '{{software}}':
 * @property string $id
 * @property integer $type
 * @property integer $use
 * @property string $version
 * @property string $version_name
 * @property string $dow_url
 * @property string $file_path
 * @property integer $dow_count
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Software extends CActiveRecord
{
	/**
	 * 软件类型 用户
	 * @var integer
	 */
	const type_user=0;
	/**
	 * 软件类型 商家
	 * @var integer
	 */
	const type_store=1;
	/**
	 * 软件类型 用户
	 * @var integer
	 */
	const type_user_ios=2;
	/**
	 * 软件类型 商家
	 * @var integer
	 */
	const type_store_ios=3;
	/**
	 *  软件类型 PAD
	 * @var integer
	 */
	const type_pad=4;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_type=array('用户Android','商家Android','用户IOS','商家IOS','PAD展示屏(Android)');
	/**
	 * zip
	 * @var integer
	 */
	const use_zip=0;
	/**
	 * apk
	 * @var integer
	 */
	const use_apk=1;
	/**
	 * apk
	 * @var integer
	 */
	const use_ios=2;
	/**
	 * apk
	 * @var integer
	 */
	const use_dpr=3;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_use=array('ZIP', 'APK', 'IPA', 'DPR');
	
	/**
	 * 禁止更新
	 * @var integer
	 */
	const status_update_not=0;
	/**
	 * 开启更新
	 * @var integer
	 */
	const status_update_yes=1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁止更新','开启更新');
	
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
		return '{{software}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('dow_url', 'required'),
			array('type, use, dow_count, status,version', 'numerical', 'integerOnly'=>true),
			array('version', 'length', 'max'=>10),
			array('dow_url', 'length', 'max'=>400),
			array('file_path', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>11),
			array('version_name', 'length' ,'min'=>5,'max'=>5),
				
			array('type,use,version,version_name', 'required','on'=>'create'),
			array('type,use,version,version_name,file_path','safe','on'=>'create'),
			array(
					'file_path','file','allowEmpty'=>true,
					'types'=>'zip,apk,api', 'maxSize'=>50*1024*1024,
					'tooLarge'=>'软件超过50M,请重新上传', 'wrongType'=>'软件格式错误',
					'on'=>'create',
			),
			array('type','in','range'=>array_keys(self::$_type),'on'=>'create'),
			array('use','in','range'=>array_keys(self::$_use),'on'=>'create'),
			array('use','is_use','on'=>'create'),
			array('version_name','is_version_name','on'=>'create'),
			array('version','is_version','on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id, dow_url, dow_count, add_time, up_time, status', 'unsafe', 'on'=>'create'),
				
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,version_name,search_end_time,id, type, use, version, dow_url, file_path, dow_count, add_time, up_time, status', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * 验证版本号名
	 */
	public function is_version_name()
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
				'`type`'=>$this->type,
				'`use`'=>$this->use,
				'`version_name`'=>$this->version_name
		));
		if($this->find($criteria))
			$this->addError('version_name', '版本号 不可重复');
		elseif(! preg_match('/^[1-9]{1}\.[0-9]{1}\.[0-9]{1}$/', $this->version_name))
			$this->addError('version_name', '版本号 数字和 . 组合');			
	}
	
	/**
	 * 验证版本号
	 */
	public function is_version()
	{
		$criteria=new CDbCriteria;		
		$criteria->addColumnCondition(array(
				'`type`'=>$this->type,
				'`use`'=>$this->use,
				'`version`'=>$this->version
		));
		if($this->find($criteria))
			$this->addError('version', '更新版本号 不可重复');
		else
		{
			$criteria_version=new CDbCriteria;
			$criteria_version->addColumnCondition(array(
					'`type`'=>$this->type,
					'`use`'=>$this->use,
			));
			$criteria_version->order='`version` desc';
			$model=$this->find($criteria_version);
			if($model)
			{
				if(($model->version+1) != $this->version)
					$this->addError('version', '更新版本号错误 最新版本号为：' . ($model->version+1));
			}
			else
			{
				if($this->version != 1)
					$this->addError('version', '更新版本号错误 初始版本号为：1');
			}
		}		
	}
	
	/**
	 * 验证上传类型的错误
	 */
	public function is_use()
	{
		if($this->use==self::use_apk)
		{
			if(! in_array($this->type, array(self::type_store,self::type_user,self::type_pad)))
				$this->addError('use', '软件格式与软件类型 不匹配');
		}
		elseif($this->use==self::use_ios)
		{
			if(! in_array($this->type, array(self::type_store_ios,self::type_user_ios)))
				$this->addError('use', '软件格式与软件类型 不匹配');
		}		
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
			'type' => '软件类型',
			'use' => '软件格式',
			'version' => '更新版本号',
			'version_name' => '版本号',
			'dow_url' => '下载地址',
			'file_path' => '上传文件',
			'dow_count' => '下载次数',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '记录状态',
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
			$criteria->compare('type',$this->type);
			$criteria->compare('`use`',$this->use);
			$criteria->compare('version',$this->version,true);
			$criteria->compare('version_name',$this->version_name,true);
			$criteria->compare('dow_url',$this->dow_url,true);
			$criteria->compare('file_path',$this->file_path,true);
			$criteria->compare('dow_count',$this->dow_count);
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
	 * @return Software the static model class
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
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
}
