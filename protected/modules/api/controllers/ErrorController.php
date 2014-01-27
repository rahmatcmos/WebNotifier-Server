<?php
class ErrorController extends RESTController {
    public function actionError() {
        if (Yii::app()->errorHandler->error)
            Yii::app()->rest->response->send(500,  $this->wrappError(Yii::app()->errorHandler->error['message']));
    }
}
