<?php 

namespace crud\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
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

    public function getActionColumn()
    {
        return [
            'class' => 'yii\grid\ActionColumn',
            'buttons' => [
                'view' => function ($url, $model, $key){
                    return Yii::$app->user->can($this->getPermissionPrefix() . 'View') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url) : '';
                },
                'update' => function ($url, $model, $key){
                    return Yii::$app->user->can($this->getPermissionPrefix() . 'Update') ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url) : '';
                },
                'delete' => function ($url, $model, $key){
                    return ((!$model->hasMethod('getHasActiveRelations') || !$model->hasActiveRelations) && Yii::$app->user->can($this->getPermissionPrefix() . 'Delete')) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'data' => [
                            'confirm' => 'Точно удалить?',
                            'method' => 'post',
                        ],
                    ]) : '';
                },
            ],
        ];
    }

    public function getCreateButton(string $title = 'Создать')
    {
        return Yii::$app->user->can($this->getPermissionPrefix() . 'Create') ? Html::a($title, ['create'], ['class' => 'btn btn-success']) : NULL;
    }

    public function getUpdateButton($model, string $title = 'Редактировать')
    {
        return Yii::$app->user->can($this->getPermissionPrefix() . 'Update') ? Html::a($title, ['update', 'id' => $model->id], ['class' => 'btn btn-success', ]) : NULL;
    }

    public function getDeleteButton($model, string $title = 'Удалить')
    {
        return ((!$model->hasMethod('getHasActiveRelations') || !$model->hasActiveRelations) && Yii::$app->user->can($this->getPermissionPrefix() . 'Delete')) ? Html::a($title, ['delete', 'id' => $model->id, ], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить?',
                'method' => 'post',
            ],
        ]) : NULL;
    }

    abstract public function getModelClass();
    abstract public function getModelSearch();
    abstract public function getPermissionPrefix();

}
