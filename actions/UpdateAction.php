<?php 

namespace crud\actions;

use Yii;
use yii\base\Action;

class UpdateAction extends Action
{

    public $viewName = 'update';
    public $redirectAction = 'view';

    public function run($id)
    {
        return (is_object($model = $this->controller->findModel($id)) && $model->load(Yii::$app->request->post()) && $model->save()) ? $this->controller->redirect([
            $this->redirectAction,
            'id' => $model->id,
        ]) : $this->controller->render($this->viewName, [
            'model' => $model,
        ]);
    }

}
