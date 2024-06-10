<?php

namespace app\controllers;
use Yii;
use app\models\Invoices;
use app\models\InvoiceItems;
use app\models\InvoicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceController implements the CRUD actions for Invoices model.
 */
class InvoiceController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TblInvoice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InvoicesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoices model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Invoices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Invoices();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->total_amount = $_POST['total_value'];
                if($model->save()){
                  if(isset($_POST['product'])){
                    $product = $_POST['product'];
                  }else{
                    $product = [];
                  }
                  if(isset($_POST['qty'])){
                    $qty = $_POST['qty'];
                  }else{
                    $qty = [];
                  }
                  if(isset($_POST['price'])){
                    $rate = $_POST['price'];
                  }else{
                    $rate = [];
                  }
                  if(isset($_POST['total'])){
                    $amount = $_POST['total'];
                  }else{
                    $amount = [];
                  }
                  if(isset($_POST['tax'])){
                    $tax = $_POST['tax'];
                  }else{
                    $tax = [];
                  }
                  $item_count = $_POST['item_count'];
                  for($i=0;$i<$item_count;$i++){
                    if(isset($product[$i]) && $product[$i] != "" && $qty[$i] != "" && $rate[$i] != "" && $amount[$i] != "" && $tax[$i] != ""){
                      $modelItem[$i] = new InvoiceItems();
                      $modelItem[$i]->fk_invoice_id = $model->id;
                      $modelItem[$i]->fk_service_id = $product[$i];
                      $modelItem[$i]->quantity = $qty[$i];
                      $modelItem[$i]->unit_price = $rate[$i];
                      $modelItem[$i]->total_price = $amount[$i];
                      $modelItem[$i]->sales_tax = $tax[$i];
                      if($modelItem[$i]->save()){

                      }else{
                        // print_r($modelItem[$i]->getErrors());
                        // die();
                      }
                    }
                  }//for loop ended
                  if(isset($_POST['new_view'])){
                    return $this->redirect(['view','id' => $model->id]);
                  }else if(isset($_POST['new_exit'])){
                    return $this->redirect(['index']);
                  }else{
                    return $this->redirect(['index']);
                  }
                }else{
                  //--------model not saved -------
                  return $this->render('create', [
                    'model' => $model,
                  ]);
                }


            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Invoices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            // return $this->redirect(['view', 'id' => $model->id]);
            $model->total_amount = $_POST['total_value'];
            if($model->save()){
              InvoiceItems::updateAll(['status' => 0], ['fk_invoice_id'=>$model->id]);
              if(isset($_POST['product'])){
                $product = $_POST['product'];
              }else{
                $product = [];
              }
              if(isset($_POST['qty'])){
                $qty = $_POST['qty'];
              }else{
                $qty = [];
              }
              if(isset($_POST['price'])){
                $rate = $_POST['price'];
              }else{
                $rate = [];
              }
              if(isset($_POST['total'])){
                $amount = $_POST['total'];
              }else{
                $amount = [];
              }
              if(isset($_POST['tax'])){
                $tax = $_POST['tax'];
              }else{
                $tax = [];
              }
              $item_count = $_POST['item_count'];
              for($i=0;$i<$item_count;$i++){
                if(isset($product[$i]) && $product[$i] != "" && $qty[$i] != "" && $rate[$i] != "" && $amount[$i] != "" && $tax[$i] != ""){
                  $modelItem[$i] = new InvoiceItems();
                  $modelItem[$i]->fk_invoice_id = $model->id;
                  $modelItem[$i]->fk_service_id = $product[$i];
                  $modelItem[$i]->quantity = $qty[$i];
                  $modelItem[$i]->unit_price = $rate[$i];
                  $modelItem[$i]->total_price = $amount[$i];
                  $modelItem[$i]->sales_tax = $tax[$i];
                  if($modelItem[$i]->save()){

                  }else{
                    // print_r($modelItem[$i]->getErrors());
                    // die();
                  }
                }
              }//for loop ended
              if(isset($_POST['view'])){
                return $this->redirect(['view','id' => $model->id]);
              }else if(isset($_POST['exit'])){
                return $this->redirect(['index']);
              }else{
                return $this->redirect(['index']);
              }
            }else{
              //--------model not saved -------
              return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblInvoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Invoices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Invoices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoices::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
