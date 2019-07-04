<?php

namespace Runhare\Admin\Form\Field;

use Runhare\Admin\Form\Field;

class Ueditor extends Field
{
    protected $view = 'admin::form.ueditor';
    protected static $css = [
    ];
    protected static $js = [
        "/packages/admin/ueditor/ueditor.config.js",
        "/packages/admin/ueditor/ueditor.all.js"
    ];

    public function render() {
        $this->script = <<<EOT
         UE.getEditor('{$this->id}111',{
                autoHeight:true,
                initialFrameHeight:400
        });

EOT;
        return parent::render();
    }
}
