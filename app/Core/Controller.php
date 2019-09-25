<?php

namespace Avanciro\Skel\Core;
use Avanciro\Skel\Core\Registry;

class Controller {

    public $data = null;
    protected $_registry = null;

    public function __construct(Registry $Registry) {
        $this->_registry = $Registry;
        $this->data = array();
    }

    public function __get($key) {
        return $this->_registry->$key;
    }

}

?>