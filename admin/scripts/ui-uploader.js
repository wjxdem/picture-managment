var Uploader = function(list) {
    this.list_ = list;
    this.$wrap = $('#uploader');
    this.$queue = $('<ul class="filelist"></ul>').appendTo(this.$wrap.find('.queueList')), // 图片容器

        this.$statusBar = this.$wrap.find('.statusBar'), //// 状态栏，包括进度和控制按钮
        // 文件总体选择信息。
        this.$info = this.$statusBar.find('.info'),
        // 上传按钮
        this.$upload = this.$wrap.find('.uploadBtn'),

        // 没选择文件之前的内容。
        this.$placeHolder = this.$wrap.find('.placeholder'),

        // 总体进度条
        this.$progress = this.$statusBar.find('.progress').hide(),

        // 添加的文件数量
        this.fileCount = 0,
        // 添加的文件总大小
        this.fileSize = 0,

        // 优化retina, 在retina下这个值是2
        this.ratio = window.devicePixelRatio || 1,

        // 缩略图大小
        this.thumbnailWidth = 110 * this.ratio,
        this.thumbnailHeight = 110 * this.ratio,

        // 可能有pedding, ready, uploading, confirm, done.
        this.state = 'pedding',

        // 所有文件的进度信息，key为file id
        this.percentages = {},

        // WebUploader实例
        this.uploader = null;
    this.init();

}
$.extend(Uploader.prototype, {
    init: function() {
        this.initWebUploader();
        this.attachEvents();
        this.uploader.addButton({
            id: '#filePicker2',
            label: '继续添加'
        });
        this.$upload.addClass('state-' + this.state);
        //        this.updateTotalProgress();
    },
    initWebUploader: function() {
        this.uploader = WebUploader.create({
            pick: {
                id: '#filePicker',
                label: '图片上传' //选择文件的按钮
            },
            // swf文件路径
            /* swf: BASE_URL + '/js/Uploader.swf',*/
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            },
            chunked: true,
            duplicate: true, //是否可重复选择同一文件
            server: './adminAction.php?act=uploadphoto',
            fileNumLimit: 100,

            formData: {},
            fileSizeLimit: 5 * 1024 * 1024, // 200 M 验证文件总大小是否超出限制, 超出则不允许加入队列。
            fileSingleSizeLimit: 1 * 1024 * 1024 // 50 M 
        });

    },
    attachEvents: function() {
        this.uploader.on('startUpload', $.proxy(this.handleStartUpload, this));
        this.uploader.on('uploadBeforeSend', $.proxy(this.handleUploadBeforeSend, this));
        this.uploader.on('fileQueued', $.proxy(this.handleFileQueued, this)); //当文件被加入队列以后触发。
        this.uploader.on('uploadProgress', $.proxy(this.handleUploadProgress, this)); //上传过程
        this.uploader.on('fileDequeued', $.proxy(this.handleFileDequeued, this)); //移除后触发    
        this.uploader.on('all', $.proxy(this.handleAll, this));
        this.uploader.on('uploadAccept', $.proxy(this.handleAccept, this));
        this.uploader.on('uploadSuccess', $.proxy(this.handleUploadSuccess, this));
        this.$upload.on('click', $.proxy(this.handleUploaderClick, this));
        this.$info.on('click', '.js-retry', $.proxy(this.handleRetry, this));
        this.$info.on('click','.js-close',$.proxy(this.handleClose,this));
    },
    handleStartUpload: function() {
        var cid = $('#js-cid').val();
        var pid = $('#js-pid').val();
        $.extend(true, this.uploader.options.formData, { "cid": cid, "pid": pid });
    },
    handleUploaderClick: function() {
        if (this.state === 'ready') {
            this.uploader.upload();
        } else if (this.state === 'paused') {
            this.uploader.upload();
        } else if (this.state === 'uploading') {
            this.uploader.stop();
        }
    },
    handleRetry: function() {
        this.uploader.retry()
    },
    handleClose:function() {
        this.removeQueue();
    },
    handleUploadProgress: function(file, percentage) {
        var $li = $('#' + file.id),
            $percent = $li.find('.progress span');
        $percent.css('width', percentage * 100 + '%');
        this.percentages[file.id][1] = percentage;
        this.updateTotalProgress();

    },
    handleUploadSuccess: function(file, response) {
        this.list_.refreshList();
    },
    handleAccept: function(file, response) {
        if (response.success) {
        } else {
            BUI.Message.Alert(response.msg || 'fail');
            return false;

        }
    },
    handleFileQueued: function(file) {
        this.fileCount++;
        this.fileSize += file.size;
        if (this.fileCount>0) {
            this.$placeHolder.addClass('element-invisible');
            this.$statusBar.show();
            $('.js-cancel-btn').hide();
        }
        
        this.addFile(file);
        this.setState('ready');
        this.updateTotalProgress();
    },
    handleFileDequeued: function(file) {
        this.fileCount--;
        this.fileSize -= file.size;

        if (!this.fileCount) {
            this.setState('pedding');
        }
        this.removeFile(file);
        this.updateTotalProgress();
    },
    updateTotalProgress: function() {
        var loaded = 0,
            total = 0,
            spans = this.$progress.children(),
            percent;

        $.each(this.percentages, function(k, v) {
            total += v[0];
            loaded += v[0] * v[1];
        });

        percent = total ? loaded / total : 0;

        spans.eq(0).text(Math.round(percent * 100) + '%');
        spans.eq(1).css('width', Math.round(percent * 100) + '%');
        this.updateStatus();
    },
    handleAll: function(type) {
        var stats;
        switch (type) {
            case 'uploadFinished':
                this.setState('confirm');
                break;

            case 'startUpload':
                this.setState('uploading');
                break;

            case 'stopUpload':
                this.setState('paused');
                break;

        }
    },
    // 当有文件添加进来时执行，负责view的创建
    addFile: function(file) {
        
        var $li = $('<li id="' + file.id + '">' +
                '<p class="title">' + file.name + '</p>' +
                '<p class="imgWrap"></p>' +
                '<p class="progress"><span></span></p>' +
                '</li>'),
            $btns = $('<div class="file-panel">' +
                '<span class="cancel">删除</span></div>').appendTo($li),
            $prgress = $li.find('p.progress span'),
            $wrap = $li.find('p.imgWrap'),
            $info = $('<p class="error"></p>'),
            self = this,

            showError = function(code) {
                switch (code) {
                    case 'exceed_size':
                        text = '文件大小超出';
                        break;

                    case 'interrupt':
                        text = '上传暂停';
                        break;

                    default:
                        text = '上传失败，请重试';
                        break;
                }

                $info.text(text).appendTo($li);
            };

        if (file.getStatus() === 'invalid') {
            showError(file.statusText);
        } else {
            // @todo lazyload
            $wrap.text('预览中');
            this.uploader.makeThumb(file, function(error, src) {
                if (error) {
                    $wrap.text('不能预览');
                    return;
                }

                var img = $('<img src="' + src + '">');
                $wrap.empty().append(img);
            }, this.thumbnailWidth, this.thumbnailHeight);

            this.percentages[file.id] = [file.size, 0];
        }

        file.on('statuschange', function(cur, prev) {
            if (prev === 'progress') {
                $prgress.hide().width(0);
            } else if (prev === 'queued') {
                $li.off('mouseenter mouseleave');
                $btns.remove();
            }

            // 成功
            if (cur === 'error' || cur === 'invalid') {
                showError(file.statusText);
                self.percentages[file.id][1] = 1;
            } else if (cur === 'interrupt') {
                showError('interrupt');
            } else if (cur === 'queued') {
                self.percentages[file.id][1] = 0;
            } else if (cur === 'progress') {
                $info.remove();
                $prgress.css('display', 'block');
            } else if (cur === 'complete') {
                $li.append('<span class="success"></span>');
            }

            $li.removeClass('state-' + prev).addClass('state-' + cur);
        });
        $li.on('mouseenter', function() {
            $btns.stop().animate({ height: 30 });
        });

        $li.on('mouseleave', function() {
            $btns.stop().animate({ height: 0 });
        });
        $btns.on('click', 'span', function() {
            self.uploader.removeFile(file);
        });
        $li.appendTo(this.$queue);
    },
    setState: function(val) {
        var file, stats,
            $filePicker2 = $('#filePicker2');
        if (val === this.state) {
            return false
        }
        this.$upload.removeClass('state-' + this.state);
        this.$upload.addClass('state-' + val);
        this.state = val;

        switch (this.state) {
            case 'pedding':
                this.$placeHolder.removeClass('element-invisible');
                this.$queue.parent().removeClass('filled');
                this.$queue.hide();
                this.$statusBar.addClass('element-invisible');
                this.uploader.refresh();
                break;

            case 'ready':
                this.$placeHolder.addClass('element-invisible');
                $filePicker2.removeClass('element-invisible');
                this.$queue.parent().addClass('filled');
                this.$queue.show();
                this.$statusBar.removeClass('element-invisible');
                this.uploader.refresh();
                break;

            case 'uploading':
                $filePicker2.addClass('element-invisible');
                this.$progress.show();
                this.$upload.text('暂停上传');
                break;

            case 'paused':
                this.$progress.show();
                this.$upload.text('继续上传');
                break;

            case 'confirm':
                this.$progress.hide();
                this.$upload.text('开始上传');
                
                stats = this.uploader.getStats();
                if (stats.successNum && !stats.uploadFailNum) {
                    this.setState('finish');
                    return;
                }
                break;
            case 'finish':
                stats = this.uploader.getStats();
                if (stats.successNum) {
                    BUI.Message.Alert('上传成功');
                    this.removeQueue();
                   
                } else {
                    // 没有成功的图片，重设
                    this.state = 'done';
                    location.reload();
                }

                break;
        }

        this.updateStatus();
    },
    // 负责view的销毁
    removeFile: function(file) {
        var $li = $('#' + file.id);
        delete this.percentages[file.id];
        this.updateTotalProgress();
        $li.off().find('.file-panel').off().end().remove();
       
    },
    removeQueue:function() {
        var self = this;
        var fileArr = [];
         this.$queue.find('li').each(function(val,key){
            var fileId =  $(key).attr('id');
            fileArr.push(self.uploader.getFile(fileId));
        })
        var len = fileArr.length;
        for(var i=0;i<len;i++){
            this.uploader.removeFile(fileArr[i]);
        }
        $('.js-cancel-btn').show();
    },
    updateStatus: function() {
        var text = '',
            stats;

        if (this.state === 'ready') {
            text = '选中' + this.fileCount + '张图片，共' +
                WebUploader.formatSize(this.fileSize) + '。';
        } else if (this.state === 'confirm') {
            stats = this.uploader.getStats();
            if (stats.uploadFailNum) {
                text = '已成功上传' + stats.successNum + '张照片至***，' +
                    stats.uploadFailNum + '张照片上传失败，<a class="button js-retry" href="#">重新上传</a>失败图片或<a class="button js-close" href="#">关闭</a>'
            }

        } else {
            stats = this.uploader.getStats();
            text = '共' + this.fileCount + '张（' +
                WebUploader.formatSize(this.fileSize) +
                '），已上传' + stats.successNum + '张';

            if (stats.uploadFailNum) {
                text += '，失败' + stats.uploadFailNum + '张';
            }
        }

        this.$info.html(text);
    }
})
