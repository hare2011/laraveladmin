<?php

namespace Runhare\Admin\Form\Field;

use Runhare\Admin\Form\Field;

class Html extends Field
{
    /**
     * Htmlable.
     *
     * @var string|\Closure
     */
    protected $html = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * Create a new Html instance.
     *
     * @param mixed $html
     * @param array $arguments
     */
    public function __construct($html, $arguments)
    {
        $this->html = $html;

        $this->label = array_get($arguments, 0);
    }

    /**
     * Render html field.
     *
     * @return string
     */
    public function render()
    {
        if ($this->html instanceof \Closure) {
            $callback = $this->html->bindTo($this->form->model());

            $this->html = call_user_func($callback, $this->form);
        }

        return <<<EOT
<div class="form-group">
    <label  class="col-sm-{$this->width['label']} control-label">{$this->label}</label>
    <div class="col-sm-{$this->width['field']}">
        {$this->html}
    </div>
</div>
EOT;
    }
}
