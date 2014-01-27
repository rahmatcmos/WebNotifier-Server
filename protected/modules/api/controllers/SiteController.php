<?php

class SiteController extends RESTController {

    public function filters() {
        return array(
            array(
                'application.modules.api.filters.RESTAuthFilter',
            ),
        );
    }

    public function actionCheck() {
        $rows = array();

        $criteria = new CDbCriteria();
        $criteria->compare('`t`.`user`', Yii::app()->user->id);

        foreach (Site::model()->sorted()->findAll($criteria) as $model) {
            if ($model->hasMonitor()) {
                $row = array(
                    'id' => $model->getprimaryKey(),
                    'name' => $model->name,
                    'url' => $model->url,
                    'states' => array()
                );

                $info = Web::info($model->url);
                
                $available = ($info['errno'] !== 0) ? false : true;

                if ($model->checkAvailability)
                    $row['states']['availability'] = $available;

                $status = $info['status'];

                if ($model->checkStatus && $available)
                    $row['states']['status'] = $status;

                if ($status === 200 && $model->checkContent) {
                    $hash = Web::hash($info['content'],$model->element);
                    if ($model->contentHash != $hash) {
                        $model->contentHash = $hash;
                        $model->scenario = 'content';
                        if (!$model->save(false))
                            throw new CHttpException(500,$this->wrappError('Wystapił błąd podczas zapisu sumy kontrolnej.'));

                        $row['states']['content'] = true;
                    } else
                        $row['states']['content'] = false;
                }

                $rows[] = $row;
            }
        }

        Yii::app()->rest->response->send(200, $rows);
    }
}