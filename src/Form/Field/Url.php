<?php

namespace Runhare\Admin\Form\Field;

class Url extends Text
{

    public function render()
    {
        $this->prepend('<i class="fa fa-internet-explorer"></i>')
            ->defaultAttribute('type', 'url');

        return parent::render();
    }
}
