<?php

/**
 * This is the model class for table "{{ad}}".
 *
 * The followings are the available columns in table '{{ad}}':
 * @property string $id
 * @property integer $type
 * @property string $manager_id
 * @property string $name
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Ad extends ActiveRecord
{
    /**
     * 广告类型 图片
     * @var integer
     */
    const AD_TYPE_IMAGE = 0;
    /**
     * 广告类型 视频
     * @var integer
     */
    const AD_TYPE_VIDEO = 1;
    /**
     * 广告类型
     * @var array
     */
    public static $_type = array(
        self::AD_TYPE_IMAGE => '图片',
        self::AD_TYPE_VIDEO => '视频',
    );
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{ad}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('manager_id', 'required'),
            array('type, status', 'numerical', 'integerOnly'=>true),
            array('manager_id', 'length', 'max'=>20),
            array('name', 'length', 'max'=>32),
            array('up_time, add_time', 'length', 'max'=>10),
              
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            array('type', 'in', 'range'=>array_keys(self::$_type)),
            
            //创建广告
            array('type, name', 'required', 'on'=>'create'),
            array('type, name', 'safe', 'on'=>'create'),
            array('id, manager_id, up_time, add_time, status', 'unsafe', 'on'=>'create'),
            //修改广告
            array('name', 'required', 'on'=>'update'),
            array('name', 'safe', 'on'=>'update'),
            array('id, manager_id, type, up_time, add_time, status', 'unsafe', 'on'=>'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, manager_id, name, up_time, add_time, status', 'safe', 'on'=>'search'),
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
            'Ad_Upload'=>array(self::HAS_ONE, 'Upload', 'upload_id', 'on'=>'Ad_Upload.type=' . Upload::UPLOAD_TYPE_AD),
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
            'manager_id' => '操作角色',
            'name' => '广告名',
            'up_time' => '更新时间',
            'add_time' => '创建时间',
            'status' => '状态',
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
        $criteria->with = array(
            'Ad_Upload'
        );
        $criteria->compare('Ad_Upload.path', $this->Ad_Upload->path, true);
        $criteria->compare('Ad_Upload.size/100', $this->Ad_Upload->size, true);
        $criteria->compare('t.id', $this->id,true);
        $criteria->compare('t.type', $this->type);
        $criteria->compare('t.manager_id', $this->manager_id,true);
        $criteria->compare('t.name', $this->name,true);
        $this->timeSearch('t.up_time', $criteria);
        $this->timeSearch('t.add_time', $criteria);
        if ($this->status != self::_STATUS_DELETED)
            $criteria->compare('t.status', '<>' . self::_STATUS_DELETED);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id desc',
                'attributes'=>array(
                    'Ad_Upload.size'=>array(
                        'desc'=>'Ad_Upload.size desc',
                    ),
                    'Ad_Upload.path'=>array(
                            'desc'=>'Ad_Upload.path desc',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Ad the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * 保存之前的操作
     * (non-PHPdoc)
     * @see CActiveRecord::beforeSave()
     */
    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->getIsNewRecord())
                $this->up_time = $this->add_time = time();
            return true;
        }
        return false;
    }
}