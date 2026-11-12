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

namespace open20\amos\workflow\components\events;

use open20\amos\workflow\models\WorkflowTransitionsLog;
use open20\amos\core\record\Record;
use raoul2000\workflow\events\WorkflowEvent;
use yii\base\Component;
use yii\db\ActiveQuery;
use yii\helpers\Json;

class SimpleWorkFlowEventsListener extends Component
{
    /**
     * @param $class string the classname on entity
     * @param $primaryKey mixed number|array
     * @param $status string, the status of SimpleWorkflowBehavior
     * @return null|string (datetime)
     */
    public static function getLastUpdateTime($class, $primaryKey, $status)
    {
        $primaryKeyJson = Json::encode($primaryKey);
        $logList = WorkflowTransitionsLog::find()->andWhere([
            'classname' => $class,
            'owner_primary_key' => $primaryKeyJson,
            'end_status' => $status
        ])->orderBy('created_at DESC')->all();
        if (count($logList) >= 1) {
            /** @var WorkflowTransitionsLog $wtl */
            $wtl = reset($logList);
            return isset($wtl->created_at) ? $wtl->created_at : null;
        }
        return null;
    }

    /**
     * @param $class string the classname on entity
     * @param $primaryKey mixed number|array
     * @param $status string, the status of SimpleWorkflowBehavior
     * @return null|number (user)
     */
    public static function getLastUpdateUser($class, $primaryKey, $status)
    {
        $primaryKeyJson = Json::encode($primaryKey);
        $logList = WorkflowTransitionsLog::find()->andWhere([
            'classname' => $class,
            'owner_primary_key' => $primaryKeyJson,
            'end_status' => $status
        ])->orderBy('created_at DESC')->all();
        if (count($logList) >= 1) {
            /** @var WorkflowTransitionsLog $wtl */
            $wtl = reset($logList);
            return isset($wtl->created_by) ? $wtl->created_by : null;
        }
        return null;
    }

    /**
     * @param $class string the classname on entity
     * @param $primaryKey mixed number|array
     * @param $status string, the status of SimpleWorkflowBehavior
     * @return null|number (user)
     */
    public static function getFirstUpdateUser($class, $primaryKey, $status)
    {
        $primaryKeyJson = Json::encode($primaryKey);
        $logList = WorkflowTransitionsLog::find()->andWhere([
            'classname' => $class,
            'owner_primary_key' => $primaryKeyJson,
            'end_status' => $status
        ])->orderBy('created_at ASC')->all();
        if (count($logList) >= 1) {
            /** @var WorkflowTransitionsLog $wtl */
            $wtl = reset($logList);
            return isset($wtl->created_by) ? $wtl->created_by : null;
        }
        return null;
    }

    /**
     * @param $class string the classname on entity
     * @param $primaryKey mixed number|array
     * @param $status string, the status of SimpleWorkflowBehavior
     * @return array WorkflowTransitionsLog[]
     */
    public static function getUpdateHistory($class, $primaryKey)
    {
        $primaryKeyJson = Json::encode($primaryKey);
        /** @var ActiveQuery $logListQ */
        $logListQ = WorkflowTransitionsLog::find()->andWhere([
            'classname' => $class,
            'owner_primary_key' => $primaryKeyJson
        ])->orderBy('created_at ASC');
        $logList = $logListQ->all();
        if (count($logList) > 0) {
            return is_array($logList) ? $logList : [];
        }
        return [];
    }

    /**
     * @param $event WorkflowEvent
     * @return bool
     */
    public static function afterChangeStatus($event)
    {
        /** @var Record $owner */
        $owner = $event->sender->owner;
        if (!empty($owner)) {

            $post = [];
            $startStatus = $event->getStartStatus();
            $endStatus = $event->getEndStatus();
            $workflowTransitionLog = new WorkflowTransitionsLog;

            //check if any comment has been typed on status change
            if (isset($_POST[$owner->formName()])) {
                $post = $_POST[$owner->formName()];
            }
            if (count($post)) {
                if (array_key_exists('changeStatusComment', $post)) {
                    $comment = $post['changeStatusComment'];
                    if(!empty($comment)) {
                        $workflowTransitionLog->comment = $comment;
                    }
                }
            }

            $workflowTransitionLog->classname = $owner->className();
            $workflowTransitionLog->owner_primary_key = Json::encode($owner->getPrimaryKey());
            if (!empty($startStatus)) {
                $workflowTransitionLog->start_status = $startStatus->getId();
            }
            $workflowTransitionLog->end_status = $endStatus->getId();
            $workflowTransitionLog->save(false);
        }
        return true;
    }

}
