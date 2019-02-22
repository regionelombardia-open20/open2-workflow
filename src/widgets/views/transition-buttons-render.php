<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\core\forms
 * @category   CategoryName
 */

?>

<div class="workflow-transition-button-widget col-xs-12">

    <div class="workflow-buttons-container col-lg-10 col-lg-push-2 col-xs-12 nop">
        <?php foreach ($buttons as $button) : ?>
            <div class="workflow-form-actions workflow-button-container">
                <?= $button['button']; ?>
                <p><?= $button['stateDescriptor'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="workflow-button-container col-lg-2 col-lg-pull-10 col-xs-12 nop">
        <?= $resetButton ?>
    </div>
</div>

<?= $hiddenActions; ?>
<?= $notificationInput ?>
