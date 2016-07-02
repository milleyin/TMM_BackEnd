<?php 
return CMap::mergeArray(
		require(dirname(__FILE__).'/main.php'),
		array(
			'components'=>array(
					'request'=>array(
							'class'=>'HttpRequest',
							'csrfTokenName'=>'TMM_CSRF',		//令牌验证的名字
							'enableCookieValidation'=>false, 		//Cookie
							'enableCsrfValidation'=>false,  			//post 验证
					),
			),
			//'defaultController'=>'api/callback/alipay', 		//默认模块方法 
			'catchAllRequest'=>array(
					'api/callback/alipay'  									//永久定向
			),
		)
);
