<?php

namespace Runhare\Admin\Form\Field;

use Runhare\Admin\Form\Field;

class Editor extends Field
{
    protected $view = 'admin::form.editor';
    protected static $css = [
        '/packages/wangEditor-2.1.23/dist/css/wangEditor.min.css',
    ];
    protected static $js = [
        '/packages/wangEditor-2.1.23/dist/js/wangEditor.min.js',
    ];

    public function render() {
        $this->script = <<<EOT
$('#{$this->id}').css({height:'400px'})
var editor = new wangEditor('{$this->id}');
editor.config.menus = [
        'source',
        '|',
        'bold',
        'underline',
        'italic',
        'eraser',
        'forecolor',
        'bgcolor',
        '|',
        'fontfamily',
        'fontsize',
        'head',
        'orderlist',
        'alignleft',
        'aligncenter',
        'alignright',
        '|',
        'link',
        'unlink',
        'table',
        'emotion',
        '|',
        'img',
        'video',
        'location',
        'insertcode',
        '|',
        'undo',
        'redo',
        'fullscreen'
     ];
    // 上传图片（举例）
    editor.config.uploadImgUrl = '/admin/webimg';
    
    // 配置自定义参数（举例）
    editor.config.uploadParams = {

    };
    
    // 设置 headers（举例）
    editor.config.uploadHeaders = {
        'Accept' : 'text/x-json'
    };
    
    // 隐藏掉插入网络图片功能。该配置，只有在你正确配置了图片上传功能之后才可用。
     //editor.config.hideLinkImg = true;
    editor.config.uploadImgFileName = 'wangEditorH5File'
    editor.create();

EOT;
        return parent::render();
    }
}
