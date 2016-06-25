<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title>奖品邮寄</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets() . '/css/mailingPrize.css';?>">
</head>
<body class="prizeMallingForm">
<form id="express-form" action="<?php echo Yii::app()->request->getUrl(); ?>" method="post">
    <div class="addr">
        <div class="row">
            <div class="tit">收货人</div>
            <input type="text" placeholder="收货人真实姓名" name="Express[name]">
        </div>
        <div class="row">
            <div class="tit">手机号</div>
            <input type="tel" placeholder="收货人手机号" name="Express[phone]">
        </div>
        <div class="row">
            <div class="tit">选择省份</div>
            <select id="province" name="Express[province]">
                <option value="" name="isDefault">请选择省</option>
            </select>
        </div>
        <div class="row">
            <div class="tit">选择城市</div>
            <select id="city" name="Express[city]">
                <option value="" name="isDefault">请选择市</option>
            </select>
        </div>
        <div class="row">
            <div class="tit">选择地区</div>
            <select id="district" name="Express[district]">
                <option value="" name="isDefault">请选择区/县</option>
            </select>
        </div>
        <div class="row rowarea">
            <div class="tit">详细地址</div>
            <textarea rows="2" class="detialAddr" placeholder="请详细到门牌号" name="Express[address]"></textarea>
        </div>
        <input type="hidden" name="<?php echo Yii::app()->request->csrfTokenName; ?>" value="<?php echo Yii::app()->request->csrfToken; ?>">
    </div>
    <a href="javascript:;" id="create" class="mailing-btn">确认收货地址</a>
</form>
<script src="<?php echo $this->getAssets() . '/js/jquery.min.js';?>"></script>
<script src="<?php echo $this->getAssets() . '/js/layermobile/layer.js';?>"></script>
<script>
    $(function() {
        get_area(0, 'province', '请选择省', '0');

        // 提示框
        function showMsg(title, msg) {
            title = title || '';
            msg = msg || '';
            layer.open({
                title: title,
                shadeClose: false,
                content: msg,
                style: 'background-color:#fff; color:#ea5336;border:none;border-radius:5px;',
                time: 2
            });
        }

        /**
         * 获取多级联动的地区
         */
        function get_area(id,next, nextTip,select_id){
            var url = "<?php echo $this->createAbsoluteUrl('express/getArea'); ?>" + '?pid=' + id;
            $.ajax({
                type : "GET",
                url  : url,
                error: function(request) {
                    alert("服务器繁忙, 请联系管理员!");
                    return;
                },
                success: function(v) {
                    v = "<option value='0'>" + nextTip + "</option>" + v;
                    $('#'+next).empty().html(v);
                    (select_id > 0) && $('#'+next).val(select_id);//默认选中
                }
            });
        }

        $('#province').on('change', function() {
            get_area($(this).val(), 'city', '请选择市', '0');
        });
        $('#city').on('change', function() {
            get_area($(this).val(), 'district', '请选择区/县', '0');
        });

        $('#create').on('click', function() {
            $.ajax({
                type : "POST",
                data : $('#express-form').serialize(),
                url  : "<?php echo Yii::app()->request->getUrl(); ?>",
                error: function(request) {
                    showMsg('', '服务器繁忙, 请稍候重试!');
                    return;
                },
                success: function(response) {
                   if (response.status == 1) {
                       window.location.href = response.data.url;
                   } else {
                       showMsg('', response.prompt);
                   }
                }
            });
        });
    });
</script>
</body>
