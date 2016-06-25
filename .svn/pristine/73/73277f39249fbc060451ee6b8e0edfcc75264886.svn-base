<?php
return array(
	//'配置项'=>'配置值'
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),
    /*短信接口配置*/
    'SMS' => array(
        'sms_key'=>'f1a763ef51b3828d7acadda7d5353a69',
        'sms_url'=>'http://sms-api.luosimao.com/v1/send.json',
    ),
    'USER_CREATE_ORDER' => array( // 用户创建订单
        'number'=>0, // 一天能使用短信发送几次 0不限制
        'time'=>300, // 短信时长
        'interval'=>60, // 间隔时长
        'content'=>'您正在预订门票，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
        'error'=>5,//最多错误次数
    ),
    'USER_QUERY_ORDER' => array( // 用户查询订单
        'number'=>0, // 一天能使用短信发送几次 0不限制
        'time'=>300, // 短信时长
        'interval'=>60, // 间隔时长
        'content'=>'您正在查询预订的门票信息，请谨慎操作！验证码：{code}。【田觅觅】',//短信模板签名放在后面
        'error'=>5,//最多错误次数
    ),
    'USER_NOTICE_ORDER' => array( // 用户通知购买订单
        'number'=>0, // 一天能使用短信发送几次 0不限制
        'time'=>300, // 短信时长
        'interval'=>60, // 间隔时长
        'content'=>'您已成功购买青白江樱花节{info}。请保留好二维码，到售票处换取纸质门票后入园。如有疑问，欢迎致电客服 400-019-7090【田觅觅】',//短信模板签名放在后面
        'error'=>5,//最多错误次数
    ),
    'YH_NOTICE_PHONE' => array( // 短信通知号码
        'phone_one'=>'13922831369', // 通知号码1
        'phone_two'=>'13927496704', // 通知号码2
        'phone_three'=>'18682074705', // 通知号码3
        'YJ'=>'13922831369', // 杰哥
        'ZM'=>'13927496704', // 章铭
        'MSH'=>'18127005181', // Moore
        'MXS'=>'18682010536', // 马哥
        'CXC'=>'18682074705' // 蔡晓畅
    ),
    /* 二维码图片上传相关配置 */
    'CODE_UPLOAD' => array(
        'rootPath' => './Uploads/Code/', //保存根路径
    ),
    /* 图片上传配置 */
    'PICTURE_UPLOAD_DRIVER' => 'Local',
    // 本地上传文件驱动配置
    'UPLOAD_LOCAL_CONFIG' => array (),
    // 图片上传相关配置
    'PICTURE_UPLOAD' => array (
        'maxSize' => 0, // 2M 上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png,jpeg', // 允许上传的文件后缀
        'rootPath' => './Uploads/Picture/'
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
        'end_time'=>'2016-04-10 23:59:59'
    ),
);