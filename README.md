Amos Workflow
========

The module Workflow is used in order to manage workflow status transitions on records useing a workflow.
Amos Workflow is based on:
- raoul2000/yii2-workflow
- cornernote/yii2-workflow-manager

Installation
------------

1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require open20/amos-workflow
```

or add this row

```
"open20/amos-workflow": "dev-master"
```

to the require section of your `composer.json` file.

2. Add module to your modules config in backend:
	
    ```php
    
    'modules' => [
        'workflow' => [
           'class' => 'open20\amos\workflow\AmosWorkflow',
        ],
    ],
    ```

3. Apply migrations

    a. amos-workflow migrations
    ```bash
    php yii migrate/up --migrationPath=@vendor/open20/amos-workflow/src/migrations
    ```
    
    or add this row to your migrations config in console:
    
    ```php
    return [
        .
        .
        .
        '@vendor/open20/amos-workflow/src/migrations',
        .
        .
        .
    ];
    ```
    
Configuration
------------

//TODO translate-fix this section

Ogni cambio di stato del workflow viene intercettato come evento e viene scritta un record di log nella tabella workflow_transition_log (vedere *WorkflowLogFunctionsBehavior*).

**Define model workflow**  
Il workflow classico in amos4 comprende gli stati:

* bozza /draft
* da validare/richiesta pubblicazione
* validato/pubblicato

Per automatizzare/standardizzare alcune operazioni è stata creata in amos-core l'interfaccia WorkflowModelInterface che è bene implementare, insieme ad estendere NotifyRecord per beneficiare ad esempio delle mail automatiche alla richiesta di validazione del modello.

Il workflow del model viene definito tramite il popolamento delle tabelle:

* sw_workflow: 
definizione id del workflow, per convenzione in amos 4 è il nome del model seguito da 'Workflow' es. 'NewsWorkflow'. 
Definizione dello stato iniziale di default, eventualmente modificabile da interfaccia o tramite migration. Es initial_status_id = 'DRAFT'

* sw_status: 
Definizione degli stati del workflow. Lo stato verrà salvato (come convenzione) nel campo status del model concatendo Workflow_id / status_id ad esempio 'NewsWorkflow/BOZZA'

* sw_transition:
definizione delle transizioni, ossia di tutti i possibili passaggi di stato. 
IMPORTANTE: l'errore non esiste la transizione tra stato A e stato B che potreste riscontrare, se avete definito la transizione è in realtà dovuto alla mancanza del permesso dell'utente salvare il modello in quello stato.

* sw_metadata:
per ogni stato del workflow definisce label sui bottoni di cambio stato, testi dei popup, classi css, ecc.
E' possibile anche commentare il passaggio di stato e salvare tale nota/commento nei log di passaggio di stato.

To enable workflow event behavior, insert in your model behavior array eg.

```php
 public function behaviors()
{
    return ArrayHelper::merge(parent::behaviors(), [
        .
        .
        .
        'workflow' => [
            'class' => SimpleWorkflowBehavior::className(),
            'defaultWorkflowId' => self::NEWS_WORKFLOW,
            'propagateErrorsToModel' => true
        ]
    ];
}
```
Widgets
-----------

Amos Workflow provides three Widgets:

* **WorkflowTransitionButtonsWidget** 
* **WorkflowTransitionStateDescriptorWidget**

//TODO explain/example of use for new widgets above

* **WorkflowTransitionWidget** *open20\amos\workflow\widgets\WorkflowTransitionWidget*  
Draws a section containing model current status and the buttons with possible status to change starting from the current one (reading from sw_metadata).  
***Comment / notes on status change***  
If needed, it is possible to show a popup to insert comment/notes on status change; to enable the functionality add in sw_metadata for the transition final status the metadata: key ='comment', value = 1

It is possible to use a global parameter to hide all transition widgets, if the model workflows are bypassed.
Insert between your application backend params array:
```php
    return [
        .
        .
        .
        'hideWorkflowTransitionWidget' => true
        .
        .
        .
    ];
    ```



example of use in a form:
```php
    <?= WorkflowTransitionWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => ShowcaseProject::SHOWCASEPROJECT_WORKFLOW,
        'classDivIcon' => 'pull-left',
        'classDivMessage' => 'pull-left message',
        'viewWidgetOnNewRecord' => true,
        'translationCategory' => 'amosshowcaseprojects'
    ]); ?>
```
