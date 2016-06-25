/* 工具类库 */
!(function() {
    var Utils = {
        showToast: function(msg) {
            var template = '<div id="toast">'
                         + '<div class="weui_mask_transparent"></div>'
                         + ''
                + '<div class="weui_toast" style="width:80%;height:4.6em;min-height:4.6em;line-height: 4.6em;font-size: 12px;margin-left:-40%;">'
                + msg
                + '</div>'
                + '</div>';

            var $template = $(template);
            $('body').append($template);

            setTimeout(function() {
                ($template).remove();
            }, 1000);

        },
        showTitleToast: function(title, msg) {
            var template = '<div id="toast">'
                    + '<div class="weui_toast" style="width:80%;height:4.6em;min-height:4.6em;line-height: 1.6em;font-size: 12px;margin-left:-40%;">'
                    + '<p style="padding: 5px 0;color: #66bb2d;">'+title+'</p>'
                    + '<p class="weui_toast_content">'+msg+'</p>'
                    + '</div>';
            var $template = $(template);
            $('body').append($template);

            setTimeout(function() {
                ($template).remove();
            }, 1000);

        },
        checkMobile: function(tel) {
            if (!tel.match(/^1[34578][0-9]{9}$/)) {
                return false;
            }
            return true;
        }
    };
    window.Utils = Utils;
})();