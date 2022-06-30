<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\forms
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var string $widgetClass
 * @var string $resetButton
 * @var array $buttons
 * @var string $hiddenActions
 * @var string $notificationInput
 * @var string $renderStatusError
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
    <div class="field-workflow-status_id required has-error">
        <div class="row">
            <div class="col-xs-12">
                <div class="tooltip-error-field">
                    <?= $renderStatusError; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $hiddenActions; ?>
<?= $notificationInput ?>
