<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-23 10:38:12 */
class ActivesController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Actives';
	/**
	 * 新的统计数量
	 * @var unknown
	 */
	public $_new_number=array();

	/**
	 * 是否组织者 默认是组织者
	 * @var int
	 * @update 2016-01-28
	 */
	public $_is_organizer = Actives::is_organizer_yes;

	/**
	 *  是否开放 默认开放
	 * @var int
	 * @update 2016-02-18
	 */
	public $_is_open = Actives::is_open_yes;

	/**
	 *  支付方式  默认 AA
	 * @var int
	 * @update 2016-02-18
	 */
	public $_pay_type = Actives::pay_type_AA;

	/**
	 * 活动服务费
	 * @var float
	 */
	public $_tour_price   = 0.00;
	
	/**
	 * 创建活动
	 */
	public function actionCreate()
	{
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user();

		//获得当前分类ID
		$shops_classliy = ShopsClassliy::getClass();

//			$actives_test = array(
//				'actives_type' => 0,            //0=旅游活动1=农产品活动
//				'tour_type' => 0,               //-1=农产品活动,0=多个点,1=一条线
//				'number' => 20,                 //活动数量
//				'price' => 200.00,              //活动单价
//				'tour_price' => 10.00,         //服务费
//				'remark' => '创建线2',         //旅游活动备注
//				'start_time' => '2015-11-15', //报名开始时间
//				'end_time' => '2015-11-17',   //报名截止时间
//				'go_time'=>'2015-11-16', 	 //出游时间
//		   		'is_organizer'=>1,		     //是否组织者 1=是 0=不是        	update time : 2016-01-28
//				'is_open'=>1,				 //是否开放显示 1=开放 0=不开放  	update time : 2016-02-18
//				'pay_type'=>0,				 //付款方式 0=AA付款 1=全额付款		update time : 2016-02-18
//			);
//			$_POST = array(
//				'actives_thrand' => 34,          //线路ID 点为0
//				'is_insurance' => 1,
//				'shops_info'=>'123335',				//记录多个点时  数据
//				'Actives' => $actives_test,            //活动信息
//				'Shops' => $this->shops(),            //商品名称
//				'Pro' => $this->pro(),                //
//				'ProFare' => $this->profare(),
//			);
		//是否POST 提交
		if(isset($_POST['Actives']) && isset($_POST['Shops']) && isset($_POST['Pro']) && isset($_POST['ProFare'])  && isset($_POST['Actives']['tour_type']) && isset($_POST['Actives']['actives_type']) ){


			# 2016-02-18 新增 是否开放  和 付款方式 字段 赋值
			$this->field_evaluation($_POST['Actives']);

			$model=new Actives();//结伴游的主要表
			$model->scenario='create';
			$model->attributes=$_POST['Actives'];

			$model->Actives_Shops=new Shops;//结伴游的商品表
			$model->Actives_Shops->scenario='create_thrand';
			$model->Actives_Shops->attributes=$_POST['Shops'];

			$ProFare=new ProFare;//选中项目选中的价格表
			$time   = time();   //当前时间撮

			//判断创建活动类型
			if($_POST['Actives']['tour_type'] == Actives::tour_type_farm && $_POST['Actives']['actives_type'] == Actives::actives_type_farm){
				//农产品
				$this->send_error(DATA_NOT_SCUSSECS);
			}elseif($_POST['Actives']['tour_type'] == Actives::tour_type_dot && $_POST['Actives']['actives_type'] == Actives::actives_type_tour){

				//多个点
				if(is_array($_POST['Pro'])  && is_array($_POST['ProFare']) &&  (count($_POST['ProFare']) == count($_POST['Pro'])) ){

					$model->Actives_Shops->scenario = 'create_thrand';
					$Pro = new Pro;//选中点的选中项目表
					$Pro->scenario = 'create_thrand';
					$ProFare = new ProFare;//选中项目选中的价格表
					$ProFare->scenario = 'create_thrand';

					$Pro->Pro_ProFare = array($ProFare);//关系赋值 一个项目可以选多个价格 默认一个
					$model->Actives_Pro = array($Pro);//一条线可以选择多个点中的项目 默认一个

					$model->Actives_ShopsInfo = new ShopsInfo();
					//验证数据是否有错误
					if ($this->validate_thrand($model, $shops_classliy->id))
					{
						$model->scenario='create';
						$model->attributes=$_POST['Actives'];
						//提前验证
						$validate_pros_fares = true;
						$validate_pros_fares=$model->validate();

						$model->Actives_Shops->scenario = 'create_thrand';
						if( ! $model->Actives_Shops->validate())
							$validate_pros_fares = false;


						foreach ($model->Actives_Pro as $pro) {
							if (!$pro->validate())
								$validate_pros_fares = false;
							foreach ($pro->Pro_ProFare as $fare) {
								if (!$fare->validate())
									$validate_pros_fares = false;
							}
						}
						if ($validate_pros_fares) {
							$status = $this->dot_save($model, $shops_classliy->id);
						}else
							$this->send_error_form($this->form_error(array($model,$model->Actives_Shops)));
					} else
						$this->send_error_form($this->form_error(array($model,$model->Actives_Shops)));

				}else
					$this->send_error(DATA_NOT_SCUSSECS);

			}elseif($_POST['Actives']['tour_type'] == Actives::tour_type_thrand && $_POST['Actives']['actives_type'] == Actives::actives_type_tour && isset($_POST['actives_thrand']) && is_numeric($_POST['actives_thrand'])  &&  $_POST['actives_thrand'] >0){
				//一条线
				$status = $this->thrand_save($model,$shops_classliy->id,$_POST['actives_thrand']);
			}else
				$this->send_error(DATA_NOT_SCUSSECS);

			/**
			 * 2016-02-26
			 * 新增返回 查看 当前创建 的活动的链接
			 * $status = 当前活动创建的ID
			 */
			//返回相应的状态
			if (isset($status) && $status) {
				//成功
				$return = array(
					'status' => STATUS_SUCCESS,
					'value'=>$status,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/oldview',array('id'=>$status)),
				);
				$this->send($return);
			} else
				$this->send_error(DATA_NOT_SCUSSECS);


		}

		$this->send_csrf();

	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);
				
		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//获得当前分类ID
		$shops_classliy = ShopsClassliy::getClass();

//		$actives_test = array(
//			'actives_type'=>0,			//0=旅游活动1=农产品活动
//			'tour_type'=>0,				//-1=农产品活动,0=多个点,1=一条线
//			'number'=>10,				//活动数量
//			'price'=>0.00,              //活动单价
//			'tour_price'=>40.00,		//服务费
//			'remark'=>'更新线2',        //旅游活动备注
//			'start_time'=>'2015-11-15', //报名开始时间
//			'end_time'=>'2015-11-18', 	//报名截止时间
//			'go_time'=>'2015-11-19', 	//出游时间
//		    'is_organizer'=>1,		    //是否组织者 1=是 0=不是        	update time : 2016-01-28
//			'is_open'=>1,				//是否开放显示 1=开放 0=不开放  	update time : 2016-02-18
//			'pay_type'=>0,				//付款方式 0=AA付款 1=全额付款		update time : 2016-02-18
//		);
//		$_POST = array(
//			'actives_thrand'=>34,          //线路ID 点为0
//			'is_insurance'=>1,
//			'shops_info'=>'6666',			//记录多个点时  数据
//			'Actives'=>$actives_test,		//活动信息
//			'Shops'=>$this->shops(),		//商品名称
//			'Pro'=>$this->pro(),			//
//			'ProFare'=>$this->profare(),
//		);

		//是否POST 提交
		if(isset($_POST['Actives'],$_POST['Shops'],$_POST['Pro'],$_POST['ProFare'],$_POST['Actives']['tour_type'],$_POST['Actives']['actives_type']))
		{
			# 2016-02-18 新增 是否开放  和 付款方式 字段 赋值
			$this->field_evaluation($_POST['Actives']);

			$model = $this->actives_model($id);//旅游活动的主要表
			$model->scenario = 'create';
			$time = time();   //当前时间撮

			$model->Actives_Shops->scenario='create_thrand';

			//判断创建活动类型
			if ($_POST['Actives']['tour_type'] == Actives::tour_type_farm && $_POST['Actives']['actives_type'] == Actives::actives_type_farm) {
				//农产品
				$this->send_error(DATA_NOT_SCUSSECS);
				//echo Actives::tour_type_farm;
			} elseif ($_POST['Actives']['tour_type'] == Actives::tour_type_dot && $_POST['Actives']['actives_type'] == Actives::actives_type_tour) {

				//多个点
				//echo Actives::tour_type_dot;
				//多个点
				if(is_array($_POST['Pro'])  && is_array($_POST['ProFare']) &&  (count($_POST['ProFare']) == count($_POST['Pro'])) ){

					$model->Actives_Shops->scenario = 'create_thrand';
					$Pro = new Pro;//选中点的选中项目表
					$Pro->scenario = 'create_thrand';
					$ProFare = new ProFare;//选中项目选中的价格表
					$ProFare->scenario = 'create_thrand';

					$Pro->Pro_ProFare = array($ProFare);//关系赋值 一个项目可以选多个价格 默认一个
					$model->Actives_Pro = array($Pro);//一条线可以选择多个点中的项目 默认一个

					$model->Actives_ShopsInfo = new ShopsInfo();
					//验证数据是否有错误
					if ($this->validate_thrand($model, $shops_classliy->id))
					{
						$model->scenario='create';
						$model->attributes=$_POST['Actives'];
						//提前验证
						$validate_pros_fares = true;
						$validate_pros_fares = $model->validate();

						$model->Actives_Shops->scenario = 'create_thrand';
						if( ! $model->Actives_Shops->validate())
							$validate_pros_fares = false;

						foreach ($model->Actives_Pro as $pro) {
							if (!$pro->validate())
								$validate_pros_fares = false;
							foreach ($pro->Pro_ProFare as $fare) {
								if (!$fare->validate())
									$validate_pros_fares = false;
							}
						}
						if ($validate_pros_fares) {
							$status = $this->dot_save($model, $shops_classliy->id,$id);
						}else
							$this->send_error_form($this->form_error(array($model,$model->Actives_Shops)));
					} else
						$this->send_error_form($this->form_error(array($model,$model->Actives_Shops)));

				}else
					$this->send_error(DATA_NOT_SCUSSECS);
			} elseif ($_POST['Actives']['tour_type'] == Actives::tour_type_thrand && $_POST['Actives']['actives_type'] == Actives::actives_type_tour && isset($_POST['actives_thrand']) && is_numeric($_POST['actives_thrand']) && $_POST['actives_thrand'] > 0) {
				//一条线
				//echo Actives::tour_type_thrand;
				$status = $this->thrand_save($model,$shops_classliy->id,$_POST['actives_thrand'],$id);
			} else
				$this->send_error(DATA_NOT_SCUSSECS);

			//返回相应的状态
			if (isset($status) && $status) {
				//成功
				$return = array(
					'status' => STATUS_SUCCESS,
				);
				$this->send($return);
			} else
				$this->send_error(DATA_NOT_SCUSSECS);
		}
		$this->send_csrf();
	}

	//====================================2016-2-23 活动编辑 单字段  start==============================================
	/**
	 * 2016-2-23
	 * 活动编辑=========  商品名称
	 * @param $id
	 * @return void
	 */
	public function actionUpdateShopsName($id){
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Shops']['name']) && $_POST['Shops']['name']) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->Actives_Shops->scenario='api_actives_shops_name';
			$model->Actives_Shops->attributes=$_POST['Shops'];

			if( ! $model->Actives_Shops->validate() )
				$this->send_error_form($this->form_error($model->Actives_Shops));

			if($model->Actives_Shops->save(false)){
				//成功
				$return = array(
					'status' => STATUS_SUCCESS,
				);
				$this->send($return);
			} else
				$this->send_error(DATA_NOT_SCUSSECS);
		}

		$this->send_csrf();
	}

	/**
	 * 2016-2-23
	 * 活动编辑======== 活动参与人数
	 * @param $id
	 * @return void
	 */
	public function actionUpdateNumber($id){
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Actives']['number']) && $_POST['Actives']['number']) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->scenario='api_update_actives_number';
			$model->attributes=$_POST['Actives'];

			if( ! $model->validate() )
				$this->send_error_form($this->form_error($model));
			//更新数据
			$this->update_actives_field($model);
		}

		$this->send_csrf();
	}

	/**
	 * 2016-2-23
	 * 活动编辑======== 服务费
	 * @param $id
	 * @return void
	 */
	public function actionUpdateTourPrice($id){
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Actives']['tour_price']) && $_POST['Actives']['tour_price']) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->scenario='api_update_actives_tour_price';
			$model->attributes=$_POST['Actives'];

			if( ! $model->validate() )
				$this->send_error_form($this->form_error($model));
			//更新数据
			$this->update_actives_field($model);
		}

		$this->send_csrf();
	}
	
	/**
	 * 2016-2-23
	 * 活动编辑======== 出游日期
	 * @param $id
	 * @return void
	 */
	public function actionUpdateGoTime($id){
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Actives']['go_time']) && $_POST['Actives']['go_time']) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->scenario='api_update_actives_go_time';
			$model->attributes=$_POST['Actives'];

			if( ! $model->validate() )
				$this->send_error_form($this->form_error($model));

			$model->go_time    	  = strtotime($model->go_time); 			//出游时间
			//更新数据
			$this->update_actives_field($model);
		}

		$this->send_csrf();
	}
	
	/**
	 * 2016-2-23
	 * 活动编辑======== 报名起止日期
	 * @param $id
	 * @return void
	 */
	public function actionUpdateStartEndTime($id){
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Actives']['start_time']) && $_POST['Actives']['start_time'] && isset($_POST['Actives']['end_time']) && $_POST['Actives']['end_time']) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->scenario='api_update_actives_start_end_time';
			$model->attributes=$_POST['Actives'];

			if( ! $model->validate() )
				$this->send_error_form($this->form_error($model));

			$model->start_time 	  = strtotime($model->start_time);     	//开始时间
			$model->end_time    	  = strtotime($model->end_time); 			//结止时间
			//更新数据
			$this->update_actives_field($model);
		}

		$this->send_csrf();
	}
	
	/**
	 * 2016-2-23
	 * 活动编辑======== 简介
	 * @param $id
	 * @return void
	 */
	public function actionUpdateRemark($id){
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Actives']['remark']) && $_POST['Actives']['remark']) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->scenario='api_update_actives_remark';
			$model->attributes=$_POST['Actives'];

			if( ! $model->validate() )
				$this->send_error_form($this->form_error($model));
			//更新数据
			$this->update_actives_field($model);
		}

		$this->send_csrf();
	}
	
	/**
	 * 2016-2-23
	 * 活动编辑======== 付款方式
	 * @param $id
	 * @return void
	 */
	public function actionUpdatePayType($id){

		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Actives']['pay_type']) && ($_POST['Actives']['pay_type'] || $_POST['Actives']['pay_type']== '0') ) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->scenario='api_update_actives_pay_type';
			$model->attributes=$_POST['Actives'];

			if( ! $model->validate() )
				$this->send_error_form($this->form_error($model));
			//更新数据
			$this->update_actives_field($model);
		}

		$this->send_csrf();
	}
	
	/**
	 * 2016-2-23
	 * 活动编辑======== 活动公开性
	 * @param $id
	 * @return void
	 */
	public function actionUpdateIsOpen($id){
		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//是否POST 提交
		if( isset($_POST['Actives']['is_open']) && ($_POST['Actives']['is_open'] || $_POST['Actives']['is_open']== '0') ) {

			$model = $this->actives_model($id);//旅游活动的主要表

			$model->scenario='api_update_actives_is_open';
			$model->attributes=$_POST['Actives'];

			if( ! $model->validate() )
				$this->send_error_form($this->form_error($model));
			//更新数据
			$this->update_actives_field($model);
		}

		$this->send_csrf();
	}

	/**
	 * 2016-2-24
	 * 活动编辑======= 线路变更
	 */
	public function actionUpdateThrand($id){

		//判断是否是组织者(是否登陆)
		$this->is_organizer_user($id);

		//判断当前活动是否可以编辑
		$this->is_actives_edit($id);

		//获得当前分类ID
		$shops_classliy = ShopsClassliy::getClass();

		if ( isset($_POST['actives_thrand']) && is_numeric($_POST['actives_thrand']) && $_POST['actives_thrand'] > 0) {

//			$thrand_models = $this->thrand_model($_POST['actives_thrand']);// 线路是否存在
//			if( !(isset($thrand_models->id) && $thrand_models->id)  ) {
//				$this->send_error(DATA_NOT_SCUSSECS);
//			}

			$model = $this->actives_model($id);//旅游活动的主要表
			//print_r($model);exit;
			$status = $this->update_actives_thrand_save($model, $shops_classliy->id, $_POST['actives_thrand'], $id);

			//返回相应的状态
			if (isset($status) && $status) {
				//成功
				$return = array(
					'status' => STATUS_SUCCESS,
				);
				$this->send($return);
			} else
				$this->send_error(DATA_NOT_SCUSSECS);
		}

		$this->send_csrf();
	}

	/**
	 * 2016-2-24
	 * 活动编辑======== 线路保存
	 * @param $model
	 * @param $actives_arr　所有数组
	 * @param $c_id        类型ID
	 * @param $thrand_id   线ID
	 * @param $old_id      旧线ID
	 * @return int
	 */
	private function update_actives_thrand_save($model,$c_id,$thrand_id,$old_id=''){

			$status = '';
			//事务
			$transaction=$model->dbConnection->beginTransaction();
			try{

				$text_con = $old_id?'更新':'创建';
				//删除旧的旅游活动相关表错误
				if($old_id){
					if(!  $this->del_actives($old_id) )
						throw new Exception($text_con."觅趣(线) 删除旧的觅趣相关表错误");
				}

				$model->Actives_Shops->c_id=$c_id;                     	//c_id
				$model->Actives_Shops->status=Shops::status_offline; 	//下线状态
				$model->Actives_Shops->audit=Shops::audit_pending; 	//未审核
				if($model->Actives_Shops->save(false)){

					$model->status     	  = Actives::status_not_publish;       //表示旅游活动没有成订单 下线状态
					if(! $model->save(false))
						throw new Exception($text_con."觅趣(线) 保存觅趣表错误");

					//迁移线的数据
					$thrand_id = $thrand_id;
					$model_thrand = $this->thrand_model($thrand_id);
					//商品ID（活动ID）
					$shop_id=$model->Actives_Shops->id;
					//复制线里的选中项目表到结伴游中
					foreach($model_thrand->Thrand_Pro as $val){
						$val->thrand_id = $thrand_id;
						$model_pro = $this->get_pro($val,$c_id,$shop_id);
						if(! $model_pro->save(false))
							throw new Exception($text_con."觅趣(线) 保存觅趣选中项目表错误");
						//选中项目表  ID
						$pro_id = $model_pro->id;
						foreach($val->Pro_ProFare as $kf=>$fare){
							$model_pro_fare = $this->get_pro_fare($fare,$pro_id);
							if(! $model_pro_fare->save(false))
								throw new Exception($text_con."觅趣(线) 保存觅趣选中项目对应价格表错误".$kf);
						}
					}

				}else
					throw new Exception($text_con."觅趣(线) 保存旅游觅趣表错误");
				//事务提交
				$transaction->commit();
				$status = $shop_id;

			}catch (Exception $e){
				//事务回滚
				$transaction->rollBack();
				$this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
			}

			return $status;


	}
	
	/**
	 * 2016-2-23
	 * 活动编辑======== 所有数据保存 方法
	 * @param $model
	 */
	private function update_actives_field($model){

		if($model->save(false)){
			//成功
			$return = array(
				'status' => STATUS_SUCCESS,
			);
			$this->send($return);
		} else
			$this->send_error(DATA_NOT_SCUSSECS);
	}

	//====================================2016-2-23 活动编辑 单字段  end==============================================

	/**
	 * 活动详情
	 * @param $id
	 */
	public function actionView($id)
	{
		// 活动类型
		$shops_classliy=ShopsClassliy::getClass();
		$c_id = $shops_classliy->id;
		//条件
		$criteria = new CDbCriteria;
		//关系
		$criteria->with = array(
				'Actives_Shops',
				'Actives_User'=>array('with'=>array('User_Organizer')),
				'Actives_OrderActives'=>array(
						'with'=>array(
								'OrderActives_OrderItems'=>array(
										'with'=>array(
												'OrderItems_OrderItemsFare',
												'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
												'OrderItems_ItemsClassliy',
										)
								),
						),
				),
		);
		//活动 私密活动 只能通过 密码进入 非私密活动 密码 ID 都可以进入
		$criteria->addCondition('(`t`.`is_open`=:is_open_yes AND (`t`.`id`=:id OR `t`.`barcode`=:id)) OR (`t`.`is_open`=:is_open_no AND `t`.`barcode`=:id)');
		$criteria->params[':is_open_yes'] = Actives::is_open_yes; 	//活动对外开放
		$criteria->params[':is_open_no'] = Actives::is_open_no;		//活动对外不开发
		$criteria->params[':id'] = $id;													//ID 或 密码
		//标准条件
		$criteria->addColumnCondition(array(
				'`t`.`c_id`'=>$c_id,															//活动类型
				'`Actives_Shops`.`audit`'=>Shops::audit_pass,				//活动审核通过
				'`Actives_Shops`.`status`'>Shops::status_online				//活动上线
		));
		//排序
		$criteria->order = '`OrderActives_OrderItems`.`shops_day_sort`,`OrderActives_OrderItems`.`shops_half_sort`,`OrderActives_OrderItems`.`shops_sort`';
		//查询
		$model = Actives::model()->find($criteria);
		// 找不到活动
		if (! $model)
			$this->send_error(DATA_NULL);
		// 添加 浏览量
		Shops::set_shops_brow($model->id);
		//获取商品的TAG
		$model->Actives_TagsElement = TagsElement::get_select_tags(TagsElement::tags_shops_actives, $model->id);
		//获取当前有没有点赞
		$model->Actives_Shops->Shops_Collect  = Collect::get_collect_praise(Collect::collect_type_praise, $model->id, Yii::app()->api->id);
		//输出的数据
		$return  = array();
		$datas = array(
			'name','c_id','list_info','page_info','brow','share','praise'
		);
		//商品列表详情
		foreach($datas as $data)
			$return[$data] = CHtml::encode($model->Actives_Shops->$data);		
		//运营商电话
		$return['manage_phone'] = CHtml::encode($model->Actives_User->phone);
		//田觅觅电话
		$return['tmm_phone'] = CHtml::encode(Yii::app()->params['tmm_400']);	
		//多少起
		$return['price'] = Actives::shops_price_num_after($model->id);
		//下单量
		$return['down'] = Actives::get_down($model->id);
		//标签
		foreach($model->Actives_TagsElement as $k=>$tags)
			$return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);
		//活动类型
		$return['tour_type'] = $model->tour_type;
		//显示图片
		$return['list_img'] = (isset($model->Actives_Shops->list_img) && $model->Actives_Shops->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Actives_Shops->list_img),'.'):'';
		$return['page_img'] = (isset($model->Actives_Shops->page_img) && $model->Actives_Shops->page_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Actives_Shops->page_img),'.'):'';
		//分享图片
		$share_img = $this->litimg_path(isset($model->Actives_Shops->list_img) ? $model->Actives_Shops->list_img : '', 'litimg_share', $this->litimg_path(isset($model->Actives_Shops->list_img) ? $model->Actives_Shops->list_img : ''));
		$return['share_image']=empty($share_img) ? '' : Yii::app()->params['admin_img_domain'] . ltrim($share_img, '.');	
		//项目名
		$return['c_name']  = CHtml::encode(ShopsClassliy::$_shop_type_name[$model->Actives_Shops->c_id]);
		//活动类型
		$return['actives'] = array(
				'name'=>Actives::$_actives_type[$model->actives_type],
				'value'=>$model->actives_type,
		);
		//扫描 码
		$return['actives_barcode'] = $model->barcode;
		//上 下线
		$return['shops_status'] = array(
			'name'=>Shops::$_status[$model->Actives_Shops->status],
			'value'=>$model->Actives_Shops->status,
		);
		//支付方式
		$return['actives_pay_type'] = array(
			'name'=>Actives::$_pay_type[$model->pay_type],
			'value'=>$model->pay_type,
		);
		// 显示方式
		$return['actives_is_open'] = array(
			'name'=>Actives::$_is_open[$model->is_open],
			'value'=>$model->is_open,
		);
		// 是否是组织者
		$return['is_organizer'] = array(
				'name'=>Actives::$_is_organizer[$model->is_organizer],
				'value'=>$model->is_organizer,
		);
		//活动简介
		$return['actives_info'] = array(
			'organizer'=>array(
				'phone'=>$model->Actives_User->phone,
			),
			'remark'=>$model->remark,//CHtml::encode($model->remark),
			'number'=>array(
				'name'=>'觅趣人数',
				'value'=>$model->number,
			),
			'order_number'=>array(
				'name'=>'剩余报名人数',
				'value'=>$model->order_number,
				'sign'=>$model->number-$model->order_number,
			),
			'tour_price'=>array(
				'name'=>'服务费/人',
				'value'=>$model->tour_price,
			),
			'tour_count'=>array(
				'name'=>'报名统计',
				'value'=>$model->tour_count,
			),
			'order_count'=>array(
				'name'=>'付款统计',
				'value'=>$model->order_count,
			),
		);
		//活动时间
		$return['actives_time'] = array(
				'start_time'=>date('Y-m-d',$model->start_time),
				'end_time'=>date('Y-m-d',$model->end_time),
				'go_time'=>$model->go_time==0 ? '出游日期未定' : date('Y-m-d',$model->go_time),
				'value'=>$model->go_time==0 ? 0 : 1, // 0表示报名 1表示我也想去
		);
		//活动状态
		$return['actives_status']=array(
			'name'=>Actives::$_actives_status[$model->actives_status],
			'value'=>$model->actives_status,
		);
		//AA付款 
		if ($model->pay_type == Actives::pay_type_AA)
		{
			//是否报名状态
			$actives_tour = Actives::actives_tour_exist_order($model->id);//获取报名信息
			if($actives_tour && isset($actives_tour->Actives_OrderActives->OrderActives_Order[0]))
			{	
				//订单状态
				$order_status = $actives_tour->Actives_OrderActives->OrderActives_Order[0]->order_status;
				//确认出游
				$order_status_go = $actives_tour->Actives_OrderActives->OrderActives_Order[0]->status_go;
				//支付状态
				$order_pay_status = $actives_tour->Actives_OrderActives->OrderActives_Order[0]->pay_status;
				//订单id
				$order_id = $actives_tour->Actives_OrderActives->OrderActives_Order[0]->id;
				//订单活动信息
				$return['order_actives'] = array(
					'order_status'=>array(
						'name'=>Order::$_order_status[$order_status],
						'value'=>$order_status,
					),
					'status_go'=>array(
						'name'=>Order::$_status_go[$order_status_go],
						'value'=>$order_status_go,
					),
					'pay_status'=>array(
						'name'=>Order::$_pay_status[$order_pay_status],
						'value'=>$order_pay_status,
					),
					'value'=>$order_id,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/view',array('id'=>$order_id)),
				);
			}
			else
			{
				$return['order_actives'] = array(
						'name'=>'未报名',
						'value'=>-1,
						'link'=>'',
				);
			}
		}
		else if ($model->pay_type == Actives::pay_type_full)
		{
			//默认报名信息
			$return['order_actives'] = array(
					'name'=>'未报名',
					'value'=>-1,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/attend/create',array('id'=>$id)),
			);
			if (Yii::app()->api->id)
			{
				//是否存在报名信息
				$actives_tour = Attend::getIsAttend($model->id, Yii::app()->api->id);
				if($actives_tour)
				{
					//用户等于报名信息
					if($actives_tour->user_id == $actives_tour->founder_id)
					{
						//活动总订单ID
						$orderActives_id = $model->Actives_OrderActives->id;
						//发布人看到详情页
						$return['order_actives']=array(
								'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/view',array('id'=>$orderActives_id)),
								'name'=>'已默认报名',
								'value'=>$orderActives_id,
						);
					}
					else
					{
						//活动总订单ID
						$attend_id = $actives_tour->id;
						//发布人看到详情页
						$return['order_actives']=array(
								'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/attend/view',array('id'=>$attend_id)),
								'name'=>'已报名',
								'value'=>$attend_id,
						);				
					}		
				}
				else if (Yii::app()->api->id == $model->organizer_id)
				{
					//活动总订单ID
					$orderActives_id = $model->Actives_OrderActives->id;
					//发布人看到详情页
					$return['order_actives']=array(
							'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/view',array('id'=>$orderActives_id)),							
							'name'=>'还没人报名',
							'value'=>$orderActives_id,
					);
				}
			}
		}
		//创建时间
		$return['add_time'] = Yii::app()->format->datetime($model->Actives_Shops->add_time);
		//审核状态
		$return['audit_type'] = (int)$model->Actives_Shops->audit;
		$return['audit_name'] = CHtml::encode(Items::$_audit[$model->Actives_Shops->audit]);

		//点赞====链接
		$return['link_collect'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/shops/collect',array('id'=>$model->id));
		$return['actives_id'] = $model->id;

		//点赞====状态
		$return['collent_status'] =  isset($model->Actives_Shops->Shops_Collect->is_collect) && $model->Actives_Shops->Shops_Collect->is_collect == 1 ? 1 : 0;
		$return['collent_name'] = $return['collent_status'] == 1 ? '已赞' : '取消';

		//卖、不
		$return['is_sale'] = array(
				'name'=>Shops::$_is_sale[$model->Actives_Shops->is_sale],
				'value'=>$model->Actives_Shops->is_sale
		);

		$data_array = array();
		$info_array = array();
		//日程安排====处理
		foreach ($model->Actives_OrderActives->OrderActives_OrderItems as $value)
		{
			$data_array[$value->shops_day_sort][$value->shops_half_sort][$value->shops_dot_id][$value->shops_sort] = $value;
			$info_array['dot_data'][$value->shops_dot_id] = $value->shops_dot_id;
			if($value->shops_half_sort==0 && $value->shops_sort==0)
				$info_array[$value->shops_day_sort] = array('info'=>$value->shops_info,'data'=>$value);
		}
		$num = 0;
		if($data_array && $info_array ){
			foreach ($data_array as $key=>$data_dot_sort)
			{
				// 日程安排====时间
				$return['list'][$num]['day'] = CHtml::encode(Pro::item_swithc($key));
				//日程亮点
				$return['list'][$num]['day_content'] = isset($info_array[$key])? CHtml::encode($info_array[$key]['info']):'';

				//$num_num 替换点的ID做下标(项目排序)
				$dot_num = 0;

				foreach ($data_dot_sort as $k=>$data_dot) {

					foreach ($data_dot as $dot_id => $data_items) {

						$return['list'][$num]['dot_list'][$dot_num]['day_dot_list_img']='';
						//$return['list'][$num]['dot_list'][$dot_num]['day_dot_list_img'] = (isset( $info_array['dot_data'][$dot_id]->list_img) &&  $info_array['dot_data'][$dot_id]->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path( $info_array['dot_data'][$dot_id]->list_img),'.'):'';
						//点ID
						$return['list'][$num]['dot_list'][$dot_num]['day_dot_id']   = (int)$dot_id;
						//点链接
						$return['list'][$num]['dot_list'][$dot_num]['day_dot_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/dot/view',array('id'=>$dot_id));

						foreach ($data_items as $sort => $items)
						{
							// 拼接的约定的规范格式
//							$return['OrderItems'][$key][$num][$dot_id][$dot_num][$items->items_id][$sort][] = array(
//								$items->id => array(
//									//'price' => $items->fare_price,
//									'number' => 0,
//									'count' => 0,
//								)
//							);
							//点名称
							$return['list'][$num]['dot_list'][$dot_num]['day_dot_name'] = CHtml::encode($items->shops_name);
							//项目id
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['value'] = $items->items_id;
							//排序
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['sort'] = $sort+1;
							//项目名称
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_name'] = CHtml::encode($items->items_name);
							//项目链接
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$items->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$items->items_id));
							//项目图片
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_img']   = isset($items->items_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($items->items_img),'.'):'';

							//项目类型
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_type']['value']  = (int)$items->items_c_id;
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_type']['name']   = CHtml::encode($items->items_c_name);
							//商家名称
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['store_name'] = CHtml::encode($items->OrderItems_StoreUser->Store_Content->name);
							//价格集合
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['shop_fare'] = Fare::show_order_organizer_fare_api($items,$items->OrderItems_ItemsClassliy->append=="Hotel"?true:false,true);
							//详细地址
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['address']   = CHtml::encode($items->items_address);
// 							//是否免费
// 							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['free_status']= array(
// 									'name'=>Items::$_free_status[$items->free_status],
// 									'value'=>CHtml::encode($items->free_status)
// 							);
						}
					}
					$dot_num ++;

				}
				$num ++;
			}
		}
		$return['list_num']=count($return['list']);
		$this->send($return);
	}
	
	/**
	 * 验证
	 * @param unknown $model
	 * @return boolean
	 */
	public function validate_thrand($model,$c_id)
	{
		//$validate_array=array();//需要验证的数据
		if(! ( isset($_POST['Pro']) && isset($_POST['ProFare'])))
		{
			$model->Actives_Shops->addError('name', '选择点或选择的项目或选择的价格 不可空白');
			return false;
		}
		$model->Actives_Shops->attributes=$_POST['Shops'];

		$day_number=count($_POST['Pro']);//天数
		if($day_number != count($_POST['ProFare'])) //比较是否为给每一天都选了价格
		{
			$model->Actives_Shops->addError('name', '选择点或选择线无选择价格');
			return false;
		}
		//线路中的天数至少一天 最多不超过 设置的天数
		if($day_number >=1 && $day_number<=Yii::app()->params['shops_thrand_day_number'])
		{
			$i=0;//项目数
			foreach ($_POST['Pro'] as $day_sort=>$day_dots)
			{
				if(!is_int($day_sort) || $day_sort<1 || ceil($day_sort/2)>Yii::app()->params['shops_thrand_day_number'])
				{
					$model->Actives_Shops->addError('name', '日程天数不是有效值');
					return false;
				}
				if(!is_array($day_dots) || empty($day_dots))
				{
					$model->Actives_Shops->addError('name', '觅趣中的选择点存在未上线的项目');
					return false;
				}
				$dot_sort=0; //点的排序
				$j=0;
				foreach ($day_dots as $half_sort=>$dot_items_ids)
				{
					if(!is_array($dot_items_ids) || empty($dot_items_ids))
					{
						$model->Actives_Shops->addError('name', '觅趣中的选择点存在未上线的项目');
						return false;
					}

					if($half_sort !=$dot_sort || $half_sort > Yii::app()->params['shops_thrand_one_day_dot_number'])
					{
						$model->Actives_Shops->addError('name', '觅趣中的选择点存在未上线的项目');
						return false;
					}

					foreach ($dot_items_ids as $dot_id=>$items)
					{
						if(!is_array($items) || empty($items))
						{
							$model->Actives_Shops->addError('name', '觅趣中的选择点存在未上线的项目');
							return false;
						}

						//获取id 点所有的信息
						$dot_items_fares_array=$this->get_dot($dot_id);

						if(empty($dot_items_fares_array))
						{
							$model->Actives_Shops->addError('name', '觅趣 选择点或项目或价格 不是有效值');
							return false;
						}

						$items_sort=0;//项目排序
						foreach ($items as $sort=>$item)
						{
							if($items_sort != $sort)
							{
								$model->Actives_Shops->addError('name', '觅趣中的选择点存在未上线的项目');
								return false;
							}
							//$this->p_r($dot_items_fares_array['items']);
							//判断点中是否有项目的id
							if(isset($dot_items_fares_array['items'][$item]) && $dot_items_fares_array['items'][$item]['is_validate'])
								$dot_items_fares_array['items'][$item]['is_validate']=false;//一个点不能有重复的项目
							else{
								$model->Actives_Shops->addError('name', '觅趣中或点存在已经选择的项目');
								return false;
							}

							//项目中的数据
							$item_data=$dot_items_fares_array['items'][$item]['data'];
							//赋值
							$Thrand_Pro=isset($model->Actives_Pro[$i])?$model->Actives_Pro[$i]:new Pro;
							$Thrand_Pro->scenario='create_thrand';
							$Thrand_Pro->shops_c_id=$c_id;
							$Thrand_Pro->sort=$sort;
							$Thrand_Pro->day_sort=$day_sort;
							$Thrand_Pro->half_sort=$half_sort;
							$Thrand_Pro->items_id=$item;
							$Thrand_Pro->dot_id=$dot_id;
							$Thrand_Pro->agent_id=$item_data->agent_id;
							$Thrand_Pro->store_id=$item_data->store_id;
							$Thrand_Pro->c_id=$item_data->c_id;

							if(! isset($_POST['ProFare'][$day_sort][$half_sort][$dot_id][$sort][$item]))
							{
								$model->Actives_Shops->addError('name', '觅趣中或点中的项目无选中的价格');
								return false;
							}

							$item_select_fares=$_POST['ProFare'][$day_sort][$half_sort][$dot_id][$sort][$item];
							if(!is_array($item_select_fares) || empty($item_select_fares))
							{
								$model->Actives_Shops->addError('name', '觅趣中或点中的项目选中的价格无效');
								return false;
							}

							$Pro_ProFares=array();
							$j=0;//价格数
							$fares=array();
							foreach ($item_select_fares as $fare)
							{
								if(isset($dot_items_fares_array['fares'][$item][$fare]) && $dot_items_fares_array['fares'][$item][$fare]['is_validate'])
									$dot_items_fares_array['fares'][$item][$fare]['is_validate']=false;
								else{
									$model->Actives_Shops->addError('name', '觅趣中或点中的项目选中的价格存在重复');
									return false;
								}
								$Pro_ProFare=isset($model->Actives_Pro[$i]->Pro_ProFare[$j])?$model->Actives_Pro[$i]->Pro_ProFare[$j]:new ProFare;
								$Pro_ProFare->scenario='create_thrand';
								$Pro_ProFare->fare_id=$fare;
								$Pro_ProFare->items_id=$item;
								$Pro_ProFares[]=$Pro_ProFare;
								if(in_array($fare, $fares))
								{
									$model->Actives_Shops->addError('name', '觅趣中或点中的项目选中的价格存在重复2');
									return false;
								}
								$fares[]=$fare;
								$j++;//价格数
							}
							$Thrand_Pro->Pro_ProFare=$Pro_ProFares;
							$Thrand_Pros[]=$Thrand_Pro;
							$this->_new_number[$i]=$j;
							$items_sort++;
							$i++;//项目数
						}
					}
					$dot_sort++;
				}
			}
		}
		if(! isset($Thrand_Pros))
		{
			$model->Actives_Shops->addError('name', '觅趣中的选择点中存在未上线的项目');
			return false;
		}

		$model->Actives_Pro=$Thrand_Pros;
		return true;
	}

	/**
	 * 创建（更新）旅游活动  ======线路保存
	 * @param $model
	 * @param $actives_arr　所有数组
	 * @param $c_id        类型ID
	 * @param $thrand_id   线ID
	 * @param $old_id      旧线ID
	 * @return int
	 */
	private function thrand_save($model,$c_id,$thrand_id,$old_id=''){
		//接收旅游活动信息和商品信息（活动名称）
		$model->attributes=$_POST['Actives'];
		$model->Actives_Shops->attributes=$_POST['Shops'];

		if(! ($model->validate() && $model->Actives_Shops->validate() ) )
			$this->send_error_form($this->form_error(array($model,$model->Actives_Shops)));

		//if( ! $this->thrand_model($thrand_id))
		//	$this->send_error(DATA_NOT_SCUSSECS);

		$status = '';
		//事务
		$transaction=$model->dbConnection->beginTransaction();
		try{

			$text_con = $old_id?'更新':'创建';
			//删除旧的旅游活动相关表错误
			if($old_id){
				if(!  $this->del_actives($old_id) )
					throw new Exception($text_con."觅趣(线) 删除旧的觅趣相关表错误");
			}

			$model->Actives_Shops->c_id=$c_id;                     	//c_id
			$model->Actives_Shops->status=Shops::status_offline; 	//下线状态
			$model->Actives_Shops->audit=Shops::audit_pending; 	//未审核
			if($model->Actives_Shops->save(false)){
				$model->id        		  = $model->Actives_Shops->id;      //旅游活动ID
				$model->c_id      		  = $c_id;                    	    //类型ID　３
				$model->organizer_id    = Yii::app()->api->id;           //组织者
				$model->actives_status  = Actives::actives_status_not_start;   //活动状态
				$model->start_time 	  = strtotime($model->start_time);     	//开始时间
				$model->end_time    	  = strtotime($model->end_time); 			//结止时间
				$model->go_time    	  = isset($model->go_time) && $model->go_time ?strtotime($model->go_time):0; 			//活动时间
				$model->order_number 	  = $model->number; //剩余数量
				$model->is_organizer	  = $this->_is_organizer;//是否组织者
				$model->is_open	  	  = $this->_is_open;//是否开放显示
				$model->pay_type		  = $this->_pay_type;//付款方式

				// 活动服务费
				if($this->_is_organizer == Actives::is_organizer_no)
					$model->tour_price	  = $this->_tour_price;//活动服务费

				$model->status     	  = Actives::status_not_publish;       //表示旅游活动没有成订单 下线状态
				if(! $model->save(false))
					throw new Exception($text_con."觅趣(线) 保存觅趣表错误");

				//迁移线的数据
				$thrand_id = $thrand_id;
				$model_thrand = $this->thrand_model($thrand_id);
				//商品ID（活动ID）
				$shop_id=$model->Actives_Shops->id;
				//复制线里的选中项目表到结伴游中
				foreach($model_thrand->Thrand_Pro as $val){
					$val->thrand_id = $thrand_id;
					$model_pro = $this->get_pro($val,$c_id,$shop_id);
					if(! $model_pro->save(false))
						throw new Exception($text_con."觅趣(线) 保存觅趣选中项目表错误");
					//选中项目表  ID
					$pro_id = $model_pro->id;
					foreach($val->Pro_ProFare as $kf=>$fare){
						$model_pro_fare = $this->get_pro_fare($fare,$pro_id);
						if(! $model_pro_fare->save(false))
							throw new Exception($text_con."觅趣(线) 保存觅趣选中项目对应价格表错误".$kf);
					}
				}

			}else
				throw new Exception($text_con."觅趣(线) 保存觅趣表错误");
			//事务提交
			$transaction->commit();
			$status = $shop_id ;

		}catch (Exception $e){
			//事务回滚
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}

		return $status;
	}

	/**
	 * 线的model
	 * @param $id
	 * @return mixed
	 */
	public function thrand_model($id){
		$this->_class_model='Thrand';
		$shops_classliy=ShopsClassliy::getClass();

		$thrand_model=$this->loadModel($id,array(
			'with'=>array(
				'Thrand_ShopsClassliy',
				'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
				'Thrand_Pro'=>array('with'=>array(
					'Pro_Thrand_Dot'=>array(
						'with'=>array(
							'Dot_Shops'=>array(
								'condition'=>'`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit_dot',
								'params'=>array(':audit_dot'=>Shops::audit_pass),
							),
						),
					),
					'Pro_Items'=>array(
						'with'=>array(
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
							'Items_ItemsImg',
							'Items_StoreContent'=>array('with'=>array('Content_Store')),
							'Items_Store_Manager',
							'Items_ItemsClassliy',
						),
						'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit_pro',
						'params'=>array(':audit_pro'=>Items::audit_pass),
					),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array(
						'with'=>array('ProFare_Fare'),
					),
				)),
			),
			'condition'=>'t.c_id=:c_id AND `Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit ',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
			'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));

		return $thrand_model;
	}

	/**
	 * 创建（更新）旅游活动  ======旧线路删除
	 * @param $id
	 */
	private function del_actives($id){
		$model_actives = $this->actives_model($id);
		$arr_pro = array();
		foreach($model_actives->Actives_Pro as $pro){
			$arr_pro[] =  $pro->id;
		}
		$str_pro = implode(',',$arr_pro);
		$fare_pro = ProFare::model()->deleteAll('pro_id in ('.$str_pro.')');
		if(!$fare_pro)
			return false;
		$pro = Pro::model()->deleteAll('shops_id=:shops_id',array(':shops_id'=>$id));
		if(!$pro)
			return false;

		return true;
	}

	/**
	 * @param $id
	 * @param string $type
	 * @return mixed
	 */
	private function actives_model($id,$type=''){

		$c_id=ShopsClassliy::getClass()->id;
		$this->_class_model='Actives';
		return $this->loadModel($id,array(
			'with'=>array(
				//'Actives_ShopsClassliy',
				'Actives_Shops'=>array('with'=>array('Shops_Agent')),
				'Actives_User',
				'Actives_Pro'=>array('with'=>array(
					'Pro_Group_Dot'=>array('with'=>'Dot_Shops'),
					'Pro_Group_Thrand'=>array('with'=>'Thrand_Shops'),
					'Pro_Items'=>array(
						'with'=>array(
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
							'Items_ItemsImg',
							'Items_StoreContent'=>array('with'=>array('Content_Store')),
							'Items_Store_Manager',
							'Items_ItemsClassliy',
						),
					),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
				)),
			),
			'condition'=>'`t`.`c_id`=:c_id AND `t`.`actives_status`=:actives_status AND `Actives_Shops`.`status`=:status AND `Actives_Shops`.`audit` != :audit',
			'params'=>array(':c_id'=>$c_id,':actives_status'=> Actives::actives_status_not_start,':status'=>Shops::status_offline,':audit'=>Shops::audit_pass),
			'order'=>'Actives_Pro.day_sort,Actives_Pro.half_sort,Actives_Pro.sort',
		));
	}
	
	/**
	 * 创建结伴游====点保存
	 * @param $model
	 * @param $c_id
	 * @return int|string
	 */
	private function dot_save($model,$c_id,$old_id='')
	{
		$status = '';
		//事物
		$transaction=$model->dbConnection->beginTransaction();
		try{

			$text_con = $old_id?'更新':'创建';
			//删除旧的旅游活动相关表错误
			if($old_id){
				if(!  $this->del_actives($old_id) )
					throw new Exception($text_con."觅趣(线) 删除旧的觅趣相关表错误");
			}

			$model->Actives_Shops->c_id=$c_id;
			$model->Actives_Shops->status=Shops::status_offline;
			$model->Actives_Shops->audit=Shops::audit_pending;
			if($model->Actives_Shops->save(false))
			{
				$model->id        		  = $model->Actives_Shops->id;      //旅游活动ID
				$model->c_id      		  = $c_id;                    	    //类型ID　３
				$model->organizer_id    = Yii::app()->api->id;           //组织者
				$model->actives_status  = Actives::actives_status_not_start;   //活动状态
				$model->start_time 	  = isset($model->start_time)?strtotime($model->start_time):time();              		//开始时间
				$model->end_time    	  = strtotime($model->end_time); //结止时间
				$model->go_time    	  = isset($model->go_time) && $model->go_time ?strtotime($model->go_time):0; 			//活动时间
				$model->status     	  = Actives::status_not_publish;//表示旅游活动没有成订单 下线状态
				$model->order_number 	  = $model->number; //剩余数量
				$model->is_organizer	  = $this->_is_organizer;//是否组织者
				$model->is_open	  	  = $this->_is_open;//是否开放显示
				$model->pay_type		  = $this->_pay_type;//付款方式

				// 活动服务费
				if($this->_is_organizer == Actives::is_organizer_no)
					$model->tour_price	  = $this->_tour_price;//活动服务费

				if(! $model->save(false))
					throw new Exception($text_con."线路(觅趣多点) 保存线路附表错误");

				$dot_ids=array();
				foreach ($model->Actives_Pro as $pro_save)
				{
					$dot_ids[]=$pro_save->dot_id;
					$pro_save->shops_id=$model->id;
					if(! $pro_save->save(false))
						throw new Exception($text_con."线路(觅趣多点) 保存选中项目表错误");
					foreach ($pro_save->Pro_ProFare as $fare_save)
					{
						$fare_save->pro_id=$pro_save->id;
						$fare_save->group_id=$model->id;
						if(! $fare_save->save(false))
							throw new Exception($text_con."线路(觅趣多点) 保存选中项目的选中价格表错误");
					}
				}
				//继承点的tags
				foreach ($dot_ids as $dot_id)
					$element_ids[]=array(TagsElement::tags_shops_dot,$dot_id);
				TagsElement::select_tags_ids_save(TagsElement::select_tags_all($element_ids), $model->Actives_Shops->id, TagsElement::tags_shops_actives,TagsElement::user);

				if(isset($_POST['shops_info']) && $_POST['shops_info']) {
					// 更新
					if ($old_id) {
						if( ! ShopsInfo::model()->updateByPk($model->Actives_Shops->id,array('info'=>$_POST['shops_info'])))
							throw new Exception($text_con . "线路(觅趣多点) 保存觅趣点详细信息错误");
					} else {
						// 添加
						$model->Actives_ShopsInfo->shops_id = $model->Actives_Shops->id;
						$model->Actives_ShopsInfo->info = $_POST['shops_info'];
						if (!$model->Actives_ShopsInfo->save(false))
							throw new Exception($text_con . "线路(觅趣多点) 保存觅趣点详细信息错误");
					}

				}

				//日志
				$return=$this->log($text_con.'线路(觅趣多点)',ManageLog::user,ManageLog::create);
			}else
				throw new Exception("审核通过保存错误");
			$transaction->commit();
			$status = $model->id ;

		}catch(Exception $e){
			//事务回滚
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::api,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}
		return $status;
	}
	
	/**
	 * 获得点
	 * @param $dot_id
	 * @return array
	 */
	public function get_dot($dot_id)
	{
		$model=Dot::model()->findByPk($dot_id,array(
			'with'=>array(
				'Dot_Shops'=>array(
					'condition'=>'`Dot_Shops`.`status`=:status && `Dot_Shops`.`audit`=:audit',
					'params'=>array(':status'=>Shops::status_online,':audit'=>Shops::audit_pass),
				),
				'Dot_Pro'=>array(
					'with'=>array(
						'Pro_ItemsClassliy',
						'Pro_Items'=>array(
							'with'=>array(
								'Items_StoreContent'=>array('with'=>array('Content_Store')),
								'Items_Fare',
								'Items_area_id_p_Area_id',
								'Items_area_id_m_Area_id',
								'Items_area_id_c_Area_id',
							),
							'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit',
							'params'=>array(':audit'=>Items::audit_pass),
						),
					),
					'order'=>'Dot_Pro.sort',
				),
			),
		));
		if($model)
		{
			if($model->Dot_Shops)
				$shops=$model->Dot_Shops;
			else
				return array();
			if(! $model->Dot_Pro)
				return array();

			foreach ($model->Dot_Pro as $Dot_Pro)
			{
				if(! $Dot_Pro->Pro_Items)
					return array();
				$items[$Dot_Pro->items_id]=array('data'=>$Dot_Pro->Pro_Items,'is_validate'=>true);
				if(! $Dot_Pro->Pro_Items->Items_Fare)
					return array();
				foreach ($Dot_Pro->Pro_Items->Items_Fare as $Items_Fare)
					$fares[$Dot_Pro->items_id][$Items_Fare->id]=array('data'=>$Items_Fare,'is_validate'=>true);
			}

			return array('shops'=>$shops,'items'=>$items,'fares'=>$fares);
		}
		return array();
	}
	
	/**
	 * tmm_pro
	 */
	public function get_pro($val,$c_id,$shop_id){
		$model_pro = new Pro();

		$model_pro->shops_id     =$shop_id;
		$model_pro->agent_id     =$val->agent_id;
		$model_pro->store_id     =$val->store_id;
		$model_pro->shops_c_id   =$c_id;
		$model_pro->c_id          =$val->c_id;
		$model_pro->sort          =$val->sort;
		$model_pro->day_sort     =$val->day_sort;
		$model_pro->half_sort    =$val->half_sort;
		$model_pro->items_id     =$val->items_id;
		$model_pro->dot_id       =$val->dot_id;
		$model_pro->thrand_id    =$val->thrand_id;
		$model_pro->info          =$val->info;
		$model_pro->add_time     =$val->add_time;
		$model_pro->up_time      =$val->up_time;
		$model_pro->audit        =$val->audit;
		$model_pro->status       =$val->status;

		return $model_pro;
	}

	/**
	 * tmm_pro_fare
	 */
	public function get_pro_fare($fare,$pro_id){
		$model_pro_fare = new ProFare();

		$model_pro_fare->pro_id      = $pro_id;
		$model_pro_fare->fare_id     = $fare->fare_id;
		$model_pro_fare->group_id    = $fare->group_id;
		$model_pro_fare->items_id    = $fare->items_id;
		$model_pro_fare->thrand_id   = $fare->thrand_id;
		$model_pro_fare->add_time    = $fare->add_time;
		$model_pro_fare->up_time     = $fare->up_time;
		$model_pro_fare->status      = $fare->status;

		return $model_pro_fare;
	}

	/**
	 * 判断是否是组织者(是否登陆)
	 * @return bool
	 */
	private function is_organizer_user($id='')
	{
		if($id == '')
		{
			//判断是否是组织者(是否登陆)
			//$model_organizer = User::model()->find('is_organizer=1 AND audit=:audit AND  status=1 AND  id='.Yii::app()->api->id,array(':audit'=>User::audit_pass));
			$model_organizer = User::model()->findByPk(Yii::app()->api->id,array(
					'with'=>array('User_Organizer'),
					'condition'=>'`t`.`status`=1'
			));
			if(!$model_organizer)
				$this->send_error(DATA_NULL);
			if($model_organizer->is_organizer == User::organizer && $model_organizer->audit ==User::audit_pass && isset($model_organizer->User_Organizer) && $model_organizer->User_Organizer->status ==1)
				$this->_is_organizer = Actives::is_organizer_yes;
			else
				$this->_is_organizer = Actives::is_organizer_no;
		}
		else
		{
			$model = Actives::model()->findByPk($id);
			if($model)
			{
				$this->_is_organizer = $model->is_organizer;
			}
			else
			{
				$this->send_error(DATA_NULL);
			}
		}
		
// 		if( ! ( $model_organizer->is_organizer == User::organizer && $model_organizer->audit ==User::audit_pass)  )
// 			$this->_is_organizer = Actives::is_organizer_no;

		return true;
	}

	/**
	 * 2016-02-18
	 * 新增 是否开放  和 付款方式 字段 赋值
	 * @param $data
	 */
	private function field_evaluation($data)
	{
		//是否开放
		if(isset($data['is_open']) && is_numeric($data['is_open']) && in_array($data['is_open'],array_keys(Actives::$_is_open)) )
			$this->_is_open = $data['is_open'];
		else 
			return false;
		//付款方式
		if(isset($data['pay_type']) && is_numeric($data['pay_type']) && in_array($data['pay_type'],array_keys(Actives::$_pay_type)) )
			$this->_pay_type = $data['pay_type'];
		else
			return false;
		return true;
	}
	
	/**
	 * 当前活动是否可以编辑
	 * @param $id
	 */
	private function is_actives_edit($id)
	{
		$this->_class_model = 'Actives';
		$surplus = $this->loadModel($id,array(
			'with'=>'Actives_Shops',
			'condition'=>'`t`.`status`=:status AND (`Actives_Shops`.`audit` =:audit OR `Actives_Shops`.`audit`=:store_audit)',
			'params'=>array(':status'=>Actives::status_not_publish,':audit'=>Shops::audit_nopass,':store_audit'=>Shops::audit_store_nopass)
		));
		//活动不可以编辑
		if(!$surplus)
			$this->send_error(ACTIVES_NOT_EDIT_ERROR);

		return true;
	}
	
	/**
	 * shops 信息
	 * @return array
	 */
	public function shops(){
		$Shops = array(
				'name' => '创建线222'
		);
		return $Shops;
	}

	/**
	 * profare 信息
	 * @return array
	 */
	public function profare(){
		$ProFare =  array(
			1 => array(
				0 => array(
					39 => array	(
						0 => array(
							58 => array	(
								0 => 64
							)
						),
						1 => array(
							57 => array(
								0 => 63
							)
						)
					)
				),
				1 => array(
					33 => array	(
						0 => array(
							58 => array	(
								0 => 64
							)
						)
					)
				)
			),
			2 => array(
				0 => array(
					27 => array	(
						0 => array(
							42 => array	(
								0 => 44
							)
						)
					)
				),
				1 => array(
					26 => array	(
						0 => array(
							43 => array	(
								0 => 45
							)
						)
					)
				)
			),
			4 => array(
				0 => array(
					26 => array	(
						0 => array(
							43 => array	(
								0 => 45
							)
						)
					)
				)
			)
		);
		return $ProFare;
	}

	/**
	 * pro 信息
	 * @return array
	 */
	public function pro(){
		$Pro  = array(
			1 => array(
				0 => array(
					39 => array(
						0 => 58,
						1 => 57,
					)
				),
				1 => array(
					33 => array	(
						0 => 58
					)
				)
			),
			2 => array(
				0 => array(
					27 => array	(
						0 => 42
					)
				),
				1 => array(
					26 => array(
						0 => 43
					)
				)
			),
			4 => array(
				0 => array(
					26 => array(
						0 => 43
					)
				)
			)
		);
		return $Pro;
	}
}
