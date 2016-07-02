<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title>兑换奖品</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets() . '/js/swiper/css/swiper-3.3.1.min.css';?>">
    <link rel="stylesheet" type="text/css" href=" <?php echo $this->getAssets() . '/css/exchangePrize.css';?>">
</head>
</head>

<body class="exchange-prize-bg">
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($model as $record) {?>
            <div class="swiper-slide">
                <div class="exchangePrize">
                    <p class="title"><?php echo CHtml::encode($record->prize_name); ?></p>
                    <img src="<?php echo $record->Record_Upload->getUrlPath();  ?>" class="volumeImg">
                    <div class="code">
                        兑奖码：<?php echo CHtml::encode($record->code);  ?>
                    </div>
                    <a href="javascript:;" data-rid="<?php echo CHtml::encode($record->id);  ?>" class="exchange">确认兑换</a>
                    <p class="prize-title">奖品概述</p>
                    <p class="txt">
                        <?php echo str_replace(array("\r\n","\n","\r"), '<br/>', CHtml::encode($record->prize_info)); ?>
                    </p>
                </div>
            </div>
        <?php }?>
    </div>
</div>
<?php if (empty($model)) { ?>
    <div class="exchange-fail">亲，你暂时没中奖，祝你下次好运</div>
<?php } ?>
<script src="<?php echo $this->getAssets() . '/js/jquery.min.js'; ?>"></script>
<script src="<?php echo $this->getAssets() . '/js/layermobile/layer.js';?>"></script>
<script src="<?php echo $this->getAssets() . '/js/swiper/js/swiper-3.3.1.jquery.min.js'; ?>"></script>
<script>
    $(function() {
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            paginationClickable: true,
            centeredSlides: true,
            spaceBetween: 15
        });

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
        $('.exchange').on('click', function() {
            var $this = $(this);
            var dataObj = $this.data();
            $.ajax({
                type : "GET",
                url  : "<?php echo $this->createAbsoluteUrl('record/exchange'); ?>" + "?id=" + dataObj.rid,
                error: function(request) {
                    $this.removeAttr('disable');
                    showMsg('', '服务器繁忙, 请稍候重试!');
                    return;
                },
                success: function(response) {
                    showMsg('', response.prompt);
                    if (response.status == 1) {
                        $this.replaceWith('<a href="javascript:;" class="exchange exchangeSuc">已兑换</a>');
                    }
                }
            });
        });

    });

</script>
</body>

</html>
