<?php 

namespace crud\actions;

use Yii;
use yii\base\Action;

class IndexAction extends Action
{

    public $viewName = 'index';
    public $searchModel;

    public function run()
    {
        return $this->controller->render($this->viewName, [
            'searchModel' => $this->searchModel,
            'dataProvider' => $this->searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

}
