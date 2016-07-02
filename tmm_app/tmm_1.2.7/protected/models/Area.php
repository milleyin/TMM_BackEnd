<?php

/**
 * This is the model class for table "{{area}}".
 *
 * The followings are the available columns in table '{{area}}':
 * @property integer $id
 * @property string $name
 * @property string $nid
 * @property integer $pid
 * @property integer $sort
 * @property integer $agent_id
 * @property integer $admin_id
 * @property integer $admin_time
 * @property integer $status_hot
 */
class Area extends CActiveRecord
{
	/**
	 * 城市热门状态 未设置热门
	 * @var integer
	 */
	const status_hot_not=0;
	/**
	 * 城市热门状态 已设置热门
	 * @var integer
	 */
	const status_hot_yes=1;
	
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status_hot=array('未设置热门','已设置热门');
	
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_pid=array('省','市','区(县)');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('热门时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('admin_time'); 
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
	 * 省
	 * @var strint
	 */
	public $area_id_p;
	/**
	 * 市
	 * @var strint
	 */
	public $area_id_m;
	/**
	 * 区(县)
	 * @var strint
	 */
	public $area_id_c;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{area}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('admin_id,admin_time,status_hot,pid, sort, agent_id', 'numerical', 'integerOnly'=>true),
			array('name, nid', 'length', 'max'=>50),
			array('status_hot', 'length', 'max'=>10),
			array('admin_id,pid,id,agent_id', 'length', 'max'=>11),
			array('status_hot', 'length', 'max'=>3),
				
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('admin_id,admin_time,status_hot,search_time_type,search_start_time,search_end_time,id, name, nid, pid, sort, agent_id,area_id_p,area_id_m,area_id_c', 'safe', 'on'=>'search'),
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
			//下级
			'Area_Area' => array(self::HAS_MANY, 'Area', 'pid'),
			//下级
			'Area_Area_Area' => array(self::HAS_MANY, 'Area', 'pid'),
			//上级
			'Area_Area_M'=>array(self::BELONGS_TO,'Area','pid'),
			//上级
			'Area_Area_P'=>array(self::BELONGS_TO,'Area','pid'),
			//谁设置的热门城市
			'Area_Admin'=>array(self::BELONGS_TO,'Admin','admin_id'),
			//归属代理商
			'Area_Agent'=>array(self::BELONGS_TO,'Agent','agent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'nid' => '拼音',
			'pid' => '分类/父级',
			'sort' => '排序',
			'agent_id' => '归属代理商',
			'admin_id'=>'管理员',
			'admin_time'=>'热门时间',
			'status_hot'=>'热门状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'area_id_p'=>'省',
			'area_id_m'=>'市',
			'area_id_c'=>'区(县)',
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
			
			$criteria->with=array(
				'Area_Area_P',
				'Area_Admin',
				'Area_Agent',
			);

			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type])){
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('t.id',$this->id);
			$criteria->compare('t.name',$this->name,true);
			
			if ($this->nid != '' && preg_match ("/^[A-Za-z]/", $this->nid))
			{
				$criteria->params[':search']='%'.implode('%', str_split($this->nid)).'%';
				$condition='`t`.`nid` LIKE :search';
				$criteria->addCondition($condition);
			}
			
			if($this->pid != '' && $this->pid == 0)
				$criteria->addCondition('`t`.`pid`=0');
			elseif($this->pid != '' && $this->pid == 1)
				$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
			elseif($this->pid != '' && $this->pid==2)
				$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid` !=0');
				
			$criteria->compare('t.sort',$this->sort);
			$criteria->compare('Area_Agent.phone',$this->agent_id);
			$criteria->compare('Area_Admin.username',$this->admin_id);
			if($this->admin_time != '')
				$criteria->addBetweenCondition('t.admin_time',strtotime($this->admin_time),(strtotime($this->admin_time)+3600*24-1));
			$criteria->compare('t.status_hot',$this->status_hot);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					//r'defaultOrder'=>'t.add_time desc', //设置默认排序
			),
		));
	}
	
	/**
	 * 选择添加或者删除热门城市
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_select($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ==='')
		{
			$criteria=new CDbCriteria;
			
			$criteria->together=true;
			$criteria->group='`t`.`id`';
						
			$criteria->with=array(
					'Area_Area_P'=>array('select'=>'name'),
					'Area_Area'=>array('select'=>'name,nid'),
					'Area_Admin'=>array('select'=>'username,name'),
			);
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type])){
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
				elseif($this->search_start_time !='' && $this->search_end_time =='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
			}
			
			$criteria->compare('t.id',$this->id);	
			//排除nane=0的市
			$criteria->addCondition(" `t`.`name`!='0' ");
			//自己pid 不等于0 自己的父 等于0
			$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');

			if ($this->nid != '' && preg_match ("/^[A-Za-z]/", $this->nid))
			{
				$criteria->params[':nid']='%'.implode('%', str_split($this->nid)).'%';
				$condition='`t`.`nid` LIKE :nid OR `Area_Area_P`.`nid` LIKE :nid OR `Area_Area`.`nid` LIKE :nid';
				$criteria->addCondition($condition);
			}

			if($this->name != '')
			{
				$criteria->params[':name']='%'.strtr($this->name,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				$condition='`t`.`name` LIKE :name OR `Area_Area_P`.`name` LIKE :name OR `Area_Area`.`name` LIKE :name';
				$criteria->addCondition($condition);
			}

			if($this->admin_time != '')
				$criteria->addBetweenCondition('t.admin_time',strtotime($this->admin_time),(strtotime($this->admin_time)+3600*24-1));
			$criteria->compare('t.status_hot',$this->status_hot);
		}
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>Yii::app()->params['admin_pageSize'],
				),
				'sort'=>array(
						'defaultOrder'=>'`t`.`nid`', //设置默认排序
				),
		));
	}
	

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Area the static model class
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
			if($this->isNewRecord){
				$time=time();
				if(isset($this->add_time))
					$this->add_time=$time;
				if(isset($this->up_time))
					$this->up_time=$time;
			}else{
				if(isset($this->up_time))
					$this->up_time=time();
			}
			return true;
		}else
			return false;
	}

	/**
	 * 树形显示
	 * @param unknown $model
	 * @param unknown $id
	 */
	public static function tree($model,$id){
		return self::_show(self::_data($model,$id));
	}

	/**
	 * 栏目无限极递归
	 * @param unknown $model
	 * @param number $id
	 * @param array $array
	 * @return multitype:unknown multitype:unknown NULL
	 */
	public static function _data($model,$id=0,$array=array())
	{
		foreach ($model as $data)
		{
			if($id==$data->pid)
				$array[]=array('data'=>$data,'son'=>self::_data($model,$data->id));
		}
		return $array;
	}

	/**
	 * 递归分类展示
	 * @param unknown $data
	 * @param string $html
	 * @return string
	 */
	public static function _show($data,$html='',$template=array('<li>','<ul>','</ul>','</li>')){
		foreach($data as $v){
			$html .= $template[0].self::buttons($v['data']);
			if(isset($v['son'][0]))
				$html .=$template[1].self::_show($v['son']).$template[2];
			$html .=$template[3];
		}
		return $html;
	}

	/**
	 * 分类显示按钮操作
	 * @param unknown $data
	 * @return string
	 */
	public static function buttons($data){
		$html = '<input type="checkbox" value="'.$data->id.'" name="area[]" />';
		$html .='<label>'.$data->name.'</label>';
		return $html;
	}

	
	/**
	 * 返回一个对象
	 * @param unknown $name
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function id($id){
		return self::model()->find('id=:id',array(':id'=>$id));
	}
	/**
	 * 返回一个对象
	 * @param unknown $name
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function name($name){
		return self::model()->find('name=:name',array(':name'=>$name));
	}
	/**
	 * 返回多个对象 根据id
	 * @param number $p_id
	 * @return Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function data_id($p_id=0){
		return self::model()->findAll('`pid`=:pid',array(':pid'=>$p_id));
	}
	/**
	 * 返回多个对象 根据name
	 * @param unknown $name
	 * @return NULL
	 */
	public static function data_name($name='',$all=true){
		if($all && $name=='')
			return self::data_id();
		elseif(!$all && $name=='')
		return array();
		elseif(!! $model=self::name($name))
		return self::data_id($model->id);
		return array();
	}
	/**
	 * 开始前面添加
	 * @param unknown $array
	 * @return unknown
	 */
	public static function start($array=array(''=>'--请选择--')){
		if($array)
			return $array==array(''=>'--请选择--')?array(''=>'--请选择--'):$array;
		else
			return array();
	}
	/**
	 * 返回数组 根据id
	 * @param number $p_id
	 * @return Ambigous <multitype:unknown , string, mixed, unknown, multitype:unknown mixed >
	 */
	public static function data_array_id($p_id=0,$array=true){
		return self::start($array)+CHtml::listData(self::data_id($p_id),'id','name');
	}
	
	/**
	 * 返回数组
	 * @param number $p_id
	 * @return Ambigous <multitype:unknown , string, mixed, unknown, multitype:unknown mixed >
	 */
	public static function data_array_name($name='',$array=true,$all=true){
		return self::start($array)+CHtml::listData(self::data_name($name,$all),'name','name');
	}
	
	/**
	 * 返回字符串
	 * @param number $p_id
	 * @param string $str
	 * @return string
	 */
	public static function data_str($p_id=0,$str=''){
		$action=self::data_id($p_id);
		if(!$action)
			return self::data_start();
		foreach($action as $v)
			$str .= CHtml::tag('option', array('value'=>$v->id),CHtml::encode($v->name), true);
		return $str;
	}
	
	/**
	 * 返回字符串
	 * @param number $p_id
	 * @param string $str
	 * @return string
	 */
	public static function data_str_name($name=0,$str=''){
		$action=self::data_name($name);
		if(!$action)
			return self::data_start();
		foreach($action as $v)
			$str .= CHtml::tag('option', array('value'=>$v->name),CHtml::encode($v->name), true);
		return $str;
	}
	
	/**
	 * 返回开始
	 * @return string
	 */
	public static function data_start(){
		foreach (self::start() as $k=>$v)
			return CHtml::tag('option', array('value'=>$k),CHtml::encode($v), true);
	}
	
	/**
	 * 请求方法
	 */
	public static function action(){
		if(isset($_POST['area_id_p'])){
			if($_POST['area_id_p'] !=''){
				$data1=Area::data_str($_POST['area_id_p'],Area::data_start());
				$data2=Area::data_start();
				echo json_encode(array($data1,$data2));
			}else
				echo json_encode(array(Area::data_start(),Area::data_start()));
		}elseif(isset($_POST['area_id_m'])){
			if($_POST['area_id_m'] !='')
				echo Area::data_str($_POST['area_id_m'],Area::data_start());
			else
				echo Area::data_start();
		}elseif(isset($_POST['area_id_c']) ){
			if($_POST['area_id_c'] !='')
				echo Area::data_str($_POST['area_id_c'],Area::data_start());
			else
				echo Area::data_start();
		}
	}
	
	/**
	 * 请求方法
	 */
	public static function action_name(){
		if(isset($_POST['area_id_p'])){
			if($_POST['area_id_p'] !=''){
				$data1=Area::data_str_name($_POST['area_id_p'],Area::data_start());
				$data2=Area::data_start();
				echo json_encode(array($data1,$data2));
			}else
				echo json_encode(array(Area::data_start(),Area::data_start()));
		}elseif(isset($_POST['area_id_m'])){
			if($_POST['area_id_m'] !='')
				echo Area::data_str_name($_POST['area_id_m'],Area::data_start());
			else
				echo Area::data_start();
		}elseif(isset($_POST['area_id_c']) ){
			if($_POST['area_id_c'] !='')
				echo Area::data_str_name($_POST['area_id_c'],Area::data_start());
			else
				echo Area::data_start();
		}
	}
	
	/**
	 * 是否是省
	 * @param unknown $id
	 * @param string $data
	 * @return Ambigous <boolean, static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function is_p($id,$data=false){
		$model=self::model()->findByPk($id,array('select'=>'pid'));
		return $data?$model:($model?$model->pid==0:false);
	}
	/**
	 * 是否是市
	 * @param unknown $id
	 * @param string $data
	 * @return Ambigous <static, boolean, Ambigous, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function is_m($id,$data=false){
		$model=self::model()->findByPk($id,array('select'=>'pid'));
		return $data?$model:($model?self::is_p($model->pid):false);
	}
	/**
	 * 是否是县（区）
	 * @param unknown $id
	 * @param string $data
	 * @return Ambigous <static, boolean, Ambigous, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function is_c($id,$data=false){
		$model=self::model()->findByPk($id,array('select'=>'pid'));
		return $data?$model:($model?self::is_m($model->pid):false);
	}
	
	public static function demo(){
		/**
		 <div class="row">
			<?php echo $form->labelEx($model,'area_id_p'); ?>
			<?php echo $form->dropDownList($model,'area_id_p',Area::data_array_id(),array(
			'ajax'=>array(
			'type'=>'POST',
			'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
			'dataType'=>'json',
			'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
			//	'update'=>'#'.CHtml::activeId($model,'area_id_m'),
			'success'=>'function(data){
			jQuery("#'.CHtml::activeId($model,'area_id_m').'").html(data[0]);
			jQuery("#'.CHtml::activeId($model,'area_id_c').'").html(data[1]);
			}',
			),
			));
			?>
			<?php echo $form->error($model,'area_id_p'); ?>
			</div>
			<div class="row">
			<?php echo $form->labelEx($model,'area_id_m'); ?>
			<?php echo $form->dropDownList($model,'area_id_m',Area::data_array_id($model->area_id_p),array(
			'ajax'=>array(
			'type'=>'POST',
			'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
			'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
			'update'=>'#'.CHtml::activeId($model,'area_id_c'),
			),
			)); ?>
			<?php echo $form->error($model,'area_id_m'); ?>
			</div>
			<div class="row">
			<?php echo $form->labelEx($model,'area_id_c'); ?>
			<?php echo $form->dropDownList($model,'area_id_c',Area::data_array_id($model->area_id_m)); ?>
			<?php echo $form->error($model,'area_id_c'); ?>
			</div>
			*/
	}
	
	public static function getHotCity()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array('Area_Area_P');
		//排除nane=0的市
		$criteria->addCondition(" `t`.`name`!='0' ");
		//自己pid 不等于0 自己的父 等于0
		$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
		$criteria->addColumnCondition(array(
			'`t`.`status_hot`'=>self::status_hot_yes //热门城市
		));
		$criteria->order='`t`.`admin_time` desc,`t`.`nid`';
		return self::model()->findAll($criteria);
	}
	
	/**
	 * 市
	 * @param string $search
	 * @param number $page
	 */
	public static function getAreaCity($search='')
	{		
		$criteria=new CDbCriteria;
		$criteria->select='`t`.`id`';
		$criteria->with=array(
				'Items_area_id_m_Area_id'=>array(
						'with'=>array(
								'Area_Area_P',
								'Area_Area',
						)
				)
		);
		$criteria->group='`t`.`area_id_m`';
		$criteria->addColumnCondition(array(
			'`t`.`status`'=>Items::status_online,
		));
		//排除nane=0的市
		$criteria->addCondition(" `Items_area_id_m_Area_id`.`name`!='0' ");
		//自己pid 不等于0 自己的父 等于0
		$criteria->addCondition('`Items_area_id_m_Area_id`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
		$criteria->order='`Items_area_id_m_Area_id`.`nid`';
		if ($search != '' && preg_match ("/^[A-Za-z]/", $search))
		{
			$criteria->params[':search']='%'.implode('%', str_split($search)).'%';
			$condition='`Items_area_id_m_Area_id`.`nid` LIKE :search OR `Area_Area_P`.`nid` LIKE :search OR `Area_Area`.`nid` LIKE :search';
			$criteria->addCondition($condition);
		}
		elseif($search != '')
		{
			$criteria->params[':search']='%'.strtr($search,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$condition='`Items_area_id_m_Area_id`.`name` LIKE :search OR `Area_Area_P`.`name` LIKE :search OR `Area_Area`.`name` LIKE :search';
			$criteria->addCondition($condition);
		}
		return Items::model()->findAll($criteria);
	}
	
	/**
	 * 获取所有的城市
	 * @return Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function getAreaCityAll($search='')
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Area_Area_P',
				'Area_Area',
		);
		//排除nane=0的市
		$criteria->addCondition(" `t`.`name`!='0' ");
		//自己pid 不等于0 自己的父 等于0
		$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
		$criteria->order='`t`.`nid`';
		if ($search != '' && preg_match ("/^[A-Za-z]/", $search))
		{
			$criteria->params[':search']='%'.implode('%', str_split($search)).'%';
			$condition='`t`.`nid` LIKE :search OR `Area_Area_P`.`nid` LIKE :search OR `Area_Area`.`nid` LIKE :search';
			$criteria->addCondition($condition);
		}
		elseif($search != '')
		{
			$criteria->params[':search']='%'.strtr($search,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$condition='`t`.`name` LIKE :search OR `Area_Area_P`.`name` LIKE :search OR `Area_Area`.`name` LIKE :search';
			$criteria->addCondition($condition);
		}
		return self::model()->findAll($criteria);
	}
	
	/**
	 * 结果处理函数
	 * @param unknown $result
	 * @param string $type
	 * @param unknown $raw
	 * @return unknown|multitype:NULL Ambigous <number, unknown> number |multitype:NULL string |multitype:
	 */
	public static function Result($result,$type='location',$raw)
	{
		if($raw)
			return $result;
		if($type=='location' && isset($result['status'],$result['info'],$result['geocodes'][0]) && $result['status']==1 && $result['info']=='OK')
		{
			if(isset($result['geocodes'][0]['location']))
			{
				$location=explode(',', $result['geocodes'][0]['location']);
				return array(
						'location'=>$result['geocodes'][0]['location'],
						'lng'=>isset($location[0])?$location[0]:0.000000,
						'lat'=>isset($location[1])?$location[1]:0.000000,
				);
			}
		}
		elseif($type=='address' && isset($result['status'],$result['info'],$result['regeocode']['addressComponent']) && $result['status']==1 && $result['info']=='OK')
		{
			if(isset($result['regeocode']['formatted_address'],$result['regeocode']['addressComponent']['province'],$result['regeocode']['addressComponent']['city'],$result['regeocode']['addressComponent']['district']))
				return array(
						'address'=>$result['regeocode']['formatted_address'],
						'province'=>is_array($result['regeocode']['addressComponent']['province'])?'':$result['regeocode']['addressComponent']['province'],
						'city'=>is_array($result['regeocode']['addressComponent']['city'])?'':$result['regeocode']['addressComponent']['city'],
						'district'=>is_array($result['regeocode']['addressComponent']['district'])?'':$result['regeocode']['addressComponent']['district'],
						'township'=>isset($result['regeocode']['addressComponent']['township'])?$result['regeocode']['addressComponent']['township']:'',
				);
		}
		return array();
	}
	
	/**
	 * 地址 获取经纬度
	 * @param unknown $address
	 * @return mixed
	 */
	public static function getLocation($address,$raw=false)
	{
		$params=array(
			'key'=>Yii::app()->params['amap_web_key'],
			'address'=>$address,
		);
		$url='http://restapi.amap.com/v3/geocode/geo';
		return self::Result(json_decode(self::getResult($url, $params),true),'location',$raw);
	}
	
	/**
	 * 获取当前的详细地址
	 * @param unknown $lng
	 * @param unknown $lat
	 * @return mixed
	 */
	public static function getAddress($lng,$lat,$raw=false)
	{
		$params=array(
				'key'=>Yii::app()->params['amap_web_key'],
				'location'=>$lng.','.$lat,
		);
		$url='http://restapi.amap.com/v3/geocode/regeo';
		return self::Result(json_decode(self::getResult($url, $params),true),'address',$raw);
	}
	
	/**
	 * 获取结果
	 * @param unknown $url
	 * @param unknown $data
	 * @return mixed
	 */
	public static function getResult($url,$data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		
		curl_setopt($ch, CURLOPT_POST, FALSE);
		$text='';
		foreach ($data as $key=>$value)
			$text[]= $key.'='.urlencode($value);
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $text));
		
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}
	
	/**
	 * 查询所有的 县(区) 如果 $array有值 去掉（省、市）
	 * @param unknown $array
	 * @return multitype:NULL
	 */
	public static function all_area($array=array(),$agent_id='')
	{
		$criteria=new CDbCriteria;
		$criteria->select='id,pid,name';
		$criteria->with=array(
				'Area_Area_M'=>array(
						'select'=>'id,pid',
						'with'=>array(	'Area_Area_P'=>array('select'=>'id,pid'))
				),
		);
	
		if(!empty($array))
			$criteria->addInCondition('`t`.`id`',$array);
		if($agent_id != '')
			$criteria->addColumnCondition(array('`t`.`agent_id`'=>$agent_id));
		$criteria->addCondition('`t`.`pid` !=0 AND `t`.`pid`=`Area_Area_M`.`id` AND `Area_Area_M`.`pid`=`Area_Area_P`.`id`');
		
		$model=self::model()->findAll($criteria);
		if($agent_id != '')
			return $model;
		$return=array();
		foreach ($model as $v)
			$return[]=$v->id;
		return $return;
	}
	
	/**
	 * 显示归属权限 县(区)
	 * @param unknown $data
	 * @return string
	 */
	public static function view_all_area($data){
		$i=0;
		$return='';
		foreach ($data as $v)
		{
			if($i>10) {
				$return .= '[<b>' . $v->name . '</b>]<br>';
				$i=-1;
			}else
				$return .= '[<b>' . $v->name . '</b>] ';
			$i++;
		}
		return $return;
	}
	
	public static function demo_name(){
		/**
			<div class="row">
			<?php echo $form->labelEx($model,'area_id_p'); ?>
			<?php echo $form->dropDownList($model,'area_id_p',Area::data_array_name(),array(
			'ajax'=>array(
			'type'=>'POST',
			'url'=>Yii::app()->createUrl('/admin/tmm_home/area_name'),
			'dataType'=>'json',
			'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
			//	'update'=>'#'.CHtml::activeId($model,'area_id_m'),
			'success'=>'function(data){
			jQuery("#'.CHtml::activeId($model,'area_id_m').'").html(data[0]);
			jQuery("#'.CHtml::activeId($model,'area_id_c').'").html(data[1]);
			}',
			),
			));
			?>
			</div>
			<div class="row">
			<?php echo $form->labelEx($model,'area_id_m'); ?>
			<?php echo $form->dropDownList($model,'area_id_m',Area::data_array_name($model->area_id_p,true,false),array(
			'ajax'=>array(
			'type'=>'POST',
			'url'=>Yii::app()->createUrl('/admin/tmm_home/area_name'),
			'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
			'update'=>'#'.CHtml::activeId($model,'area_id_c'),
			),
			)); ?>
			</div>
			<div class="row">
			<?php echo $form->labelEx($model,'area_id_c'); ?>
			<?php echo $form->dropDownList($model,'area_id_c',Area::data_array_name($model->area_id_m,true,false)); ?>
			</div>
			*/
	}
}