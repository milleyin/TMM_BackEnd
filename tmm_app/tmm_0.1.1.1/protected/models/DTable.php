<?php
 /**
  * DTable provides dynamic table model supports for some application environments
  * such as dynamic-generated database tables, or simple CRUD actions.
  * @property string $tableName the table name associate with the denamic model.
  * new record :
  * $model = new DTable('table_name'); 
  * //use table prefix:
  * $model = new DTable('{{table_name}}');
  * $model->id = $id;
  * $model->name = 'zhangxugg@163.com';
  * $model->save();
  * 
  * update:
  * $model = DTable::model('{{table_name}}')->findByPk(1);
  * if($model) {
  *   $model->name = 'zhangxugg@163.com'
  *   $model->save();
  * }
  * $list = $model->findAll();
  * 
  * use non-default database connection :
  * DTable::$db = Yii::app()->getCompoments('db-extra');
  * tips : you must define the database connection informations in config/main.php
  * 'components' => array(
  *     'db-extra' => array(
  *         'class' => 'CDbConnection',
  *         'connectionString' => 'mysql:host=localhost;dbname=cdcol;charset=utf8',
  *         'username' => 'root',
  *         'password' =>'',
  *         'tablePrefix' => '',
  *         'autoConnect' => false,
  *     ),
  * )
  * 
  *
  */ 
class DTable extends CActiveRecord {
    
    private $_tableName ;
    private $_md ;
    
    public static $_models ;
    
    public function __construct($table_name = '') {
        if($table_name != null) {
            $this->_tableName = $table_name ;
            parent::__construct();
        }        
    }
    
    public function tableName()
    {
    	return $this->_tableName;
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
    
    

    
  	public static function model($tableName='')
	{
		if(isset(self::$_models[$tableName]))
			return self::$_models[$tableName];
		else
		{
			$model=self::$_models[$tableName]=new DTable(null);
     		$model->_tableName = $tableName ;
			$model->_md=new CActiveRecordMetaData($model);
			$model->attachBehaviors($model->behaviors());
			return $model;
		}
	}
	
  	public function refreshMetaData()
	{
		$finder=self::model($this->_tableName);
		$finder->_md=new CActiveRecordMetaData($finder);
		if($this!==$finder)
			$this->_md=$finder->_md;
	}
    
 	protected function instantiate($attributes)
	{
		$model=new DTable(null);
   		$model->_tableName = $this->_tableName;
		return $model;
	}
    
  	public function getMetaData()
	{
		if($this->_md!==null)
			return $this->_md;
		else
			return $this->_md=self::model($this->_tableName)->_md;
	} 
}