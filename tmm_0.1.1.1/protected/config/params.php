<?php

// using Yii::app()->params['paramName']
return array_merge(array(
		'adminEmail'=>'761324952@qq.com',
		'tmm_400'=>'400-019-7090',
		//ajax 访问
		'admin_srbac_ajax'=>'TMM_ADMIN_RBAC',
		//接口域名
	    'app_api_domain'=>'https://m.365tmm.com',
		//图片链接
		'admin_img_domain'=>'http://img.365tmm.com',
		//订单位数单号
		'order_no_default'=>5,
		//组织者单号
		'order_organizer_no_default'=>5,
		//资金流水单号
		'account_log_no_default'=>10,
		
	    'ckeditor_upload_img_url'=>'ckeditorupload',

		//后台显示页的数量
		'admin_pageSize'=>10,
		//api(用户)分页设置
		'api_pageSize'=>array(
			'ts_shops'=>10,							//探索商品
			'store_items'=>10,						//商家项目
			'retinue'=>10,								//随行人员
			'shop_list'=>10,							//商品列表
			'order_user_list'=>10,					//用户订购订单列表
			'user_account_cash_list'=>20,	//用户账单提现记录列表
			'actives'=>10,								//代理商发起的活动 列表
			'actives_order'=>10,					//代理商活动报名列表
			'account_log'=>20,						//资金流水分页
			'account_count'=>20,					//资金统计分页数量
			'deposit_log'=>20,						//保证金日志
			'attend'=>20,								//报名人列表
		),
		//api 错误返回 程序错误 json 
		'api_error_json'=>true,
		//api 错误返回 程序错误 json
		'store_error_json'=>true,
		
		//shops搜索的定级分类数量
		'search_tags_type_shops'=>5,

	  //创建结伴游 过期时间天数
		'group_end_time'=>10,

		 //订单提现额度====代理商
		'order_deposit_price_agent'=>100,
		//订单提现额度====商家
		'order_deposit_price_store'=>100,
		//订单提现额度====用户
		'order_deposit_price_user'=>100,

		'tags'=>array(
			'store'=>array(
				'select'=>5,
				'error'=>'选择标签不能超过5个',
			),		
			'user'=>array(
					'select'=>5,
					'error'=>'选择标签不能超过5个',
			),
			'items'=>array(
					'select'=>5,
					'error'=>'选择标签不能超过5个',
			),
			'shops'=>array(//继承上级的（随机取）
					'select'=>5,
					'error'=>'选择标签不能超过5个',
			),
		),
		'litimg_pc'=>'pc',					//电脑端
		'litimg_app'=>'app',			//手机app
		'litimg_share'=>'share',		//分享
		//缩略图地址后缀
		'litimg'=>array('pc'=>'litimg_pc/','app'=>'litimg_app/','share'=>'litimg_share/'),
        'litimg_confing'=>array(
            'pc'=>array(
            		'height'=>'150',			//高
            		'with'=>'150',				//宽
            		'quality'=>100,			//质量
            		'compression'=>6,		//压缩
            		'mode'=>4
            ),
            'app'=>array(
            	'height'=>'504',
            	'with'=>'612',
            	'quality'=>60,
            	'compression'=>6,
            	'mode'=>4
            ),
            'share'=>array(
            	'height'=>'140',
            	'with'=>'140',
            	'quality'=>60,
            	'compression'=>6,
            	'mode'=>4
            ),
        ),
		//上传组织者的图片
		'uploads_organizer_image'=>'./uploads/role/organizer/',
		//上传用户的图片
		'uploads_user_image'=>'./uploads/role/user/',
		//上传商家的图片
		'uploads_store_image'=>'./uploads/role/store/',
		//上传代理商的图片
		'uploads_agent_image'=>'./uploads/role/agent/',
		
		//上传项目中地图图片
		'uploads_items_map'=>'./uploads/items/map/',
         //上传项目中酒店服务图片
        'uploads_items_wifi'=>'./uploads/items/hotel/wifi/', 
		//上传项目中 吃 图片
		'uploads_items_eat'=>'./uploads/items/eat/',
		//上传项目中 住 图片
		'uploads_items_hotel'=>'./uploads/items/hotel/',
		//上传项目中 玩 图片
		'uploads_items_play'=>'./uploads/items/play/',
		//上传项目中 点中附加项目 农产品 图片
		'uploads_items_farm_outer'=>'./uploads/items/farm/outer/',	
		
		//上传项目中 吃 图片缓存目录
		'uploads_items_tmp_eat'=>'./uploads/items/tmp/eat/',
		//上传项目中 住 图片缓存目录
		'uploads_items_tmp_hotel'=>'./uploads/items/tmp/hotel/',
		//上传项目中 玩 图片缓存目录
		'uploads_items_tmp_play'=>'./uploads/items/tmp/play/',
	
		//上传线路中 点 详情图片目录
		'uploads_shops_dot'=>'./uploads/shops/dot/',
		//上传线路中 线 详情图片目录
		'uploads_shops_thrand'=>'./uploads/shops/thrand/',
		//上传线路中 结伴游 详情图片目录
		'uploads_shops_group'=>'./uploads/shops/group/',
		//上传线路中 活动 详情图片目录
		'uploads_shops_actives'=>'./uploads/shops/actives/',
		//软件更新上传
		'uploads_software'=>'./uploads/software/',
		//广告图片地址
		'uploads_ad'=>'./uploads/ad/',
		
		
		//项目中价格添加数量 默认一
		'items_fare_number'=>6,
		//项目中图片添加数量 默认一
		'items_image_number'=>5,
		//一个点最多可添加10个项目
		'shops_dot_items_number'=>10,		
		//一条线最多可添加10天的日程
		'shops_thrand_day_number'=>10,
		//一条线的一天最多可添加5个点
		'shops_thrand_one_day_dot_number'=>5,
		
		/******************************************************/
		//不需要相对 编辑器上传的
		'uploads_editor'=>'/uploads/editor/',
		//上传项目中 吃 详情图片目录
		'uploads_items_editor_eat'=>'/uploads/editor/eat/',
		//上传项目中 住 详情图片目录
		'uploads_items_editor_hotel'=>'/uploads/editor/hotel/',
		//上传项目中 玩 详情图片目录
		'uploads_items_editor_play'=>'/uploads/editor/play/',
		/******************************************************/
		
		//不记录返回的URL
		'admin_not_back_url'=>array(
			'admin/tmm_hotel/uploads',
			'admin/tmm_eat/uploads',
			'admin/tmm_play/uploads',
			'admin/tmm_home/index',
        ),
        //不记录返回的URL
        'agent_not_back_url'=>array(

        ),

		//验证 正则表达
		'pattern'=>array(
			'phone'=>'/^1[34578][0-9]{9}$/'
		),
		//csrf 白名单
		'csrf_allowed'=>array(
			'/admin/tmm_hotel/uploads',
			'/admin/tmm_eat/uploads',
			'/admin/tmm_play/uploads',
			//'/api',
			//'/store',
			'/srbac',
			'/api/callback/alipay'
		),
		//post=>put 获取方式
		'post_put'=>array(
			'/api',
			'/store',
		),
		//是否限制 post_put ajax
		'post_put_ajax'=>false,
		// csrf 错误 返回 json
		'csrf_error_json'=>array(
			'/api',
			'/store',
		),
		//登录必须的 跳转页面
		'API_LOGIN_REQUIRED'=>array('/api/login/index'),
		//登录必须的 跳转页面
		'STORE_LOGIN_REQUIRED'=>array('/store/store_login/index'),

		//错误次数
		'user_login_error'=>5,
		'agent_login_error'=>5,
		'store_login_error'=>5,

		//短信设置
    	'sms'=>array(
    		'sms_key'=>'f1a763ef51b3828d7acadda7d5353a69',
    		'sms_url'=>'http://sms-api.luosimao.com/v1/send.json',
			'agent_login'=>array(//代理商登录
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'登录验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'agent_create_store'=>array(//代理商创建商家账号
					'number'=>0,//一天能使用短信发送几次 0不限制
					'time'=>300,//短信时长
					'interval'=>60,//间隔时长
					'content'=>'代理商为您创建账号验证码：{code}。【田觅觅】',//短信模板签名放在后面
					'error'=>3,//最多错误次数
			),
			'agent_create_store_son'=>array(//代理商创建商家子账号
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'代理商为您创建账号验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'agent_update_store_son'=>array(//代理商创建商家账号
					'number'=>0,//一天能使用短信发送几次 0不限制
					'time'=>300,//短信时长
					'interval'=>60,//间隔时长
					'content'=>'代理商为您修改账号验证码：{code}。【田觅觅】',//短信模板签名放在后面
					'error'=>3,//最多错误次数
			),
			'agent_update_old_phone'=>array(//代理商更新手机号(旧手机号)
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您正在修改登录账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'agent_update_new_phone'=>array(//代理商新的手机号
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您的新登录账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'agent_update_bank_phone'=>array(//代理商绑定银行卡
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您的正在绑定银行账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'agent_update_pwd_phone'=>array(//代理商修改密码
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您的正在修改账号密码，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'agent_cash_phone'=>array(//代理商提现
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您正在进行提现，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'api_login'=>array(//APP用户登录
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'登录验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'user_update_pwd_phone'=>array(//用户修改密码
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您的正在修改账号密码，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'user_update_old_phone'=>array(//用户更新手机号(旧手机号)
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您正在修改登录账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'user_update_new_phone'=>array(//用户新的手机号
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您的新登录账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'user_update_bank_phone'=>array(//用户绑定银行卡
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>0,//间隔时长
				'content'=>'您正在绑定银行账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'user_cash_phone'=>array(//用户提现
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您正在进行提现，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'user_password_phone'=>array(//用户提现
					'number'=>0,//一天能使用短信发送几次 0不限制
					'time'=>300,//短信时长
					'interval'=>60,//间隔时长
					'content'=>'您正在设置{name}，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
					'error'=>3,//最多错误次数
			),
			'store_login'=>array(//APP用户登录
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'登录验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'store_update_pwd_phone'=>array(//商家修改密码
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您的正在修改账号密码，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'store_update_old_phone'=>array(//商家更新手机号(旧手机号)
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您正在修改登录账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'store_update_new_phone'=>array(//商家新的手机号
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您的新登录账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'store_update_bank_phone'=>array(//商家绑定银行卡
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您正在绑定银行账号，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'store_cash_phone'=>array(//商家提现
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>300,//短信时长
				'interval'=>60,//间隔时长
				'content'=>'您正在进行提现，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
			'user_notify_sms_phone'=>array(//用户支付成功回调发送短信
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>0,//短信时长	不需要
				'interval'=>0,//间隔时长
				'content'=>'您的订单{order_no}已支付成功，请以{time}出游，客服电话：{server}。【田觅觅】',//短信模板签名放在后面
				'error'=>0,//最多错误次数 不需要
			),
			'order_store_notify'=>array(//用户下单成功（自助游）发送短信
				'off'=>false,//是否关闭短信通知
				'number'=>0,//一天能使用短信发送几次 0不限制
				'time'=>0,//短信时长	不需要
				'interval'=>0,//间隔时长
				'content'=>'您有一条未处理的订单 {order_no}，请立即前往处理，客服电话：{server}。【田觅觅】',//短信模板签名放在后面
				'error'=>0,//最多错误次数 不需要
			),
			'use_attend'=>array(	//用户活动报名验证
				'number'=>0,			//一天能使用短信发送几次 0不限制
				'time'=>300,			//短信时长
				'interval'=>60,			//间隔时长
				'content'=>'您正在报名参加觅趣活动！验证码：{code}。【田觅觅】',//短信模板签名放在后面
				'error'=>3,//最多错误次数
			),
		),
  	
		//支付类型
		'pay_type'=>array(
			'alipay'=>array(
				'name'=>'支付宝',
				'value'=>1,
			),
		),
		//支付类型值
		'pay_type_value'=>array(
			1=>'alipay',//支付值 方法
			
		),
		//支付详情
		'order_pay_type'=>array(
			'alipay'=>array(
				'fee'=>'0.03',// % 支付宝支付费
				'domain'=>'http://m.365tmm.com', //回掉域名
				'returnUrl'=>'/api/callback/alipay',//回调链接
			),
		),
		'order_limit'=>array(
			'dot'=>array(
				'retinue_count'=>100, 	//随行人员的限制
				'items_count'=>100,	//下单项目的数量限制
			),
			'thrand'=>array(
				'retinue_count'=>100,
				'number'=>100,			//成人数量
			),
			'actives_tour'=>array(
				'retinue_count'=>100,
				'number'=>100,			//成人数量
				'set_go_time'=>7,//天 旅游活动 审核通过后 给出出游时间 最后的限制 
				'max_go_time'=>7,//天 旅游活动 最多设置出游时间的最多在多少天 end+max_go_time
				'min_interval_time'=>1,//天，开始和结束时间  最小的间隔时间
			),
		),
		
		//定时任务 超时
		'command_time'=>array(
			'order'=>array(
				'MQ'=>12,//小时	点 线 的订单支付超时时间
				'MQ_Store'=>12,// 小时 觅竟 （点 线）觅趣接单超时
				'MQ_User'=>12,//小时 觅趣订单：用户超过X小時未付款
				'MQ_dot'=>7,	//天  点订单  超时时间
				'MQ_thrand'=>7,//天  线订单  超时时间
				'MQ_actives'=>7,//天  觅趣订单(活动) 超时时间
				'MQ_appeal'=>7,//天  申诉按钮，超过7天后申诉按钮消失
			),
			'actives'=>array(
				'end_time'=>7,//天  到了活动报名结束时间后，超过7天未给出游日期，活动状态为已取消；
			),
		),
		//高德地图的key
		//web key
		'amap_web_key'=>'dfcde3745914da0d8fac310f4641ebd9',
		//javascript key
		'amap_javascript_key'=>'3187e3c3e007edbbe926a8cbcadab1fb',
		//用户设置当年城市县 信息 cookie 的名字
		'orientation'=>'orientation',
		//用户GPR定位的信息 cookie 的名字
		'gps'=>'gps',
),require(dirname(__FILE__).'/localhost.php'));