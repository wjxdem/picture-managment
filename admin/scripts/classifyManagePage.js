$.namespace('pg.admin.ClassifyManagePage');
var ClassifyManagePage = function() {
    this.firstTabClickFlag_ = false;
    this.tag_ = false;
    this.init();
}

$.extend(ClassifyManagePage.prototype, {
    init: function() {
        this.initTab();
        this.initLogolist();
        this.attachEvents();
    },
    initSubList: function() {
        this.subList_ = new pg.classify.SubController();
    },

    initLogolist: function() {
        if(!this.logoList_){
             this.logoList_ = new pg.classify.LogoController();
         }else{
            this.logoList_.refreshList();
         }    
    },

    initTopList: function() {
        this.topList_ = new pg.classify.TopController();
    },

    initTab: function() {
        var self = this;
        $('.m-tab-container').tabswitch({
            tabSelector: '.m-tab-title h3',
            contentSelector: '.m-tab-content',
            clickAction: function(nextTab, nextContent, nowTab, nowContent) {
                if (!self.firstTabClickFlag_) {
                    self.firstTabClickFlag_ = true;
                    self.initTopList();
                }else{
                    self.topList_.refreshList();
                }
                nextTab.addClass('current');
                nowTab.removeClass('current');
                nextContent.show();
                nowContent.hide();
            }
        });
    },
    attachEvents: function() {
        $('.js-tab-classify').on('click', $.proxy(this.handleTab, this));
        $('.js-add-logo-btn').on('click', $.proxy(this.handleLogoAdd, this));
        $('.js-add-top-btn').on('click', $.proxy(this.handleTopAdd, this));
        $('.js-add-sub-btn').on('click', $.proxy(this.handleSubAdd, this));
        $('.js-add-btn').on('click',$.proxy(this.handleAdd,this));
        $('.js-cancel-btn').on('click',$.proxy(this.handleCancel,this));
        $('.js-select-topClassify').on('change', $.proxy(this.handleSelect, this));
    },
    handleSelect: function(e) {//下拉框的值改变时，传参到页面隐藏域
        var val = $("#pid").val();
        $('#subForm').find('input[name="pid"]').val(val);
        this.subList_.refreshList();

    },
    handleCancel:function(e){
    	 var $this = $(e.currentTarget);
    	 var $addWrap = $this.parents('.js-add-wrap');
    	 $addWrap.hide();
    	 $addWrap.siblings('.js-add-btn').show();
    },
    handleAdd:function(e) {
        var $this = $(e.currentTarget)
        $this.hide();
        $this.siblings('.js-add-wrap').show();


    },
    handleLogoAdd: function() {
        var $name = $('#js-classify-name');
        var $error = $name.siblings('span');
        var name = $name.val();
        var url = window.globalConfig.api.addLogo;
        var data = {'cates': name }
        if (!$.trim(name)) {
            $error.html('不能为空!')
        } else {
            $error.html('');
            this.getAddAjax(url, data, this.logoList_);
        }
    },
    handleTopAdd: function() {
        var $name = $('#js-topClassify');
        var $error = $name.siblings('span');
        var name = $name.val();
        var url = window.globalConfig.api.addTop;
        var data = {'scate': name }
        if (!$.trim(name)) {
            $error.html('不能为空!')
        } else {
            $error.html('');
            this.getAddAjax(url, data, this.topList_);
        }
    },
    handleSubAdd: function() {
        var $topClassify = $('#pid');
        var $subClassify = $('#js-subClassify');
        var topClassifyVal = $topClassify.find("option:selected").val();
        var subClassifyVal = $subClassify.val();
        var $topClassifyError = $topClassify.siblings('span');
        var $subClassifyError = $subClassify.siblings('span');
        var url = window.globalConfig.api.addSub;
        var data = { 'pid': topClassifyVal, 'cates': subClassifyVal }
        if (topClassifyVal == '0' || !$.trim(subClassifyVal)) {
            if (topClassifyVal == '0') {
                $topClassifyError.html('不能为空!');
            } else {
                $topClassifyError.html('');
            }
            if (!$.trim(subClassifyVal)) {
                $subClassifyError.html('不能为空!')
            } else {
                $subClassifyError.html('');
            }

        }else{
             $topClassifyError.html('');
             $subClassifyError.html('');
             this.getAddAjax(url, data, this.subList_);
        }
       
    },
    getAddAjax: function(url, data, list) {
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: data
        }).done(function(rs) {
            if (rs.success) {
            	setTimeout(function() {
                    BUI.Message.Alert('添加成功', function() {
                        list.refreshList();
                    }, 'success');
                });
            } else {
            	setTimeout(function() {
                    BUI.Message.Alert(rs.msg, 'error');
                });
            }
        })
    },
    handleTab: function(e) {
        var $this = $(e.currentTarget);
        var index = $this.index();
        var $topContent = $('.m-top-content');
        var $subContent =$('.m-sub-content');
         $this.removeClass('button-cancel').addClass('button-info').siblings().addClass('button-cancel');
        if (index == '0') {
        	$topContent.show();
        	$subContent.hide();
        } else {
            if (!this.tag_) {
                this.initSubList();
                this.tag_ = true;
            }else{
                this.subList_.refreshList();
            }
            $topContent.hide();
            $subContent.show();

        }
    }

})
pg.admin.ClassifyManagePage = ClassifyManagePage;
