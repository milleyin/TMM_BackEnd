<?php
//状态码
define('STATUS_FAIL', 0);
define('STATUS_SUCCESS', 1);
define('STATUS_UNAUTHORIZED', 2);
define('STATUS_NOTLOGIN', 3);
define('STATUS_PENDING', 4);
define('STATUS_ENCRYPT_FAIL', 5);
define('STATUS_FALL_SYSTEM', 6);
define('STATUS_REPEAT', 99);

//正常返回码
define('SUCCESS', 0);

//系统相关错误码
define('NOT_ENOUGH_ARGS', 40001);
define('NOT_ALLOWED_FORMAT', 40002);
define('NOT_AUTHORIZED', 40003);
define('NOT_FOUND', 40004);
define('MODULE_NOT_ALLOWED', 40005);
define('METHOD_NOT_ALLOWED', 40006);
define('GET_APP_TOKEN_FAIL',40100);
define('APP_TOKEN_NOT_EXIST',40101);
define('SYSTEM_BUSY_NOT',40102);
define('APP_SMS_PUSH_OFTEN',40103);


define('SYSTEM_FROM_ERROR',40201);
define('SYSTEM_FROM_CSRF_ERROR',40202);
define('SYSTEM_CLASS_MODEL',40203);

//结伴游
define('GROUP_CURRENT_END_ERROR',40301);
define('GROUP_CURRENT_GROUP_TIME_ERROR',40302);
define('GROUP_NOT_ORGANIZER_ERROR',40303);

//活动=====创建
define('ACTIVES_NOT_ORGANIZER_ERROR',40401);
define('ACTIVES_NOT_EDIT_ERROR',40402);

//绑定银行卡相关
define('BIND_BANK_ORGANIZER_NOT',40501);

//数据库操作相关错误码
define('INSERT_FAIL', 50001);
define('UPDATE_FAIL', 50002);
define('DELETE_FAIL', 50003);
define('HANDLE_FAIL', 50004);
define('DATA_NOT_SCUSSECS', 50005);
define('DATA_NULL', 50006);

//登录相关
define('API_LOGIN_REQUIRED',60000);
define('STORE_LOGIN_REQUIRED',60001);

//订单支付相关
define('ACCOUNT_USER_ERROR',60100);
define('ALIPAY_USER_ERROR',60101);
define('WXPAY_USER_ERROR',60102);

