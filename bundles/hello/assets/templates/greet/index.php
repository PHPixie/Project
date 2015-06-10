<?php $this->layout('layout'); ?>

<?php $this->block('content'); ?>
    <h1>Hello</h1>
    <?=$_($message)?>
<?php $this->endBlock(); ?>