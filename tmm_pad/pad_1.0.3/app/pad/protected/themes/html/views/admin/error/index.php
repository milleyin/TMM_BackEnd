<?php
/* @var $this SiteController */
/* @var $error array */

if ($error['errorCode'] == 302) {
    $this->title = '正在跳转 -' . $this->pageTitle;
    $this->breadcrumbs=array(
        '提示',
    );
} else {
    $this->title = ' 错误页面 -' . $this->pageTitle;
    $this->breadcrumbs=array(
        '错误',
    );
}
    if ($error['errorCode'] == 302) {
?>
<h2>提示信息：</h2>
<h2>
    <div class="error">
        <?php echo CHtml::encode($error['message']); ?>
    </div>
</h2>
<form name=loading>
    <p>
        <font color="#0066ff" size="2">正在跳转，请稍等</font>
        <font color="#0066ff" size="2" face="Arial">...</font>
        <input type=text name=chart size=52 style="font-family:Arial; font-weight:bolder; color:#0066ff; background-color:#fef4d9; padding:0px; border-style:none;"> 
        <input type=text name=percent size=5 style="color:#0066ff; text-align:center; border-width:medium; border-style:none;"> 
    </p> 
</form>
<p> 如果您的浏览器不支持跳转，<a style="text-decoration: none" href="<?php echo $error['location'];?>"><font color="#FF0000">请点这里</font></a>.</p>
<script type="text/javascript">
/*<![CDATA[*/ 
    var bar = 0, line = "||", amount = "||";
    function count() {
        bar += 2;
        amount += line;
        document.loading.chart.value = amount;
        document.loading.percent.value = bar+"%";
        if (bar < 99) {
            setTimeout("count()", <?php echo $error['time'] *1000 / 50;?>);
        } else {
            window.location = "<?php echo $error['location'];?>";
        }
    }
    count();
/*]]>*/
</script>

<?php 
    } else {
?>

<h2>错误代码 <?php echo $error['errorCode'] == 0 ? $error['code'] : $error['errorCode']; ?></h2>

<div class="error">
    错误信息：<?php echo CHtml::encode($error['message']); ?>
</div>

<?php
    }
?>