
var wsfeList = function() {
    this.form = null;
    this.grid = null;
    this.store = null;
    this.deffered_ = $.Deferred();
    this.pageSize_ = 10;
    this.init();
};

$.extend(wsfeList.prototype, {

    init: function() {
        var self = this;
        BUI.use(['bui/form', 'bui/grid', 'bui/data', 'bui/toolbar', 'bui/mask'], function(Form, Grid, Data, Toolbar, Mask) {
            self.Grid = Grid;
            self.Form = Form;
            self.Data = Data;
            self.Mask = Mask;
            self.initForm_(Form.HForm).initStore_(Data.Store).initPagingBar_(Toolbar.NumberPagingBar).initGrid_(Grid.Grid).attachEvents();
            self.deffered_.resolve();
        });
    },

    /**
     * 装载用于List初始化成功之后的回调函数callback,尽量不要重写
     * @param  {Function} callback 当List初始化成功之后执行该函数
     * @return {Promise}          返回promise
     */
    done: function(callback) {
        var self = this;
        this.deffered_.done(function() {
            callback(self);
        });
        return this.deffered_.promise();
    },

    /**
     * 装载用于List初始化成功或者失败之后的回调函数resolveCB,rejectCB，尽量不要重写
     * @param  {[type]} resolveCB List初始化成功之后执行的回调函数
     * @param  {[type]} rejectCB  List初始化失败之后执行的回调函数
     * @return {[type]}           返回promise
     */
    then: function(resolveCB, rejectCB) {
        var self = this;
        this.deffered_.then(function() {
            resolveCB(self);
        }, function() {
            rejectCB && rejectCB(self);
        });
        return this.deffered_.promise();
    },

    /**
     * 初始化HForm对象，默认配置是srcNode和submiType,尽量不要重写
     * @param {[BUI.Form.HForm]} Form [bui的form类]
     * @return {List} 返回List对象
     */
    initForm_: function(Form) {
        this.form = new Form($.extend({
            srcNode: '#search-form'
        }, this.formConfig())).render();

        return this;
    },

    /**
     * 自定义HForm的配置，如果需要自定义，那么这个函数需要被重写
     * @return {object} 自定义HForm的配置
     */
    formConfig: function() {
        return {};
    },

    /**
     * [初始化Store 默认配置自己瞅瞅],尽量不要重写
     * @param  {[BUI.Data.Store]} Store [description]
     * @return {[List]}       [返回List对象]
     */
    initStore_: function(Store) {
        this.store = new Store($.extend({
            url: this.form.get('action'),
            proxy: {
                method: this.form.get('method'),
                dataType: "json",
            },
            hasErrorProperty: 'ajaxError', //是否错误的字段（hasError) 
            errorProperty: 'msg', //存放错误信息的字段(error)
            root: 'data.rows', //存放数据的字段名(rows)
            totalProperty: 'data.count', //存放记录总数的字段名(results)
            pageSize: this.pageSize_
        }, this.storeConfig()));

        return this;
    },

    /**
     * 自定义Store的配置，如果需要自定义，那么这个函数需要被重写
     * @return {object} 自定义Store的配置
     */
    storeConfig: function() {
        return {};
    },

    /**
     * 初始化Grid 默认配置自己瞅瞅,尽量不要重写
     * @param {BUI.Grid.Grid} Grid [description]
     * @return {List} 返回List对象
     */
    initGrid_: function(Grid) {
        this.grid = new Grid($.extend({
            render: '#js-grid',
            loadMask: true,
            width: '100%',
            columns: this.columns(),
            store: this.store,
            emptyDataTpl: '<div class="grid-nodata-tpl"><div class="grid-nodata-content"><h3>暂无查询数据</h3></div></div>',
        }, this.gridConfig()));
        this.grid.render();
        if (this.storeConfig().autoLoad != false) {
            this.load();
        }
        return this;
    },

    /**
     * 自定义Grid的配置，如果需要自定义，那么这个函数需要被重写
     * @return {object} 自定义Grid的配置
     */
    gridConfig: function() {
        return {};
    },

    /**
     * 自定义Columns，这个函数必须被重写。
     * @return {Array} 自定义列数组
     */
    columns: function() {
        return [];
    },

    /**
     * 初始化分页
     * @return {[type]} [description]
     */
    initPagingBar_: function(PagingBar) {
        this.pagingBar = new PagingBar($.extend({
            render: '#js-bottom-bar',
            elCls: 'pagination pull-right',
            store: this.store,
            autoRender: true,
            prevText: '上一页',
            nextText: '下一页'
        }, this.pagingBarConfig()));
        return this;
    },

    pagingBarConfig: function() {
        return {};
    },

    /**
     * 设置每页的数量（暂时还没有在实际中测试过该函数的可用性）
     * @param {[type]} pageSize [description]
     */
    setPageSize: function(pageSize) {
        this.pageSize_ = pageSize;
        this.done(function(list) {
            list.store.set('pageSize', pageSize);
        });
    },

    /**
     * 尽量不要重写,除非自己面向对象，继承很了解了
     * @return {[type]} [description]
     */
    attachEvents: function() {
        this.form.on('beforesubmit', $.proxy(this.handleBeforesubmit, this));
        this.store.on('exception', function(ev) {
            var errorMsg=$.isPlainObject(ev.error) ? '发生网络错误，请稍后重试！' : ev.error;
            if(!errorMsg){
                if(ev.msg){
                    errorMsg = ev.msg
                }else{
                    errorMsg = '发生网络错误，请稍后重试！';
                }
            }
            BUI.Message.Alert(errorMsg);
        });
        this.store.on('beforeprocessload', function(ev) {
            if (!ev.data.success) {
                ev.data.ajaxError = true;
            }
        });
        this.grid.on('columnclick', $.proxy(this.handleColumnClick, this)); //根据列排序，目前还没在实际项目中使用过，因此大家如果要使用的话得谨慎。
    },

    /**
     * 这个函数相对比较复杂，如果要重写请了解其中原理之后再重写
     * @param  {[type]} ev [description]
     * @return {[type]}    [description]
     */
    handleColumnClick: function(ev) {
        var column = ev.column,
            dataIndex = column.get('dataIndex'),
            sort = column.get('sort');
        if (sort) {
            var sortDirection = column.get(dataIndex + 'Direction');
            if (!sortDirection || sortDirection === 'DESC') {
                sortDirection = 'ASC';
            } else if (sortDirection === 'ASC') {
                sortDirection = 'DESC';
            }
            column.set(dataIndex + 'Direction', sortDirection);
            if (sort === true) {
                this.load({
                    sortField: dataIndex,
                    sortDirection: sortDirection
                });
            } else if (typeof sort === 'function') {
                var extraParams = sort.call(this, dataIndex, sortDirection);
                this.load(extraParams);
            }
        }
    },

    /**
     * 处理表单提交之前触发的事件，尽量不要重写
     * @return {[type]} [description]
     */
    handleBeforesubmit: function() {
        this.load();
        return false;
    },

    /**
     * 尽量不要重写
     * @param  {[type]} extraParams [description]
     * @return {[type]}             [description]
     */
    load: function(extraParams) {
        /**
         * GDN项目专用，搜索之前需要校验输入字符是否合法并提示。
         * 如果要开启该功能可在formConfig中配置gdnValid:true
         * @youyf
         */
        if(this.formConfig().gdnValid==true){
            var illgalStrReg = "/^([\u4e00-\u9fa5]|[\uE7C7-\uE7F3]|\\w|[，：。,:.（）()-]|[——]|[【】\/*“”@]|[\[]|])*$/";
            if (!this.form.isValid()) {
                return false;   
            }
            var formEl = this.form.get("el");
            var searchInput = formEl.find("input[type='text']");
            var valid=true;
            $.each(searchInput, function() {
                var inputobj = $(this);
                var searchVal = $.trim(inputobj.val());
                if(searchVal.indexOf("--") >= 0){
                    BUI.Message.Alert('搜索值存在非法字符', 'error');
                    valid = false;
                    return false;
                }
                //最大字符不得超过50个
                if (searchVal.length > 50) {
                    BUI.Message.Alert('查询字符串不得超过50个字符', 'error');
                    valid = false;
                    return false;
                }
                //增加文本框校验机制
                var reg = illgalStrReg;
                if (inputobj.attr("data-reg")) {
                    reg = inputobj.attr("data-reg");
                }
                reg = reg.replace(/\/\//g, "\/");
                reg = eval(reg);
                //去除所有空格，避免校验出错。
                if (!reg.test(searchVal.replace(/\s/g, ""))) {
                    var errorInfo='搜索值存在非法字符';
                    if (inputobj.attr("data-message")) {
                        errorInfo = inputobj.attr("data-message");
                    }
                    BUI.Message.Alert(errorInfo, 'error');
                    valid = false;
                    return false;
                }
            });
            if(!valid){
                return false;
            }
        }
        /*
        * 校验结束
        */
        var obj = this.form.serializeToObject();
        if (extraParams && typeof extraParams === 'object') {
            $.extend(obj, extraParams);
        }
        obj.start = 0; //返回第一页
        this.store.load(obj);
    },

    /**
     * 判断是否有xxx权限,大家根据需要自己重写
     * @param  {string}  permission 权限类型
     * @return {Boolean}            [description]
     */
    hasPermission: function(permission) {
        return !!window.globalConfig.permission && window.globalConfig.permission.indexOf(permission) > -1;
    },

    /**
     * 根据某权返回相应的source值，可以根据需要重写
     * @param  {string} permission 权限类型
     * @param  {[type]} source     资源
     * @return {[type]}            [description]
     */
    permissionRender: function(permission, source) {
        if (this.hasPermission(permission)) {
            return source;
        } else {
            switch ($.type(source)) {
                case 'string':
                    return '';
                case 'array':
                    return [];
                case 'object':
                    return {};
                default:
                    return false;
            }
        }
    },

    savePermissionRender: function(source) {
        return this.permissionRender('SAVE', source);
    }
});


var wsfeListController = function(list) {
    this.list = list;
};

$.extend(wsfeListController.prototype, {

    init: function() {
        this.attachEvents();
    },

    /**
     * 尽量不要重写
     * @return {[type]} [description]
     */
    attachEvents: function() {
        var self = this;
        this.list.done(function(list) {
            $(list.grid.get('render')).find('.bui-grid-tbar').on('click', $.proxy(self.handleTbarItemClick, self));
            list.grid.on('aftershow', $.proxy(self.handleGridShow, self));
            list.grid.on('cellclick', $.proxy(self.handleCellClick, self));
            list.store.on('beforeprocessload', $.proxy(self.handleBeforeProcessLoad, self));
        });
    },

    /**
     * 尽量不要重写
     * @param  {[type]} e [description]
     * @return {[type]}   [description]
     */
    handleTbarItemClick: function(e) {
        this.getClickCallBack_(e.target.className).call(this, e);
    },

    /**
     * 尽量不要重写
     * @param  {[type]} className [description]
     * @return {[type]}           [description]
     */
    getClickCallBack_: function(className) {
        var reg = new RegExp(/js-([\w|-]+)-btn/),
            matches = className.match(reg);
        if (matches && matches.length > 1) {
            var action = matches[1].split('-').join(' ').replace(/\b(\w)/g, function($0, $1) {
                return $1.toUpperCase();
            }).split(' ').join('');
            if (this['handle' + action]) {
                return this['handle' + action];
            }
        }
        return $.noop;
    },

    /**
     * 刷新列表
     */
    refreshList: function() {
        this.list.form.submit();
    },

    /**
     * 每当列表渲染成功之后触发的回调函数
     * @param  {[type]} ev [description]
     * @return {[type]}    [description]
     */
    handleGridShow: function(ev) {},

    /**
     * 尽量不要重写
     * @param  {[type]} e [description]
     * @return {[type]}   [description]
     */
    handleCellClick: function(e) {
        this.getClickCallBack_(e.domTarget.className).call(this, e.record, e);
        if (!$(e.domTarget).hasClass('x-grid-checkbox')) {
            return false;
        }
    },

    /**
     * 对通过store请求返回来的数据进行过滤处理，如果需要对返回的数据进行重置，只要在继承的类里面重写这个函数就行了。
     * @param  {[type]} ev  jQuery.Event
     * @return {[type]}    [description]
     */
    handleBeforeProcessLoad: function(ev) {
        //ev.data 数据库返回的数据
//        console.log(ev);
    }
});