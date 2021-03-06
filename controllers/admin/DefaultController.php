<?php

namespace panix\mod\markup\controllers\admin;

use panix\engine\CMS;
use Yii;
use panix\engine\controllers\AdminController;
use panix\mod\markup\models\Markup;
use panix\mod\markup\models\MarkupSearch;

class DefaultController extends AdminController
{
    public function actions()
    {
        return [
            'switch' => [
                'class' => \panix\engine\actions\SwitchAction::class,
                'modelClass' => Markup::class,
            ],
            'delete' => [
                'class' => \panix\engine\actions\DeleteAction::class,
                'modelClass' => Markup::class,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->pageName = Yii::t('markup/default', 'MODULE_NAME');


        $this->view->params['breadcrumbs'][] = [
            'label' => Yii::t('shop/default', 'MODULE_NAME'),
            'url' => ['/admin/shop']
        ];
        $this->view->params['breadcrumbs'][] = $this->pageName;
        if (Yii::$app->user->can("/{$this->module->id}/{$this->id}/*") ||  Yii::$app->user->can("/{$this->module->id}/{$this->id}/create")) {
            $this->buttons = [
                [
                    'icon' => 'add',
                    'label' => Yii::t('markup/default', 'CREATE_DISCOUNT'),
                    'url' => ['create'],
                    'options' => ['class' => 'btn btn-success']
                ]
            ];
        }
        $searchModel = new MarkupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionUpdate($id = false)
    {
        $model = Markup::findModel($id, Yii::t('markup/default', 'NO_FOUND_MARKUP'));


        $this->pageName = Yii::t('markup/default', ($model->isNewRecord) ? 'CREATE':'UPDATE');


        $this->view->params['breadcrumbs'][] = [
            'label' => Yii::t('shop/default', 'MODULE_NAME'),
            'url' => ['/admin/shop']
        ];
        $this->view->params['breadcrumbs'][] = [
            'label' => Yii::t('markup/default', 'MODULE_NAME'),
            'url' => ['index']
        ];
        $this->view->params['breadcrumbs'][] = $this->pageName;
        \panix\mod\markup\MarkupAsset::register($this->view);

        $post = Yii::$app->request->post();
        $isNew = $model->isNewRecord;
        if ($model->load($post)) {
            if (!isset($post['Markup']['manufacturers'])) {
                $model->manufacturers = [];
            }
            if (!isset($post['Markup']['categories']))
                $model->categories = [];

            if (!isset($post['Markup']['suppliers']))
                $model->suppliers = [];
            //if (!isset($post['Markup']['userRoles']))
            //    $model->userRoles = [];


            if ($model->validate()) {
                $model->save();
                return $this->redirectPage($isNew, $post);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionCreate()
    {
        return $this->actionUpdate(false);
    }
}
