<?php

class RESTResponseJSON extends RESTResponseBase {

    private $_mimeType = 'application/json';

    public function send($status, $data, $encode = true, $wrapper=null) {
        if ($wrapper !== null)
            $data = $this->wrap($wrapper, $data);

        if ($encode)
            $data = $this->encode($data);

        $this->_sendResponse($status, $data, $this->_mimeType);
    }

    public function wrap($wrapper, $data) {        
        return array($wrapper => $data);
    }

    public function encode($data) {
        return CJSON::encode($data);
    }

}