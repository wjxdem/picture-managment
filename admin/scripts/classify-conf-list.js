
var Logolist = function() {
    wsfeList.apply(this, arguments);
}
$.inherit(Logolist, wsfeList);

$.extend(Logolist.prototype, {
    gridConfig: function() {
        return {
            bbar: {
                pagingBar: false
            },
            render: '#js-logo-grid'
        }
    },
    pagingBarConfig: function() {
        return {
            render: '#js-logo-bottom-bar'
        }
    },
    formConfig: function() {
        return {
            srcNode: '#logoForm'
        }

    },
    columns: function() {
    	
	        return [{
	            title: "id",
	            dataIndex: 'id',
	            visible: false
	
	        }, {
	            title: '分类名称',
	            elCls: 'center',
	            dataIndex: 'cname',
	            width: '30%'
	        }, {
	            title: '素材数目',
	            dataIndex: 'pnum',
	            elCls: 'center',
	            width: '30%'
	        }, {
	            title: '创建时间',
	            dataIndex: 'ptime',
	            elCls: 'center',
	            width: '30%',
	        }, {
	            title: '操作',
	            width: '10%',
	            elCls: 'center',
	            renderer: function(val) {
	                var str = '<div class="gird-handle-icon">' + '<a href="javascript:;" class="wsicon wsicon-edit js-edit-btn" title="编辑"></a>' + '<a href="javascript:;" class="wsicon wsicon-delete js-delete-btn" title="删除"></a>' + '</div>';
	                return str;
	
	            }
	        }];
    },
})

$.namespace('pg.classify.LogoController');
var LogoController = function() {
    wsfeListController.apply(this, [new Logolist()]);
    this.init();

}
$.inherit(LogoController, wsfeListController)

$.extend(LogoController.prototype, {
	init:function(){
		this.superClass_.init.call(this);
		this.initDialog();
	},
	initDialog: function() {
        this.dialog = new Dialog({
            title: '编辑分类',
            height: 'auto',
            contentId: 'js-dialog-logo-content',
            beforeShow: $.proxy(this.handleBeforeShow, this),
            ajaxSuccess: $.proxy(this.refreshList, this)
        })
    },
     handleBeforeShow: function(dialog, outSideParams) {
        var $form = dialog.get('el').find('form');
        $form.find('input[name="id"]').val(outSideParams.id);
        $form.find('input[name="cname"]').val(outSideParams.cname);
        $form.find('.pnum').html(outSideParams.pnum);
        $form.find('input[name="pnum"]').html(outSideParams.pnum);
        $form.find('.ptime').html(outSideParams.ptime);
        $form.find('input[name="ptime"]').html(outSideParams.ptime);
    },
    handleEdit: function(record) {
        this.dialog.show(record);
    },

    handleDelete: function(record) {
        var self = this;
        var data = { 'id': record.id };
        if (record.id) {
            BUI.Message.Confirm('确定将删掉该分类和该分类下的图片？', function() {
                $.ajax({
                    url: window.globalConfig.api.deleteLogo,
                    dataType: 'json',
                    data: data,
                    type: 'post'
                }).done(function(data) {
                    if (data.success) {
                        setTimeout(function() {
                            BUI.Message.Alert('删除成功', function() {
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
pg.classify.LogoController = LogoController;

var Toplist = function() {
    wsfeList.apply(this, arguments);
}
$.inherit(Toplist, wsfeList);

$.extend(Toplist.prototype, {
    gridConfig: function() {
        return {
            bbar: {
                pagingBar: false
            },
            render: '#js-top-grid'
        }
    },
    pagingBarConfig: function() {
        return {
            render: '#js-top-bottom-bar'
        }
    },
    formConfig: function() {
        return {
            srcNode: '#topForm'
        }

    },
    columns: function() {
        return [{
            title: "id",
            dataIndex: 'id',
            visible: false,
        }, {
            title: '一级分类',
            dataIndex: 'sname',
            elCls: 'center',
            width: '30%'
        }, {
        	title:'二级分类数目',
        	dataIndex:'snum',
        	elCls: 'center',
            width: '30%'
        },{
            title: '创建时间',
            dataIndex: 'stime',
            elCls: 'center',
            width: '30%'
        }, {
            title: '操作',
            width: '10%',
            elCls: 'center',
            renderer: function(val) {
                var str = '<div class="gird-handle-icon">' + '<a href="javascript:;" class="wsicon wsicon-edit js-edit-btn" title="编辑"></a>' + '<a href="javascript:;" class="wsicon wsicon-delete js-delete-btn" title="删除"></a>' + '</div>';
                return str;
            }
        }];

    },
})

$.namespace('pg.classify.TopController');
var TopController = function() {
    wsfeListController.apply(this, [new Toplist()]);
    this.init();
}
$.inherit(TopController, wsfeListController)

$.extend(TopController.prototype, {
	init:function(){
		this.superClass_.init.call(this);
		this.initDialog();
	},
	 initDialog: function() {
        this.dialog = new Dialog({
            title: '编辑分类',
            height: 'auto',
            contentId: 'js-dialog-top-content',
            beforeShow: $.proxy(this.handleBeforeShow, this),
            ajaxSuccess: $.proxy(this.refreshList, this)
        })
    },
     handleBeforeShow: function(dialog, outSideParams) {
        var $form = dialog.get('el').find('form');
        $form.find('input[name="id"]').val(outSideParams.id);
        $form.find('input[name="sname"]').val(outSideParams.sname);
        $form.find('.snum').html(outSideParams.snum);
//        $form.find('input[name="total"]').html(outSideParams.total);
        $form.find('.stime').html(outSideParams.stime);
//        $form.find('input[name="makeTime"]').html(outSideParams.makeTime);
    },
    handleEdit: function(record) {
        this.dialog.show(record);
    },

    handleDelete: function(record) {
        var self = this;
        var data = { 'id': record.id };
        if (record.id) {
            BUI.Message.Confirm('确定将删掉该一级分类？', function() {
                $.ajax({
                    url: window.globalConfig.api.deleteTop,
                    dataType: 'json',
                    data: data,
                    type: 'post'
                }).done(function(data) {
                    if (data.success) {
                        setTimeout(function() {
                            BUI.Message.Alert('删除成功', function() {
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
pg.classify.TopController = TopController;
var Sublist = function() {
    wsfeList.apply(this, arguments);
}
$.inherit(Sublist, wsfeList);

$.extend(Sublist.prototype, {
    gridConfig: function() {
        return {
            bbar: {
                pagingBar: false
            },
            render: '#js-sub-grid'
        }
    },
    pagingBarConfig: function() {
        return {
            render: '#js-sub-bottom-bar'
        }
    },
    formConfig: function() {
        return {
            srcNode: '#subForm'
        }

    },
    columns: function() {
        return [{
            title: "id",
            dataIndex: 'id',
            visible: false,
        },  {
            title: '二级分类',
            dataIndex: 'cname',
            elCls: 'center',
            width: '25%'
        }, {
            title: '一级分类',
            dataIndex: 'sname',
            elCls: 'center',
            width: '25%'
        },{
        	title:'素材数目',
        	dataIndex:'pnum',
        	elCls: 'center',
            width: '20%'
        },
        {
            title: '创建时间',
            dataIndex: 'ptime',
            elCls: 'center',
            width: '25%'
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

$.namespace('pg.classify.SubController');
var SubController = function() {
    wsfeListController.apply(this, [new Sublist()]);
    this.init();

}
$.inherit(SubController, wsfeListController)

$.extend(SubController.prototype, {
	init:function(){
		this.superClass_.init.call(this);
		this.initDialog();
	},

	initDialog: function() {
        this.dialog = new Dialog({
            title: '编辑分类',
            height: 'auto',
            contentId: 'js-dialog-sub-content',
            beforeShow: $.proxy(this.handleBeforeShow, this),
            ajaxSuccess: $.proxy(this.refreshList, this)
        })
    },
     handleBeforeShow: function(dialog, outSideParams) {
          var $form = dialog.get('el').find('form');
        $form.find('input[name="id"]').val(outSideParams.id);
        $form.find('input[name="cname"]').val(outSideParams.cname);
        $form.find('.sname').html(outSideParams.sname);
//        $form.find('input[name="topClassify"]').html(outSideParams.topClassify);
        $form.find('.pnum').html(outSideParams.pnum);
//        $form.find('input[name="total"]').html(outSideParams.total);
        $form.find('.ptime').html(outSideParams.ptime);
//        $form.find('input[name="makeTime"]').html(outSideParams.makeTime);
    },
    handleEdit: function(record) {
        this.dialog.show(record);
    },

    handleDelete: function(record) {
        var self = this;
        var data = { 'id': record.id };
        if (record.id) {
            BUI.Message.Confirm('确定将删掉该分类和有该分类下的图片？', function() {
                $.ajax({
                    url: window.globalConfig.api.deleteSub,
                    dataType: 'json',
                    data: data,
                    type: 'post'
                }).done(function(data) {
                    if (data.success) {
                        setTimeout(function() {
                            BUI.Message.Alert('删除成功', function() {
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
pg.classify.SubController = SubController;
