<?php $this->titleMenu = array(
    array('label'=>'Logowanie', 'url'=>array('/profile/login'),'icon'=>'icon-user'),
);?>

<?php $this->renderPartial('_form',array('model'=>$model)); ?>