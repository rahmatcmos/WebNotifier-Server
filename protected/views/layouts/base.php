<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo implode(' - ',array($this->pageTitle,Yii::app()->name))?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php $cs = Yii::app()->clientScript; ?>
        
        <?php $cs->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.css'); ?>
        <?php $cs->registerCssFile(Yii::app()->baseUrl.'/css/layout-main.css'); ?>

    </head>    
<body>
    <?php echo $content ?>
</body>
</html> 

