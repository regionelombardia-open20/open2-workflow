<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\core\forms
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
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
    <div class="workflow-button-container m-t-10 m-b-30 nop">
        <?= $resetButton ?>
    </div>
    <div class="workflow-buttons-container nop">
        <?php foreach ($buttons as $button) : ?>
        <?php
            $class = '';
            if(!empty($button['class'])){
            $class = $button['class'];
            }?>
            <div class="workflow-form-actions workflow-button-container m-t-10 m-b-10 <?= $class?>">
                <?= $button['button']; ?>
                <p><?= $button['stateDescriptor'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
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

<?= $hiddenActions; ?>
<?= $notificationInput ?>
