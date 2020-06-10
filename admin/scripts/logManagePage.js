LogManagelist = function() {
        wsfeList.apply(this, arguments);
    },
    $.inherit(LogManagelist, wsfeList);

$.extend(LogManagelist.prototype, {
    gridConfig: function() {
        var self = this;
        return {
            bbar: {
                pagingBar: false
            }
        };
    },
    columns: function() {
        return [{
            title: "id",
            dataIndex: 'id',
            visible: false,

        }, {
            title: '创建日期',
            elCls: 'center',
            dataIndex: 'ptime',
            width: '40%',
        }, {
            title: '日志内容',
            dataIndex: 'pdesc',
            elCls: 'center',
            width: '55%'
        }, {
            title: '操作',
            elCls: 'center',
            renderer: function(val) {
                var str = '<div class="gird-handle-icon">' + '<a href="javascript:;" class="wsicon wsicon-edit js-edit-btn" title="编辑"></a>' + '<a href="javascript:;" class="wsicon wsicon-delete js-delete-btn" title="删除"></a>' + '</div>';
                return str;

            }
        }];
    },
})

$.namespace('pg.admin.LogManagePage');
var LogManagePage = function() {
    wsfeListController.apply(this, [new LogManagelist()]);
    this.form_ = null;
    this.dialog_ = null;
    this.init();
}
$.inherit(LogManagePage, wsfeListController)

$.extend(LogManagePage.prototype, {
    init: function() {
        this.superClass_.init.call(this);
        this.initDialog();
    },
    initDialog: function() {
        this.dialog = new Dialog({
            title: '编辑分类',
            height: 'auto',
            contentId: 'js-dialog-content',
            beforeShow: $.proxy(this.handleBeforeShow, this),
            ajaxSuccess: $.proxy(this.refreshList, this)
        })
    },
    attachEvents: function() {
        this.superClass_.attachEvents.call(this);
        $('.js-add-btn').on('click', $.proxy(this.handleAddBtn, this));
        $('.js-add-log').on('click',$.proxy(this.handleAddLog,this));
        $('.js-cancel-btn').on('click',$.proxy(this.handleCancel,this));
    },
    handleAddLog:function(e) {
        $(e.currentTarget).hide();
        $('.js-add-wrap').show();

    },
    handleCancel:function(e){
   	 var $this = $(e.currentTarget);
   	 var $addWrap = $this.parents('.js-add-wrap');
   	 $addWrap.hide();
   	 $addWrap.siblings('.js-add-log').show();
   },
    handleBeforeShow: function(dialog, outSideParams) {
    	console.log(dialog);
        var $form = dialog.get('el').find('form');
        $form.find('input[name="id"]').val(outSideParams.id);
        $form.find('.ptime').html(outSideParams.ptime);
        $form.find('textarea[name="pdesc"]').val(outSideParams.pdesc);   
    },

    handleAddBtn: function() {
        var self = this;
        var $content = $('#js-content');
        var content=$content.val();
        if (!$.trim(content)) {
            $('#js-error').html('不能为空!');
            
        } else {
            $('#js-error').html('');
            $.ajax({
                url: window.globalConfig.api.addlog,
                type: 'post',
                dataType: 'json',
                data: { 'pdesc': content }
            }).done(function(data) {
                if (data.success) {
                	setTimeout(function() {
                        BUI.Message.Alert('操作成功', function() {
                            self.refreshList();
                            $content.val('');
                        }, 'success');
                    });
                } else {
                	 setTimeout(function() {
                         BUI.Message.Alert(data.msg||'fail', 'error');
                     });
                }
            })
        }
    },
    handleEdit: function(record) {
        this.dialog.show(record);
    },

    handleDelete: function(record) {
        var self = this;
        var data = { 'id': record.id };
        if (record.id) {
            BUI.Message.Confirm('确定将删掉该图片信息？', function() {
                $.ajax({
                    url: window.globalConfig.api.dellog,
                    dataType: 'json',
                    data: data,
                    type: 'post'
                }).done(function(data) {
                    if (data.success) {
                        setTimeout(function() {
                            BUI.Message.Alert('操作成功', function() {
                                self.refreshList();
                            }, 'success');
                        });
                    } else {
                        setTimeout(function() {
                            BUI.Message.Alert(data.msg, 'error');
                        });
                    }
                });
            }, 'question');
        }
    }
})
pg.admin.LogManagePage = LogManagePage;
