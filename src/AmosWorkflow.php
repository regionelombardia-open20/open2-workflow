<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\workflow
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

namespace open20\amos\workflow;

use open20\amos\core\module\Module;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\record\Record;
use open20\amos\workflow\components\events\SimpleWorkFlowEventsListener;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\base\Event;

/**
 * Class AmosWorkflow
 * @package open20\amos\workflow
 */
class AmosWorkflow extends Module implements ModuleInterface
{
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    public $name = 'Workflow';

    public static function getModuleName()
    {
        return "workflow";
    }

    public function init()
    {
        parent::init();
        // initialize the module with the configuration loaded from config.php
        Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php'));

        Event::on(Record::className(), SimpleWorkflowBehavior::EVENT_AFTER_CHANGE_STATUS, [SimpleWorkFlowEventsListener::className(), 'afterChangeStatus']);
    }

    public function getWidgetIcons()
    {
        return [];
    }

    public function getWidgetGraphics()
    {
        return [];
    }
}