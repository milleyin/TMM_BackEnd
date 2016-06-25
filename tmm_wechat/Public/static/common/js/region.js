//加载地区（regionId地区父ID、elementId下拉框元素ID、selectedId选中的地区ID）
function loadRegion(regionId, elementId, selectedId, $parentEle) {
    var option = '';

    if (/^[0-9]*$/.test(regionId)) {
        console.log(regionId);
        $.ajax({
            type: 'GET',
            url: _URL_ + '/getRegion',
            async: false,
            data: 'fromType=1&id=' + regionId,
            dataType: 'json',
            success: function(json) {
                $.each(json, function(i, n) {
                    if (selectedId == json[i].id) {
                        option += '<option value="' + json[i].id + '" selected="selected">' + json[i].name + '</option>';
                    } else {
                        option += '<option value="' + json[i].id + '">' + json[i].name + '</option>';
                    }
                });
            }
        });
    }

    if ($parentEle) {
        $parentEle.find('.' + elementId).empty().append('<option value="">请选择</option>' + option);
    } else {
        $('.' + elementId).empty().append('<option value="">请选择</option>' + option);
    }
}
//获取地区（province省ID、city市ID、area区ID）
function getRegion(province, city, area, parentEle) {
    //初始化
    if (province == undefined && city == undefined && area == undefined) {
        //初始化省
        loadRegion(0, 'province');
    } else {
        var $parentEle = $('#' + parentEle);
        //初始化省
        loadRegion(0, 'province', province, $parentEle);
        //初始化市
        loadRegion(province, 'city', city, $parentEle);
        //初始化区
        loadRegion(city, 'area', area, $parentEle);
    }
}
//加载市
$('body').on('change', '.province', function() {
    var $parentEle = $(this).closest('.addr');
    loadRegion($(this).find('option:selected').val(), 'city', '', $parentEle);
});
//加载区
$('body').on('change', '.city', function() {
    var $parentEle = $(this).closest('.addr');
    loadRegion($(this).find('option:selected').val(), 'area', '', $parentEle);
});
