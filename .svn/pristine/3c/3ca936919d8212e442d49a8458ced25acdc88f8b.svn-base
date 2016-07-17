<?php

/**
 * This is the model class for table "{{store}}".
 *
 * The followings are the available columns in table '{{store}}':
 * @property string $id
 * @property string $phone
 * @property string $store_name
 * @property string $name
 * @property string $telephone
 * @property string $pad_count
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Store extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{store}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('id, province, city, district', 'required'),
            array('status', 'numerical', 'integerOnly'=>true),
            array('id', 'length', 'max'=>20),
            array('phone, telephone, pad_count, province, city, district', 'length', 'max'=>11),
            array('store_name, name', 'length', 'max'=>16),
            array('address', 'length', 'max'=>128),
            array('up_time, add_time', 'length', 'max'=>10),
            
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            array('phone', 'unique'),
            //创建体验店
            array('store_name, phone, name, telephone, province, city, district, address', 'required', 'on'=>'create, update'),
            array('phone', 'ext.validators.PhoneValidator', 'on'=>'create, update'),
            array('telephone', 'ext.validators.TelephoneValidator', 'on'=>'create, update'),
            array('province, city, district', 'ext.validators.AreaValidator', 'on'=>'create, update'),
            array('store_name, phone, name, telephone, province, city, district, address', 'safe', 'on'=>'create, update'),
            array('id, pad_count, up_time, add_time, status', 'unsafe', 'on'=>'create, update'),
                
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, phone, store_name, name, telephone, pad_count, province, city, district, address, up_time, add_time, status', 'safe', 'on'=>'search'),
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
                'Store_Role'=>array(self::BELONGS_TO, 'Role', 'id'),
                'Store_Password'=>array(self::HAS_ONE, 'Password', 'role_id'),
                'Store_Area_province'=>array(self::BELONGS_TO, 'Area', 'province'),
                'Store_Area_city'=>array(self::BELONGS_TO, 'Area', 'city'),
                'Store_Area_district'=>array(self::BELONGS_TO, 'Area', 'district'),
                'Store_Pad'=>array(self::HAS_MANY, 'Pad', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'phone' => '手机账号',
            'store_name' => '体验店名',
            'name' => '联系人',
            'telephone' => '联系电话',
            'pad_count' => '展示屏统计',
            'province' => '省',
            'city' => '市',
            'district' => '县(区)',
            'address' => '详细地址',
            'up_time' => '修改时间',
            'add_time' => '注册时间',
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
            'Store_Area_province',
            'Store_Area_city',
            'Store_Area_district',
        );
        $criteria->compare('t.id', $this->id,true);
        $criteria->compare('t.phone', $this->phone,true);
        $criteria->compare('t.store_name', $this->store_name,true);
        $criteria->compare('t.name', $this->name,true);
        $criteria->compare('t.telephone', $this->telephone,true);
        $criteria->compare('t.pad_count', $this->pad_count,true);
        
        if ( (!!$area = Area::model()->validateAttribute('city', $this->city)) && $area->pid != $this->province)
        {
            $this->city = $this->district = '';
        }
        $criteria->compare('t.province', $this->province);
        $criteria->compare('t.city', $this->city);
        $criteria->compare('t.district', $this->district);
        $criteria->compare('t.address', $this->address,true);
        $this->timeSearch('t.up_time', $criteria, $this->up_time);
        $this->timeSearch('t.add_time', $criteria, $this->add_time);
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
     * @return Store the static model class
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