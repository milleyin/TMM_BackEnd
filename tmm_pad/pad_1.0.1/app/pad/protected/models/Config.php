<?php

/**
 * This is the model class for table "{{config}}".
 *
 * The followings are the available columns in table '{{config}}':
 * @property string $id
 * @property string $store_id
 * @property string $pad_id
 * @property string $manager_id
 * @property string $number
 * @property string $info
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Config extends ActiveRecord
{
    /**
     * 解释字段 status 的含义
     * @var array
     */
    public static $_status = array(
            self::_STATUS_DISABLE => '已禁用',
            self::_STATUS_NORMAL => '正常',
    ); 
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{config}}';
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
            array('store_id, pad_id, manager_id', 'length', 'max'=>20),
            array('number', 'length', 'max'=>11),
            array('info', 'length', 'max'=>128),
            array('up_time, add_time', 'length', 'max'=>10),
            
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            // 创建，更新抽奖配置
            array('number, info', 'required', 'on'=>'create, update'),
            array('number', 'numerical', 'integerOnly'=>true, 'min'=>0, 'max'=>100, 'on'=>'create, update'),
            array('pad_id', 'isPadIdValidator', 'on'=>'create'),
            array('number, info','safe', 'on'=>'create, update'),
            array('id, store_id, pad_id, manager_id, up_time, add_time, status', 'unsafe', 'on'=>'create, update'),
                
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, store_id, pad_id, manager_id, number, info, up_time, add_time, status', 'safe', 'on'=>'search'),
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
            'Config_Pad' => array(self::BELONGS_TO, 'Pad', 'pad_id'),
            'Config_Store' => array(self::BELONGS_TO, 'Store', 'store_id'),
            'Config_Upload'=>array(self::HAS_ONE, 'Upload', 'upload_id', 'on'=>'Config_Upload.type=' . Upload::UPLOAD_TYPE_CONFIG),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'store_id' => '体验店',
            'pad_id' => '展示屏',
            'manager_id' => '操作角色',
            'number' => '次数/人*天',
            'info' => '抽奖规则',
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
            'Config_Pad',
            'Config_Store',
            'Config_Upload',
        );

        $criteria->compare('t.id', $this->id,true);
        
        if (strpos($this->pad_id, '=') === 0) {
            $criteria->compare('t.pad_id', $this->pad_id);
        } else {
            $criteria->compare('Config_Pad.name', $this->pad_id, true);
        }
        
        if (strpos($this->store_id, '=') === 0) {
            $criteria->compare('t.store_id', $this->store_id);
        } else {
            $criteria->compare('Config_Store.store_name', $this->store_id, true);
        }    
        $criteria->compare('Config_Upload.path', $this->Config_Upload->path, true);
        $criteria->compare('Config_Pad.number', $this->Config_Pad->number, true);
        $criteria->compare('Config_Store.phone', $this->Config_Store->phone, true);
        $criteria->compare('t.manager_id', $this->manager_id, true);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.info', $this->info,true);
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
                    'Config_Upload.path'=>array(
                        'desc'=>'Config_Upload.path desc',
                    ),
                    'Config_Store.phone'=>array(
                            'desc'=>'Config_Store.phone desc',
                    ),
                    'Config_Pad.number'=>array(
                        'desc'=>'Config_Pad.number desc',
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
     * @return Config the static model class
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
    
    /**
     * 验证是否设置了抽奖配置
     */
    public function isPadIdValidator()
    {
        if ( !$this->hasErrors())
        {
            if ($this->pad_id)
            {
                if (self::model()->find('pad_id=:pad_id', array(':pad_id'=>$this->pad_id)))
                    $this->addError('pad_id', '展示屏已设置了抽奖配置');
            }
            else
                $this->addError('pad_id', '展示屏 不是有效值');
        }
    }
}