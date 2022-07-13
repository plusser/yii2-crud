<?php 

namespace crud\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use crud\actions\CreateAction;
use crud\actions\DeleteAction;
use crud\actions\IndexAction;
use crud\actions\UpdateAction;
use crud\actions\ViewAction;

abstract class CRUDController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'view', 'update', 'delete', ],
                'rules' => [
                    'index' => [
                        'actions' => ['index', ],
                        'allow' => true,
                        'roles' => [$this->getPermissionPrefix() . 'Index', ],
                    ],
                    'create' => [
                        'actions' => ['create', ],
                        'allow' => true,
                        'roles' => [$this->getPermissionPrefix() . 'Create', ],
                    ],
                    'view' => [
                        'actions' => ['view', ],
                        'allow' => true,
                        'roles' => [$this->getPermissionPrefix() . 'View', ],
                    ],
                    'update' => [
                        'actions' => ['update', ],
                        'allow' => true,
                        'roles' => [$this->getPermissionPrefix() . 'Update', ],
                    ],
                    'delete' => [
                        'actions' => ['delete', ],
                        'allow' => true,
                        'roles' => [$this->getPermissionPrefix() . 'Delete', ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => $this->getModelSearch(),
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->getModelClass(),
            ],
            'update' => [
                'class' => UpdateAction::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
            ],
        ];
    }

    public function findModel($id)
    {
        $modelClass = $this->getModelClass();

        if(is_null($model = $modelClass::findOne($id))){
            throw new NotFoundHttpException('Модель не найдена.');
        }

        return $model;
    }

    public function getActionColumn(): array
    {
        return [
            'class' => ActionColumn::class,
            'headerOptions' => [
                'style' => 'width: 80px;',
            ],
            'visibleButtons' => [
                'view' => Yii::$app->user->can($this->getPermissionPrefix() . 'View'),
                'update' => Yii::$app->user->can($this->getPermissionPrefix() . 'Update'),
                'delete' => function($model, $key, $index){
                    return (!$model->hasMethod('getHasActiveRelations') || !$model->hasActiveRelations) && Yii::$app->user->can($this->getPermissionPrefix() . 'Delete');
                },
            ],
            'urlCreator' => function($action, $model, $key, $index, $column){
                return Url::toRoute([$action, 'id' => $model->id]);
            }
        ];
    }

    public function getCreateButton(string $title = 'Создать'): string
    {
        return Yii::$app->user->can($this->getPermissionPrefix() . 'Create') ? Html::a($title, ['create'], ['class' => 'btn btn-success']) : '';
    }

    public function getUpdateButton($model, string $title = 'Редактировать'): string
    {
        return Yii::$app->user->can($this->getPermissionPrefix() . 'Update') ? Html::a($title, ['update', 'id' => $model->id], ['class' => 'btn btn-success', ]) : '';
    }

    public function getDeleteButton($model, string $title = 'Удалить'): string
    {
        return ((!$model->hasMethod('getHasActiveRelations') || !$model->hasActiveRelations) && Yii::$app->user->can($this->getPermissionPrefix() . 'Delete')) ? Html::a($title, ['delete', 'id' => $model->id, ], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить?',
                'method' => 'post',
            ],
        ]) : '';
    }

    abstract public function getModelClass();
    abstract public function getModelSearch();
    abstract public function getPermissionPrefix();

}
