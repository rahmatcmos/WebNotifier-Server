<?php $this->titleMenu = array(
    array('label'=>'Dodawanie', 'url'=>array('/site/create'),'icon'=>'icon-plus-sign'),
);?>

<?php $this->widget('bootstrap.widgets.BootGridView', array(
    'type'=>'striped',
    'filter'=>$model,
    'dataProvider'=>$model->search(),
    'template'=>"{summary}\n{items}\n{pager}",
    'columns'=>array(
        array(
            'name'=>'name',
            'headerHtmlOptions'=>array('style'=>'width:20%'),            
        ),
        array(
            'name'=>'url',
            'headerHtmlOptions'=>array('style'=>'width:29%'),            
        ),
        array(
            'class'=>'CCheckBoxColumn',
            'selectableRows'=>0,
            'name'=>'checkAvailability',
            'header'=>'Dostępność',
            'checked'=>'$data->checkAvailability==1 ? true : false',
            'checkBoxHtmlOptions'=>array('disabled'=>'disabled'),
            'headerHtmlOptions'=>array('style'=>'width:10%'),
        ),
        array(
            'class'=>'CCheckBoxColumn',
            'selectableRows'=>0,
            'name'=>'checkStatus',
            'header'=>'Status',
            'checked'=>'$data->checkStatus==1 ? true : false',
            'checkBoxHtmlOptions'=>array('disabled'=>'disabled'),
            'headerHtmlOptions'=>array('style'=>'width:10%'),            
        ),
        array(
            'class'=>'CCheckBoxColumn',
            'selectableRows'=>0,
            'name'=>'checkContent',
            'header'=>'Zawartość',
            'checked'=>'$data->checkContent==1 ? true : false',
            'checkBoxHtmlOptions'=>array('disabled'=>'disabled'),
            'headerHtmlOptions'=>array('style'=>'width:10%'),   
            //'filter'=>array('Nie','Tak')
        ),
        array(
            'name'=>'contentTime',
            'headerHtmlOptions'=>array('style'=>'width:15%'),
        ),
        array(
            'class'=>'bootstrap.widgets.BootButtonColumn',
            'template'=>'{update} {delete}',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>