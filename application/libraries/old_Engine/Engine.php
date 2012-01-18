<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Engine extends CI_Driver_Library {

    function Engine()
    {
        $this->valid_drivers[] = 'Engine_socialmention';
    }

}
