<?php
Loader::element('editor_config');
?>
<style type="text/css" media="screen">
    .ccm-block-field-group h2 { margin-bottom: 5px; }
    .ccm-block-field-group td { vertical-align: middle; }
    .ccm-ui label { display: inline;float: none; }
</style>

<div class="ccm-ui">
    <div class="ccm-block-field-group">
        <h2>CSS ID</h2>
        <?php echo $form->text('css_id', $css_id, array('style' => 'width: 95%;')); ?>
        <h2>CSS Class</h2>
        <?php echo $form->text('css_class', $css_class, array('style' => 'width: 95%;')); ?>
        <h2>Text</h2>
        <?php echo $form->text('btn_text', $btn_text, array('style' => 'width: 95%;')); ?>
        <h2>Url</h2>
        <?php echo $form->text('btn_url', $btn_url, array('style' => 'width: 95%;')); ?>
    </div>
</div>