<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__COMMON__' => __ROOT__ . '/Public/common',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),
    'USER_BUY_TICKET' => array( // 用户创建订单
        'number'=>0, // 一天能使用短信发送几次 0不限制
        'time'=>300, // 短信时长
        'interval'=>60, // 间隔时长
        'content'=>'您正在购买荔枝节门票！验证码：{code}。【田觅觅】',//短信模板签名放在后面
        'error'=>5,//最多错误次数
    ),
    'USER_BUY_CARD' => array( // 用户创建订单
        'number'=>0, // 一天能使用短信发送几次 0不限制
        'time'=>300, // 短信时长
        'interval'=>60, // 间隔时长
        'content'=>'您正在购买桂味·大宗提货券！验证码：{code}。【田觅觅】',//短信模板签名放在后面
        'error'=>5,//最多错误次数
    ),
    'USER_NOTICE_ORDER' => array( // 用户通知购买订单
        'number'=>0, // 一天能使用短信发送几次 0不限制
        'time'=>300, // 短信时长
        'interval'=>60, // 间隔时长
        'content'=>'您已成功购买荔枝节{info}。如有疑问，欢迎致电客服 400-019-7090【田觅觅】',//短信模板签名放在后面
        'error'=>5,//最多错误次数
    ),
    'USER_NOTICE_LOGISTICS' => array( // 用户通知购买卡券邮寄物流
        'number'=>0, // 一天能使用短信发送几次 0不限制
        'time'=>300, // 短信时长
        'interval'=>60, // 间隔时长
        'content'=>'您购买的桂味·大宗提货券（{info}）已使用邮寄。如有疑问，欢迎致电客服 400-019-7090【田觅觅】',//短信模板签名放在后面
        'error'=>5,//最多错误次数
    ),
    'COMPRESS_IMG_CONFIG'=>array(
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
    'ACTIVITY_CONFIG'=>array(
        'end_time'=>'2016-07-03 23:59:59'
    ),
);