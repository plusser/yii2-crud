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
        return (is_object($model = new $this->modelClass) && $model->load(Yii::$app->request->post()) && $model->save()) ? $this->controller->redirect([
            $this->redirectAction,
            'id' => $model->id,
        ]) : $this->controller->render($this->viewName, [
            'model' => $model,
        ]);
    }

}
