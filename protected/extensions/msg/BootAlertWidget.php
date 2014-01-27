<?php

Yii::import('bootstrap.widgets.BootAlert');

class BootAlertWidget extends BootAlert {

    public function run() {
        if (is_string($this->keys))
            $this->keys = array($this->keys);

        echo CHtml::openTag('div', $this->htmlOptions);

        $transitions = Yii::app()->bootstrap->isPluginRegistered(Bootstrap::PLUGIN_TRANSITION);

        foreach ($this->keys as $key) {

            $messages = Msg::get($key, true);
            $count = count($messages);
            if ($count > 0) {
                $message = '';
                if ($count == 1)
                    $message = current($messages);
                else {
                    $message .= CHtml::openTag('ul');
                    foreach ($messages as $msg) {
                        $message .= CHtml::openTag('li');
                        $message .= $msg;
                        $message .= CHtml::closeTag('li');
                    }
                    $message .= CHtml::closeTag('ul');
                }

                echo strtr($this->template, array(
                    '{class}' => $transitions ? ' fade in' : '',
                    '{key}'=>$key,
                    '{message}' => $message,
                ));
            }
        }

        echo '</div>';

        $selector = "#{$this->id} .alert";
        Yii::app()->clientScript->registerScript(__CLASS__ . '#' . $this->id, "jQuery('{$selector}').alert();");

        /*
          // Register the "close" event-handler.
          if (isset($this->events['close']))
          {
          $fn = CJavaScript::encode($this->events['close']);
          Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->id.'.close', "jQuery('{$selector}').bind('close', {$fn});");
          }

          // Register the "closed" event-handler.
          if (isset($this->events['closed']))
          {
          $fn = CJavaScript::encode($this->events['closed']);
          Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->id.'.closed', "jQuery('{$selector}').bind('closed', {$fn});");
          }
         */
    }

}