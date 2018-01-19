<?php

/**
 * Description of DeleteEvent
 *
 * @author hare
 * @Time   Nov 17, 2017 9:35:19 AM
 */

namespace Runhare\Admin\Event;

class DeleteEvent {
    
    protected $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
}
