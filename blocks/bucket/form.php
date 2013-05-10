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
        <h2>Heading</h2>
        <?php echo $form->text('heading', $heading, array('style' => 'width: 95%;')); ?>

        <div class="ccm-block-field-group">
            <h2>Copy</h2>
            <?php Loader::element('editor_controls'); ?>
            <textarea id="html" name="copy" class="ccm-advanced-editor"><?php echo $copy; ?></textarea>

            <h2>Copy Styles</h2>
            <p>Width: <?php echo $form->text('copy_width', $copy_width, array('style' => 'width: 25%;')); ?></p>
            <p>Top: <?php echo $form->text('copy_top', $copy_top, array('style' => 'width: 25%;')); ?>&nbsp;&nbsp;&nbsp;
                Bottom: <?php echo $form->text('copy_bottom', $copy_bottom, array('style' => 'width: 25%;')); ?><br>
                Only use Top or Bottom
            </p>

            <p>Left: <?php echo $form->text('copy_left', $copy_left, array('style' => 'width: 25%;')); ?>&nbsp;&nbsp;&nbsp;
                Right: <?php echo $form->text('copy_right', $copy_right, array('style' => 'width: 25%;')); ?><br>
                Only use Left or Right
            </p>
        </div>
        <h2>Background Image</h2>
        <?php echo $form->file('background', array('style' => 'width: 95%;')); ?>
        <p>
            <?php
            if (!empty($image_id)) {
                $file = File::getById($image_id);
                Loader::helper('image')->outputThumbnail($file, 100, 100);
            }
            ?>
        </p>
    </div>
</div>