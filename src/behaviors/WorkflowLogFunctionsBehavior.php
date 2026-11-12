<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\workflow\behaviors
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

namespace open20\amos\workflow\behaviors;

use open20\amos\core\record\Record;
use open20\amos\workflow\components\events\SimpleWorkFlowEventsListener;
use open20\amos\workflow\models\WorkflowTransitionsLog;
use yii\base\Behavior;
use yii\helpers\Json;

/**
 * Class WorkflowLogFunctionsBehavior
 * @package open20\amos\workflow\behaviors
 */
class WorkflowLogFunctionsBehavior extends Behavior
{

    /**
     * @var string $changeStatusComment - attribute for model to send comment data on status change
     */
    public $changeStatusComment;

    public function getStatusLastUpdateTime($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getLastUpdateTime($owner->className(), $owner->primaryKey, $status);
    }

    public function getStatusLastUpdateUser($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getLastUpdateUser($owner->className(), $owner->primaryKey, $status);
    }

    public function getStatusFirstUpdateUser($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getFirstUpdateUser($owner->className(), $owner->primaryKey, $status);
    }

    /**
     * @param $status string, the status of SimpleWorkflowBehavior
     * @return null|number (user)
     */
    public function getStatusLastUpdateComment($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        $class = $owner->className();
        $primaryKey = $owner->primaryKey;
        $primaryKeyJson = Json::encode($primaryKey);
        $logList = WorkflowTransitionsLog::find()->andWhere([
            'classname' => $class,
            'owner_primary_key' => $primaryKeyJson,
            'end_status' => $status
        ])->orderBy('created_at DESC')->all();
        if (count($logList) >= 1) {
            /** @var WorkflowTransitionsLog $wtl */
            $wtl = reset($logList);
            return isset($wtl->comment) ? $wtl->comment : null;
        }
        return null;
    }

    public function getStatusUpdateHistory()
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getUpdateHistory($owner->className(), $owner->primaryKey);
    }
}
