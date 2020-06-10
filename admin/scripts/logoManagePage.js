LogoManagelist = function() {
    wsfeList.apply(this, arguments);
},
$.inherit(LogoManagelist, wsfeList);

$.extend(LogoManagelist.prototype, {
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
            title: "pid",
            dataIndex: 'pid',
            visible: false,

        },{
            title: "cid",
            dataIndex: 'id',
            visible: false,

        },{
            title: '缩略图',
            elCls: 'center',
            dataIndex: 'ppath',
            width: '25%',
            renderer: function(val) {
                return '<img src="' + val + '"/>'
            }
        }, {
            title: '命名',
            dataIndex: 'pname',
            elCls: 'center',
            width: '20%'
        }, {
            title: '分类',
            dataIndex: 'cname',
            elCls: 'center',
            width: '25%'
        }, {
            title: '上传时间',
            dataIndex: 'pubtime',
            elCls: 'center',
            width: '25%'
        }, {
            title: '操作',
            elCls: 'center',
            renderer: function(val) {
            	var str = '<div class="gird-handle-icon">' + '<a href="javascript:;" class="wsicon wsicon-delete js-delete-btn" title="删除"></a>' + '</div>';
                return str;
            }
        }];
    },
})

$.namespace('pg.admin.LogoManagePage');
var LogoManagePage = function() {
    wsfeListController.apply(this, [new LogoManagelist()]);
    this.form_ = null;
    this.init();

}
$.inherit(LogoManagePage, wsfeListController)

$.extend(LogoManagePage.prototype, {
    attachEvents:function() {
        this.superClass_.attachEvents.call(this);
        $('.js-add-uploader').on('click',$.proxy(this.handleAddUploder,this));
        $('.js-cancel-btn').on('click',$.proxy(this.handleCancelBtn,this));
        $('.js-select-cate').on('change', $.proxy(this.handleSelect, this));
    },
    handleSelect: function(e) {//下拉框的值改变时，传参到页面隐藏域
        var val = $("#cid").val();
        $('#search-form').find('input[name="cid"]').val(val);
        this.refreshList();

    },
    handleCancelBtn:function() {
        $('.js-add-uploader').show();
        $('.js-add-wrap').hide();
    },
    handleAddUploder:function(e) {
        $(e.currentTarget).hide();
        $('.js-add-wrap').show();
        if(!this.uploader_){
         this.uploader_ =  new Uploader(this);
        }
    },
    handleDelete: function(record) {//页面删除事件处理
        var self = this;
        var data = { 'id': record.id ,
        		'pid': record.pid,
        		'cid': record.cid
        		};
        console.log(data);
        if (record.id) {
            BUI.Message.Confirm('确定将删掉该图片信息？', function() {
                $.ajax({
                    url: window.globalConfig.api.deleteImg,
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
pg.admin.LogoManagePage = LogoManagePage;
