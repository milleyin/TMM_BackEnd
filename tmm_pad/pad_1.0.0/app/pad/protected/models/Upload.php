<?php

/**
 * This is the model class for table "{{upload}}".
 *
 * The followings are the available columns in table '{{upload}}':
 * @property string $id
 * @property integer $type
 * @property string $upload_id
 * @property string $manager_id
 * @property integer $upload_type
 * @property string $path
 * @property string $info
 * @property string $size
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Upload extends ActiveRecord
{
    /***************************************  Upload   type*****************************************************/
    /**
     * 资源归属 广告表的资源
     * @var integer
     */
    const UPLOAD_TYPE_AD = 0;
    /**
     * 资源归属 商品表的资源
     * @var integer
     */
    const UPLOAD_TYPE_SHOP = 1;
    /**
     * 资源归属 奖品表的资源
     * @var integer
     */
    const UPLOAD_TYPE_PRIZE = 2;
    /**
     * 资源归属 奖品记录表的资源
     * @var integer
     */
    const UPLOAD_TYPE_RECORD = 3;
    /**
     * 资源归属 抽奖配置表的资源
     * @var integer
     */
    const UPLOAD_TYPE_CONFIG = 4;
    /**
     * 资源归属 解释字段 type 含义
     * @var array
     */
    public static $_type = array(
        self::UPLOAD_TYPE_AD => '广告',
        self::UPLOAD_TYPE_SHOP => '商品',
        self::UPLOAD_TYPE_PRIZE => '奖品',
        self::UPLOAD_TYPE_RECORD => '记录',
        self::UPLOAD_TYPE_CONFIG => '抽奖配置',
    );
    /**
     * 资源归属 解释字段 type 含义
     * @var array
     */
    public static $__type = array(
            self::UPLOAD_TYPE_AD => 'ad',
            self::UPLOAD_TYPE_SHOP => 'shop',
            self::UPLOAD_TYPE_PRIZE => 'prize',
            self::UPLOAD_TYPE_RECORD => 'record',
            self::UPLOAD_TYPE_CONFIG => 'config',
    );
    
    /***************************************  Upload   upload_type*****************************************************/
    /**
     * 资源类型 图片资源
     * @var integer
     */
    const UPLOAD_UPLOAD_TYPE_IMAGE = 0;
    /**
     * 资源类型 视频资源
     * @var integer
     */
    const UPLOAD_UPLOAD_TYPE_VIDEO = 1;
    /**
     * 资源类型 文件资源
     * @var integer
     */
    const UPLOAD_UPLOAD_TYPE_FILE = 2;   
    /**
     * 资源类型 解释字段 upload_type 含义
     * @var array
     */
    public static $_upload_type = array(
        self::UPLOAD_UPLOAD_TYPE_IMAGE => '图片类型',
        self::UPLOAD_UPLOAD_TYPE_VIDEO => '视频类型',
        self::UPLOAD_UPLOAD_TYPE_FILE => '文件类型'
    );
    /**
     * 资源类型 解释字段 upload_type 含义
     * @var array
     */
    public static $__upload_type = array(
            self::UPLOAD_UPLOAD_TYPE_IMAGE => 'image',
            self::UPLOAD_UPLOAD_TYPE_VIDEO => 'video',
            self::UPLOAD_UPLOAD_TYPE_FILE => 'file',
    );
    /**
     * 上传属性的对象
     * @var unknown
     */
    public $_file;
    /**
     * 原来的资源
     * @var unknown
     */
    public $_old_path;
    /**
     * 上传文件夹
     * @var unknown
     */
    public $_uploads_dir = '/uploads';
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{upload}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('upload_id, manager_id', 'required'),
            array('type, upload_type, status', 'numerical', 'integerOnly'=>true),
            array('upload_id, manager_id, size', 'length', 'max'=>20),
            array('path, info', 'length', 'max'=>128),
            array('up_time, add_time', 'length', 'max'=>10),
            
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            array('type', 'in', 'range'=>array_keys(self::$_type)),
            array('upload_type', 'in', 'range'=>array_keys(self::$_upload_type)),
            
            //图片上传 
            array('type', 'required', 'on'=>'create_image, update_image'),
            array('upload_type', 'ext.validators.DefaultValueValidator', 'value'=>self::UPLOAD_UPLOAD_TYPE_IMAGE, 'on'=>'create_image, update_image'),
            array(
                    'path', 'file', 'allowEmpty'=>true,
                    'types'=>'jpg, png, jpeg, gif',
                    'maxSize'=>1024*1024*2,
                    'tooLarge'=>'{attribute} 超过2M，请重新上传',
                    'wrongType'=>'{attribute} 格式错误 jpg, png, jpeg, gif',
                    'on'=>'create_image, update_image',
            ),
            array('path', 'validateFile', 'on'=>'create_image, update_image'),
            array('info', 'safe', 'on'=>'create_image, update_image'),
            array('id, type, upload_id, manager_id, upload_type, path, size, up_time, add_time, status', 'unsafe', 'on'=>'create_image, update_image'),            
            //视频上传
            array('type', 'required', 'on'=>'create_video, update_video'),
            array('upload_type', 'ext.validators.DefaultValueValidator', 'value'=>self::UPLOAD_UPLOAD_TYPE_VIDEO, 'on'=>'create_video, update_video'),
            array(
                    'path', 'file', 'allowEmpty'=>true,
                    'types'=>'mp4',
                    'maxSize'=>1024*1024*50,
                    'tooLarge'=>'{attribute} 超过50M，请重新上传',
                    'wrongType'=>'{attribute} 格式错误 mp4',
                    'on'=>'create_video, update_video',
            ),
            array('path', 'validateFile', 'on'=>'create_video, update_video'),
            array('info', 'safe', 'on'=>'create_video, update_video'),
            array('id, type, upload_id, manager_id, upload_type, path, size, up_time, add_time, status', 'unsafe', 'on'=>'create_video, update_video'),            
            
            //文件上传
            array('type', 'required', 'on'=>'create_file, create_file'),
            array('upload_type', 'ext.validators.DefaultValueValidator', 'value'=>self::UPLOAD_UPLOAD_TYPE_FILE, 'on'=>'create_file, create_file'),
            array(
                    'path', 'file', 'allowEmpty'=>true,
                    'types'=>'zip, rar, xls, pdf, ppt',
                    'maxSize'=>1024*1024*10,
                    'tooLarge'=>'{attribute} 超过10M，请重新上传',
                    'wrongType'=>'{attribute} 格式错误 jpg, png, jpeg, gif',
                    'on'=>'create_file, create_file',
            ),
            array('path', 'validateFile', 'on'=>'create_file, create_file'),
            array('info', 'safe', 'on'=>'create_file, create_file'),
            array('id, type, upload_id, manager_id, upload_type, path, size, up_time, add_time, status', 'unsafe', 'on'=>'create_file, create_file'),            
            
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, upload_id, manager_id, upload_type, path, info, size, up_time, add_time, status', 'safe', 'on'=>'search'),
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
            'id' => 'ID',
            'type' => '类型',
            'upload_id' => '归属ID',
            'manager_id' => '操作角色',
            'upload_type' => '上传类型',
            'path' => '上传文件',
            'info' => '文件描述',
            'size' => '大小MB',
            'up_time' => '更新时间',
            'add_time' => '创建时间',
            'status' => '状态',
            '_old_path'=>'原上传文件',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id,true);
        $criteria->compare('type', $this->type);
        $criteria->compare('upload_id', $this->upload_id, true);
        $criteria->compare('manager_id', $this->manager_id, true);
        $criteria->compare('upload_type', $this->upload_type);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('info', $this->info, true);
        $criteria->compare('size/100', $this->size, true);
        $this->timeSearch('up_time', $criteria);
        $this->timeSearch('add_time', $criteria);
        if ($this->status != self::_STATUS_DELETED)
            $criteria->compare('status', '<>' . self::_STATUS_DELETED);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id desc',
                /*
                'attributes'=>array(
                    'id'=>array(
                        'desc'=>'id desc',
                    ),
                ),
                */
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Upload the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * 保存之前的操作
     * (non-PHPdoc)
     * @see ActiveRecord::beforeSave()
     */
    public function beforeSave()
    {
        if (parent::beforeSave() && $this->upload_id)
        {
            if ( !is_dir(dirname(Yii::app()->basePath . '/../web' . $this->path)))
                mkdir(dirname(Yii::app()->basePath . '/../web' . $this->path), 0777, true);
            if ($this->getIsNewRecord())
            {
                $this->up_time = $this->add_time = time();
                if ($this->_file) {
                    return $this->_file->saveAs(Yii::app()->basePath . '/../web' . $this->path);
                } else {
                    return true;
                }
            }
            else
            {
                $this->up_time = time();
                if ($this->_file)
                {
                    if ($this->_file->saveAs(Yii::app()->basePath . '/../web' . $this->path))
                    {
                        if (file_exists(Yii::app()->basePath . '/../web' . $this->_old_path))
                            unlink(Yii::app()->basePath . '/../web' . $this->_old_path);
                        return true;
                    }
                }
                else 
                    $this->path = $this->_old_path;
            }        
            return true;
        }
        return false;
    }
    
    /**
     * 执行验证之前的操作
     * (non-PHPdoc)
     * @see CModel::beforeValidate()
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate())
        {
            if (isset($_POST['Upload']))
                $this->attributes = $_POST['Upload'];
            $this->_old_path = $this->path;
        }
        return true;
    }
    
    /**
     * 验证图片
     */
    public function validateFile()
    {
        if ( !$this->hasErrors())
        {
            $this->uploadInit();
            if ($this->getIsNewRecord())
            {
                if ( !$this->_file)
                    $this->addError('path', '资源文件 不可空白');
            }
        }
    }
    
    /**
     * 上传 初始化
     * @param string $attribute
     */
    public function uploadInit($attribute = 'path')
    {
        if (!$this->_file && !!$this->_file = CUploadedFile::getInstance($this, $attribute))
        {
            $this->path = $this->getPathName() . '.' . $this->_file->extensionName;
            $this->size = $this->getFormatSize($this->_file->getSize());
        }
    }
    
    /**
     * 获取存储地址
     * @return string
     */
    public function getPathName()
    {
       if (isset(self::$__type[$this->type], self::$__upload_type[$this->upload_type]))
       {
           return $this->_uploads_dir . '/' . self::$__type[$this->type] . '/' . self::$__upload_type[$this->upload_type] . '/' .date('Y-m-d') . '/' . uniqid(mt_rand(0, 999999), true);
       }
       return $this->_uploads_dir  . '/' .date('Y-m-d') . '/' . uniqid(mt_rand(0, 999999), true);
    }
    
    /**
     * 返回 文件 MB 大小
     * @param unknown $bytes
     * @return number
     */
    public function getFormatSize($bytes)
    {
        return round(($bytes/1024/1024) * 100);
    }

    /**
     * 获取大小
     * @return Ambigous <number, NULL>
     */
    public function getSize()
    {
        return isset($this->size) && $this->size ? $this->size / 100 : null;
    }
    
    /**
     * 返回绝对路径
     * @return string
     */
    public function getAbsolutePath()
    {
        if ($this->path)
           return Yii::app()->basePath . '/../web' . $this->path;
        return null;
    }
    
    /**
     * 返回url path
     * @return string
     */
    public function getUrlPath()
    {
        if ($this->path)
           return Yii::app()->request->baseUrl . $this->path;
        return null;
    }
}