<?php 

namespace crud\actions;

use yii\base\Action;

class DeleteAction extends Action
{

    public $redirectAction = 'index';

    public function run($id)
    {
        $this->controller->findModel($id)->delete();

        return $this->controller->redirect([$this->redirectAction]);
    }

}
