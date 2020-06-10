/**
 * 基于bui的弹窗，主要用于列表页面的弹出框
 * @param {[type]} opt [description]
 */
var Dialog = function(opt) {
    this.option = $.extend({
        title: '', //弹出框的title
        width: 500, //设置弹窗的宽
        height: 320, //设置弹窗的高
        beforeSubmit: function(){return true;}, //返回true表示可以提交，返回false表示不能提交
        ajaxSuccess: $.noop, //处理ajax成功，含有三个参数，this.dialog, this.form,this.$form
        ajaxFinish: $.noop,
        contentId: '', //弹窗对应html的内容ID
        beforeShow: $.noop, //含有两个参数，第一个参数是bui的dialog弹窗对象，第二个参数是显示弹窗的时候通过show这个方法传入的参数。主要是用来重试dialog的表单
        afterRenderUI: $.noop,
        alertSuccess: true,
        limitAjax: true
    }, opt || {});

    this.init();
};

$.extend(Dialog.prototype, {
    init: function() {
        this.getDialog();
    },

    getDialog: function() {
        var deferred = $.Deferred();
        if (this.dialog) {
            deferred.resolve(this.dialog);
        } else {
            this.createDialog(deferred);
        }
        return deferred.promise();
    },

    createDialog: function(deferred) {
        var self = this;
        BUI.use(['bui/overlay', 'bui/form'], function (Overlay, Form) {
            var dialog = new Overlay.Dialog({
                title: self.option.title,
                width: self.option.width,
                height: self.option.height,
                contentId: self.option.contentId,
                success: $.proxy(self.success_, self)
            });
            dialog.on('afterRenderUI', function(e) {
                self.$form = dialog.get('el').find('form');
                self.form = new Form.HForm({
                    srcNode: self.$form
                }).render();
                self.form.setInternal('initRecord', self.form.serializeToObject()); //设置初始值，之后才能调用reset(),否则会报错
                self.option.afterRenderUI(self);
                self.attachEvents();
            });
            dialog.on('closed', function(e) {
                self.form.reset();
                self.form.clearErrors();
            });
            self.dialog = dialog;
            deferred.resolve(dialog);
        });
    },

    attachEvents: function() {
        var self = this;
        this.$form.find('input').on('keyup', function (ev) {
            if(ev.keyCode == 13) {
                self.success_();
            }
        });
    },

    success_: function() {
        if(!(this.option.limitAjax && this.xhr_) && this.option.beforeSubmit(this.dialog, this.form, this.$form, this.outSideParams_) && this.form.valid(),this.form.isValid()) {
            this.xhr_ = $.ajax({
                url: this.$form.attr('action'),
                type: this.$form.attr('method'),
                dataType: 'json',
                data: this.$form.serialize(),
                context: this
            }).done(function(data) {
                if (data.success) {
                    if(this.option.alertSuccess) {
                        setTimeout(function() {
                            BUI.Message.Alert('操作成功', 'success');
                        });
                    }
                    this.option.ajaxSuccess(this.dialog, this.form, this.$form, this.outSideParams_);
                    this.close();
                } else {
                    setTimeout(function() {
                        BUI.Message.Alert(data.msg, 'error');
                    });
                }
            }).fail(function(data) {
                setTimeout(function() {
                    BUI.Message.Alert('网络错误', 'error');
                });
            }).always(function(e) {
                this.option.ajaxFinish(this);
                this.xhr_ = null;
            });
        }
    },

    //关闭弹窗
    close: function() {
        this.dialog.close();
    },

    /**
     * 弹窗显示方法
     * @param  {object} outSideParams 当弹窗的时候需要传入的数据。
     */
    show: function(outSideParams) {
        this.outSideParams_ = outSideParams;
        var self = this;
        this.getDialog().then(function(dialog) {
            dialog.show();
            self.option.beforeShow(dialog, outSideParams);
        });
    }
});

//module.exports = Dialog;