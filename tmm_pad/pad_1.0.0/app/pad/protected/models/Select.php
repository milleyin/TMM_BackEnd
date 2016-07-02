<?php

/**
 * This is the model class for table "{{select}}".
 *
 * The followings are the available columns in table '{{select}}':
 * @property string $id
 * @property string $ad_id
 * @property integer $ad_type
 * @property string $store_id
 * @property string $pad_id
 * @property string $manager_id
 * @property string $add_time
 */
class Select extends ActiveRecord
{    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{select}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('ad_id, store_id, pad_id, manager_id', 'required'),
            array('ad_type', 'numerical', 'integerOnly'=>true),
            array('ad_id, store_id, pad_id, manager_id', 'length', 'max'=>20),
            array('add_time', 'length', 'max'=>10),
            // The following rule is used by search().
            array('ad_type', 'in', 'range'=>array_keys(Ad::$_type)),
            //创建
            array('ad_id, ad_type, pad_id', 'required', 'on'=>'create'),
            array('pad_id', 'isPadIdValidator', 'on'=>'create'),
            array('pad_id', 'safe', 'on'=>'create'),
            array('id, ad_id, ad_type, store_id, manager_id, add_time', 'unsafe', 'on'=>'create'),
            //更新  (删除)
            array('pad_id', 'required', 'on'=>'update'),
            array('pad_id', 'safe', 'on'=>'update'),
            array('id, ad_id, ad_type, store_id, manager_id, add_time', 'unsafe', 'on'=>'update'),
            
            // @todo Please remove those attributes that should not be searched.
            array('id, ad_id, ad_type, store_id, pad_id, manager_id, add_time', 'safe', 'on'=>'search'),
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
            'Select_Ad' => array(self::BELONGS_TO, 'Ad', 'ad_id'),
            'Select_Pad' => array(self::BELONGS_TO, 'Pad', 'pad_id'),
            'Select_Store' => array(self::BELONGS_TO, 'Store', 'store_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'ad_id' => '广告ID',
            'ad_type' => '广告类型',
            'store_id' => '体验店ID',
            'pad_id' => '展示屏ID',
            'manager_id' => '操作角色ID',
            'add_time' => '创建时间',
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
            'Select_Ad'=>array(
                 'with'=>array('Ad_Upload'),
            ),
            'Select_Pad',
            'Select_Store'=>array(
                'with'=>array(
                    'Store_Area_province',
                    'Store_Area_city',
                    'Store_Area_district',
                ),
            ),
        );
        
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.ad_id', $this->ad_id);    
        $criteria->compare('t.ad_type', $this->ad_type);        
        $criteria->compare('t.store_id', $this->store_id);
        $criteria->compare('t.pad_id', $this->pad_id);        
        $criteria->compare('t.manager_id', $this->manager_id, true);
        $this->timeSearch('t.add_time', $criteria);
        
        $criteria->compare('Select_Ad.name', $this->Select_Ad->name, true);
        
        $criteria->compare('Select_Pad.name', $this->Select_Pad->name, true);
        $criteria->compare('Select_Pad.number', $this->Select_Pad->number, true);
        $criteria->compare('Select_Pad.mac', $this->Select_Pad->mac, true);
        
        $criteria->compare('Select_Store.phone', $this->Select_Store->phone, true);
        $criteria->compare('Select_Store.store_name', $this->Select_Store->store_name, true);
        $criteria->compare('Select_Store.name', $this->Select_Store->name, true);
        $criteria->compare('Select_Store.telephone', $this->Select_Store->telephone, true);
        if ( (!!$area = Area::model()->validateAttribute('city', $this->Select_Store->city)) && $area->pid != $this->Select_Store->province)
            $this->Select_Store->city = $this->Select_Store->district = '';
        $criteria->compare('Select_Store.province', $this->Select_Store->province);
        $criteria->compare('Select_Store.city', $this->Select_Store->city);
        $criteria->compare('Select_Store.district', $this->Select_Store->district);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id desc',
                'attributes'=>array(
                    'Select_Ad.name'=>array(
                        'desc'=>'Select_Ad.name desc',
                    ),
                    'Select_Ad.Ad_Upload.path'=>array(
                            'desc'=>'Ad_Upload.path desc',
                            'asc'=>'Ad_Upload.path',
                    ),
                    'Select_Pad.name'=>array(
                            'desc'=>'Select_Pad.name desc',
                    ),
                    'Select_Pad.number'=>array(
                            'desc'=>'Select_Pad.number desc',
                    ),
                    'Select_Store.phone'=>array(
                            'desc'=>'Select_Store.phone desc',
                    ),
                    'Select_Store.store_name'=>array(
                            'desc'=>'Select_Store.store_name desc',
                    ),
                    'Select_Store.name'=>array(
                            'desc'=>'Select_Store.name desc',
                    ),
                    'Select_Store.telephone'=>array(
                            'desc'=>'Select_Store.telephone desc',
                    ),
                    'Select_Store.province'=>array(
                            'desc'=>'Select_Store.province desc',
                    ),
                    'Select_Store.city'=>array(
                            'desc'=>'Select_Store.city desc',
                    ),
                    'Select_Store.district'=>array(
                            'desc'=>'Select_Store.district desc',
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
     * @return Select the static model class
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
                $this->add_time = time();
            return true;
        }
        return false;
    }
    
    /**
     * 验证展示屏 是否是有效的
     */
    public function isPadIdValidator()
    {
        if ( !$this->hasErrors())
        {
            if ($this->ad_type == Ad::AD_TYPE_VIDEO && $this->checkedPadVideo($this->pad_id)) {
                $this->addError('pad_id', '展示屏 已经有视频了');
                return ;
            }
            $criteria = new CDbCriteria;
            $criteria->select = 'store_id';
            $criteria->with = array(
                    'Pad_Store'=>array('select'=>''),
            );
            $criteria->addColumnCondition(array(
                '`t`.`status`'=>Pad::_STATUS_NORMAL,
                '`Pad_Store`.`status`'=>Store::_STATUS_NORMAL,
            ));
            if ( (!!$model = Pad::model()->findByPk($this->pad_id, $criteria)) && (!$this->checkedPad($this->pad_id, $this->ad_id)))
                $this->store_id = $model->store_id;
            else
                $this->addError('pad_id', '展示屏 不是有效值');
           
        }
    }
    
    /**
     * 检测广告是否归属展示屏
     * @param unknown $padModel
     * @param unknown $adModel
     * @return Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
     */
    public function checkedPad($pad_id, $ad_id)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'id';
        $criteria->addColumnCondition(array(
                'pad_id'=>$pad_id,
                'ad_id'=>$ad_id,
        ));
        return $this->find($criteria);
    }
    
    /**
     * 检测 屏是否已经选择了视频
     * @param unknown $pad_id
     * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
     */
    public function checkedPadVideo($pad_id, $ad_id = '')
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'id';
        $criteria->addColumnCondition(array(
                'pad_id'=>$pad_id,
                'ad_type'=>Ad::AD_TYPE_VIDEO,
        ));
        if ($ad_id != '')
            $criteria->compare('ad_id', '<>' . $ad_id);
        return $this->find($criteria);
    }
}