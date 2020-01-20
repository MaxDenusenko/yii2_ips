<?php

namespace backend\controllers;

use core\forms\manage\Core\MenuForm;
use core\forms\manage\Core\MenuFormItem;
use core\services\manage\Core\MenuManageService;
use Yii;
use core\entities\Core\Menu;
use backend\forms\MenuSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    private $service;

    public function __construct($id, $module, MenuManageService $service,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'deleteRootNode' => ['POST'],
                    'deleteRootItem' => ['POST'],
                    'moveUp' => ['POST'],
                    'moveDown' => ['POST'],
                ],
            ],
        ];
    }

    public function actionMoveUp($root_id, $id)
    {
        $this->service->moveUp($id);
        return $this->redirect(['menu/view-root-node', 'id' => $root_id]);
    }

    public function actionMoveDown($root_id, $id)
    {
        $this->service->moveDown($id);
        return $this->redirect(['menu/view-root-node', 'id' => $root_id]);
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndexRootNode()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('root/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewRootNode($id)
    {
        $menu = $this->findModel($id);

        $modificationsProvider = new ActiveDataProvider([
            'query' => $menu->find()->andWhere(['>', 'depth', 0])->andWhere(['=', 'tree', $id]),
            'sort' => [
                'defaultOrder' => ['lft' => SORT_ASC]
            ],
        ]);

        return $this->render('root/view', [
            'root' => $menu,
            'dataProvider' => $modificationsProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param $root_id
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewRootItem($root_id, $id)
    {
        $item = $this->findModel($id);
        $root = $this->findModel($root_id);
        $parent = $item->parent;

        return $this->render('items/view', [
            'root' => $root,
            'item' => $item,
            'parent' => $parent,
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateRootNode()
    {
        $form = new MenuForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $menu = $this->service->createRootNode($form);
                return $this->redirect(['view-root-node', 'id' => $menu->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('root/create', [
            'model' => $form,
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $root_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreateRootItem($root_id)
    {
        $form = new MenuFormItem();
        $root = $this->findModel($root_id);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->addItem($root->id, $form);
                return $this->redirect(['menu/view-root-node', 'id' => $root->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('items/create', [
            'model' => $form,
            'root' => $root,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateRootNode($id)
    {
        $menu = $this->findModel($id);

        $form = new MenuForm($menu);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editRootNode($menu->id, $form);
                return $this->redirect(['view-root-node', 'id' => $menu->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('root/update', [
            'model' => $form,
            'menu' => $menu,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $root_id
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateRootItem($root_id, $id)
    {
        $menu = $this->findModel($id);
        $root = $this->findModel($root_id);

        $form = new MenuFormItem($menu);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editItem($menu->id, $form);
                return $this->redirect(['view-root-node', 'id' => $root_id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('items/update', [
            'model' => $form,
            'menu' => $menu,
            'root' => $root,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteRootNode($id)
    {
        $this->findModel($id)->deleteWithChildren();

        return $this->redirect(['index-root-node']);
    }

    public function actionDeleteRootItem($root_id, $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['view-root-node', 'id' => $root_id]);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
