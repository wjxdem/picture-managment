$(document).ready(function(){
   var Menu = function() {
    this.init();
};

$.extend(Menu.prototype, {
    init: function() {
        this.$topNav = $('.top-nav');
        this.timer_ = null;
        this.attachEvents();
    },

    attachEvents: function() {
        var timer = null;
         this.$topNav.on('click','li',$.proxy(this.handleClick, this));
        //头部个人信息切换
        $(".pg-nav-pop").hover(function() {
            $(".pop-hd").show();
        }, function() {
            $(".pop-hd").hide();
        });
    },
   
    handleClick:function(e) {
        $(e.currentTarget).addClass('current').siblings('li').removeClass('current');;
    }
 
});

var Header = function() {
    new Menu();
};
new Header();
	
})
//jQuery 扩展
$.namespace = function(names) {
    names = names.split('.');
    var currentSpace = window;
    for (var i = 0, len = names.length - 1; i < len; i++) {
        var name = names[i];
        if (currentSpace[name]) {
            currentSpace = currentSpace[name];
        } else {
            currentSpace = currentSpace[name] = {};
        }
    }
};

$.inherit = function(subClass, superClass) {
    var temp = function() {};
    temp.prototype = superClass.prototype;
    var subPro = new temp();
    subPro.constructor = subClass;
    subPro.superClass_ = superClass.prototype;
    subClass.prototype = subPro;
};
