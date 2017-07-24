<?php 

namespace crud\actions;

use yii\base\Action;

class ViewAction extends Action
{

    public $viewName = 'view';

    public function run($id)
    {
        return $this->controller->render($this->viewName, [
            'model' => $this->controller->findModel($id),
        ]);
    }

}
