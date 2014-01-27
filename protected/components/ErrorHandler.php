<?php
class ErrorHandler extends CErrorHandler {

    public $ajaxRender = true;
    private $_error;
 
    protected function render($view, $data) {
        if ($this->isAjaxRequest() && !$this->ajaxRender) {
            echo $data['message'];
        } else {

            if ($view === 'error' && $this->errorAction !== null)
                Yii::app()->runController($this->errorAction);
            else {
                // additional information to be passed to view
                $data['version'] = $this->getVersionInfo();
                $data['time'] = time();
                $data['admin'] = $this->adminInfo;
                include($this->getViewFile($view, $data['code']));
            }
        }
    }
}
