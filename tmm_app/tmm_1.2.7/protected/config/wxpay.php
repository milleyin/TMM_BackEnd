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
					'urlManager'=>array(
						'urlFormat'=>'path',
						'showScriptName'=>false,//注意false不要用引号括上
						'appendParams'=>false,
						//'urlSuffix'=>'.html',				//搭车加上.html后缀，霸道
			 			'rules'=>array(
			 				'<modules:(api)>/<controller:(callback|order)>/<action:\w+(wxpay|paywx)>'
			 						=>'api/<controller>/<action>',
						),
					),
			),
// 			'catchAllRequest'=>array(
// 					'api/callback/wxpay'  									//永久定向
// 			),
		)
);
