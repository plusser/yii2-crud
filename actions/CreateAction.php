<?php 

namespace crud\actions;

use Yii;
use yii\base\Action;

class CreateAction extends Action
{

    public $viewName = 'create';
    public $redirectAction = 'view';
    public $modelClass;

    public function run()
    {
        return (is_object($model = new $this->modelClass) AND $model->load(Yii::$app->request->post()) AND $model->save()) ? $this->controller->redirect([
            $this->redirectAction,
            'id' => $model->id
        ]) : $this->controller->render($this->viewName, [
            'model' => $model,
        ]);
    }

}
