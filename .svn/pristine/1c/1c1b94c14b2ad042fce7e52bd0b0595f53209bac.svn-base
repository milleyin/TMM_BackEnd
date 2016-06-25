$(function() {
    // 获取地区
    getRegion('', '', '', 'address-one-tml');
    getRegion('', '', '', 'address-two-tml');
    function showMsg(msg) {
        layer.open({
            content: msg,
            style: 'background:rgba(40, 40, 40, 0.75); color:#fff; border:none;',
            time: 2
        });
    }

    $('.item').on('click', function() {
        var $this = $(this);
        var flag = $this.attr('data-item');
        $('.item').find('img').hide();
        $this.find('img').show();
        $('form').hide();
        $('#item-' + flag + '-form').show();
    });

    // 添加收货人
    $('body').on('touchstart', '#add-one', function() {
        var len = $('#address-one').find('.addr').length;
        if (len < 6) {
            var $addressOneTml = $('#address-one-tml');
            $addressOneTml.find('.receiver-name').html('收货人' + (len + 1));
            $('#address-one').append($addressOneTml.html());
        }
    });

    // 添加收货人
    $('body').on('touchstart', '#add-two', function() {
        var len = $('#address-two').find('.addr').length;
        if (len < 10) {
            var $addressOneTml = $('#address-two-tml');
            $addressOneTml.find('.receiver-name').html('收货人' + (len + 1));
            $('#address-two').append($addressOneTml.html());
        }
    });


    // 删除收货人
    $('body').on('touchstart', '.del-address', function() {
        $(this).closest('.addr').remove();
        updateSort();
    });

    $('#item-one-form').submit(function() {
        var objArr = $(this).serializeArray();
        var isOk = true;
        var totalNumber = 0;

        $.each(objArr, function(i, field){
            if ($.trim(field.value) == '') {
                isOk = false;
            }
            if (field.name == 'number[]') {
                totalNumber += parseInt(field.value);
            }

        });

        if (! isOk) {
            showMsg('收货信息填写不完整');
            return false;
        }

        if (totalNumber < 6) {
            showMsg('必须购满6箱');
            return false;
        }
        if (totalNumber > 6) {
            showMsg('超过限定购买的箱数6箱');
            return false;
        }
        return true;
    });

    $('#item-two-form').submit(function() {
        var objArr = $(this).serializeArray();
        var isOk = true;
        var totalNumber = 0;
        $.each(objArr, function(i, field){
            if ($.trim(field.value) == '') {
                isOk = false;
            }
            if (field.name == 'number[]') {
                totalNumber += parseInt(field.value);
            }

        });
        if (! isOk) {
            showMsg('收货信息填写不完整');
            return false;
        }

        if (totalNumber < 10) {
            showMsg('必须购满10箱');
            return false;
        }
        if (totalNumber > 10) {
            showMsg('超过限定购买的箱数10箱');
            return false;
        }
        return true;
    });

    // 更新收货人排序
    function updateSort() {
        var addrList = $('#address-one').find('.addr');
        if (addrList.length > 1) {
            addrList.each(function(index, ele) {
                $(ele).find('.receiver-name').html('收货人' + (index + 1));
            });
        }
        var addrTwoList = $('#address-two').find('.addr');
        if (addrTwoList.length > 1) {
            addrTwoList.each(function(index, ele) {
                $(ele).find('.receiver-name').html('收货人' + (index + 1));
            });
        }
    }

});