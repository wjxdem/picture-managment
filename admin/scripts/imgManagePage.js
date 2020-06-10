ImgManagelist = function() {
        wsfeList.apply(this, arguments);
    },
    $.inherit(ImgManagelist, wsfeList);

$.extend(ImgManagelist.prototype, {
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
            title: '一级分类',
            dataIndex: 'sname',
            elCls: 'center',
            width: '25%'
        }, {
            title: '二级分类',
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

$.namespace('pg.admin.ImgManagePage');
var ImgManagePage = function() {
    wsfeListController.apply(this, [new ImgManagelist()]);
    this.form_ = null;
    this.init();

}
$.inherit(ImgManagePage, wsfeListController)

$.extend(ImgManagePage.prototype, {
    attachEvents: function() {
        this.superClass_.attachEvents.call(this);
        $('.js-add-uploader').on('click', $.proxy(this.handleAddUploder, this));
        $('.js-cancel-btn').on('click', $.proxy(this.handleCancelBtn, this));
        $('.js-select-scate').on('change', $.proxy(this.handleTopSelect,this));
        $(".js-select-cate").on('change',$.proxy(this.handleSubSelect,this));
    },
    handleTopSelect: function() {//ajax获取二级下拉选择框
        var str = '<option value="0">--请选择二级分类--</option>';
        var val = $('.js-select-scate').find('option:selected').val();
        var $cid = $("#cid");
        $.ajax({
            type: "post",
            url: window.globalConfig.api.getSubClassify,
            data: { 'id': val },
            dataType: 'json'
        }).done(function(ret) {
            if (ret.success) {
            	console.log(ret.data.rows);
                $cid.html('');
                $.each(ret.data.rows, function(index, val) {
                    str += '<option value="' + val.id + '">' + val.cname + '</option><br/>'
                })
                $(str).appendTo($cid);
            } else {
                BUI.Message.Alert(ret.msg || 'fail')
            }
        });
    },
    handleSubSelect:function() {//下拉框的值改变时，传参到页面隐藏域
        var $searchForm = $('#search-form');
        var topSelectVal = $('#pid').val();
        var subSelectVal = $("#cid").val();
        $searchForm .find('input[name="pid"]').val(topSelectVal);
        $searchForm .find('input[name="cid"]').val(subSelectVal);
        this.refreshList();

    },
    handleCancelBtn: function() {
        $('.js-add-uploader').show();
        $('.js-add-wrap').hide();
    },
    handleAddUploder: function(e) {
        $(e.currentTarget).hide();
        $('.js-add-wrap').show();
        if (!this.uploader_) {
            this.uploader_ = new Uploader(this);
        }
    },
    handleDelete: function(record) {//页面删除事件处理
        var self = this;
        var data = { 'id': record.id };
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
pg.admin.ImgManagePage = ImgManagePage;
