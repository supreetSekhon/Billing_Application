<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Clients;
use app\models\Services;
use app\models\TaxRate;
use app\models\Invoices;
use app\models\InvoiceItems;
/** @var yii\web\View $this */
/** @var app\models\Invoices $model */
/** @var yii\widgets\ActiveForm $form */
$allcustomers = Clients::find()->where(['status'=>1])->all();
$customerarr = ArrayHelper::map($allcustomers,'id','name');
$discountarr = [1=>'Discount Value',2=>'Discount Percentage'];
if($model->isNewRecord){
  $model->invoice_date = date('Y-m-d');
  $model->due_date = date('Y-m-d');
  $model->comments = "We appreciate your business and look forward to helping you again soon.";
  $model->discount_value = number_format(0,2,'.','');

	$get = Invoices::find()->where(['status'=>1])->orderBy(['id'=>SORT_DESC])->one();
  if(isset($get) && $get->invoice_number < 2000){
    $invoice_no = 1001;
  	$new_number = sprintf('%04d', $invoice_no);
  }else if(isset($get) && $get->id != ""){
    $invoice_no = intval($get->invoice_number)+1;
    $new_number = sprintf('%04d', $invoice_no);
  }else{
    $new_number = 1001;
  }

	$model->invoice_number = $new_number;
}
?>

<div class="tbl-invoice-form">
  <div id="msg">
    <?php
    if(Yii::$app -> session -> getFlash('success')!=null){
      ?>
      <div class="alert alert-primary alert-dismissable mb-3">
        <?= Yii::$app -> session -> getFlash('success'); ?>
      </div>
      <?php
    }else if(Yii::$app -> session -> getFlash('error')!=null){
      ?>
      <div class="alert alert-danger alert-dismissable mb-3">
        <?= Yii::$app -> session -> getFlash('error'); ?>
      </div>
      <?php
    }
    ?>
  </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="card shadow rounded mt-3 mb-3">
      <h5 class="card-header bg-light">
        <div class="row">
          <div class="col-lg-6">
            Basic Details&nbsp;<span style="color:#f00;font-size:12px;">* fields are required to save the data.</span>
          </div>
          <div class="col-lg-6 text-end">
            <i class="fa fa-info-circle" data-bs-toggle="modal" data-bs-target="#basicModal"></i>
          </div>
        </div>
      </h5>
      <div class="card-body border-top">
        <div class="row">
          <div class="col-lg-3">
            <?= $form->field($model, 'invoice_number')->textInput(['readonly'=>true]) ?>
          </div>
          <div class="col-lg-3">
            <?= $form->field($model, 'fk_customer_id')->dropDownList($customerarr,['prompt'=>'Select'])->label('Customer') ?>
          </div>
          <div class="col-lg-3">
            <?= $form->field($model, 'invoice_date')->textInput() ?>
          </div>
          <div class="col-lg-3">
            <?= $form->field($model, 'due_date')->textInput() ?>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow rounded mt-3 mb-3">
      <h5 class="card-header bg-light">
        <div class="row">
          <div class="col-lg-6">
            Service Details&nbsp;<span style="color:#f00;font-size:12px;">* fields are required to save the data.</span>
          </div>
          <div class="col-lg-6 text-end">
            <i class="fa fa-info-circle" data-bs-toggle="modal" data-bs-target="#basicModal"></i>
          </div>
        </div>
      </h5>
      <div class="card-body border-top">
        <?php
          $discount_value = 0;
          $discount_value = 0;
          $discount_percentage = 0;
          $total_qty = 0;
          $taxesData = [];
          $subtotal = 0;
          $subtotal_0 = 0;
          $total_taxes = 0;
          $sub_total = 0;
          $subtotal_13 = 0;
          $hst_13 = 0;
          $subtotal = 0;
          $total = 0;
          if(!$model->isNewRecord){
            $getitems = InvoiceItems::find()->where(['fk_invoice_id'=>$model->id])->andWhere(['status'=>1])->all();
            if(isset($getitems) && count($getitems) > 0){
              $i = 1;
              $item_count = count($getitems);
              foreach($getitems as $gt){
                $total_qty += $gt->quantity;
                $subtotal += $gt->total_price;
                if($i == 1){
                  ?>
                  <div class="row">
                    <div class="col-lg-3">
                      <label for="">Service</label>
                      <select class="form-control product_select" name="product[]" id="product_<?=$i?>">
                        <option value="">Select</option>
                        <?php
                          $getallservices = Services::find()->where(['status'=>1])->all();
                          if(isset($getallservices) && count($getallservices) > 0){
                            foreach($getallservices as $gp){
                              if($gt->fk_service_id == $gp->id){
                                  echo '<option value="'.$gp->id.'" selected="selected">'.$gp->title.'</option>';
                              }else{
                                  echo '<option value="'.$gp->id.'">'.$gp->title.'</option>';
                              }
                            }//----for loop ended -----
                          }//------if isset ended -----
                         ?>
                      </select>
                    </div>
                    <div class="col-lg-1">
                      <label for="">Qty</label>
                      <input type="text" name="qty[]" class="form-control qty" id="qty_<?=$i?>" value="<?=$gt->quantity?>">
                    </div>
                    <div class="col-lg-1">
                      <label for="">Price</label>
                      <input type="text" name="price[]" class="form-control rate" id="rate_<?=$i?>" value="<?=$gt->unit_price?>">
                    </div>
                    <div class="col-lg-1">
                      <label for="">Amount</label>
                      <input type="text" name="total[]" class="form-control amount" readonly id="amount_<?=$i?>" value="<?=$gt->total_price?>">
                    </div>
                    <div class="col-lg-2">
                      <label for="">Tax Rate</label>
                      <select class="form-control tax" name="tax[]" id="tax_<?=$i?>">
                        <?php
                        $getalltaxrates = TaxRate::find()->where(['status'=>1])->all();
                        if(isset($getalltaxrates) && count($getalltaxrates) > 0){
                          foreach($getalltaxrates as $ga){
                            if($gt->sales_tax == $ga->id){
                              echo '<option value="'.$ga->id.'" selected="selected">'.$ga->tax_rate.'% ['.$ga->province_state.']</option>';
                            }else{
                              echo '<option value="'.$ga->id.'">'.$ga->tax_rate.'% ['.$ga->province_state.']</option>';
                            }
                          }//for loop ended
                        }//if isset ended
                         ?>
                      </select>
                    </div>
                    <div class="col-lg-1 mt-4">
                      <button type="button" name="button" class="btn btn-primary btn-sm btn_add_row">
                        <i class="fas fa-plus"></i>
                      </button>
                      <button type="button" name="button" class="btn btn-primary btn-sm btn_remove_row">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <?php
                }else{
                  //-----any other record
                  ?>
                  <div class="row row_extra mt-3">
                    <div class="col-lg-3">
                      <select class="form-control product_select" name="product[]" id="product_<?=$i?>">
                        <option value="">Select</option>
                        <?php
                          $getallservices = Services::find()->where(['status'=>1])->all();
                          if(isset($getallservices) && count($getallservices) > 0){
                            foreach($getallservices as $gp){
                              if($gt->fk_service_id == $gp->id){
                                  echo '<option value="'.$gp->id.'" selected="selected">'.$gp->title.'</option>';
                              }else{
                                  echo '<option value="'.$gp->id.'">'.$gp->title.'</option>';
                              }
                            }//----for loop ended -----
                          }//------if isset ended -----
                         ?>
                      </select>
                    </div>
                    <div class="col-lg-1">
                      <input type="text" name="qty[]" class="form-control qty" id="qty_<?=$i?>" value="<?=$gt->quantity?>">
                    </div>
                    <div class="col-lg-1">
                      <input type="text" name="price[]" class="form-control rate" id="rate_<?=$i?>" value="<?=$gt->unit_price?>">
                    </div>
                    <div class="col-lg-1">
                      <input type="text" name="total[]" class="form-control amount" readonly id="amount_<?=$i?>" value="<?=$gt->total_price?>">
                    </div>
                    <div class="col-lg-2">
                      <select class="form-control tax" name="tax[]" id="tax_<?=$i?>">
                        <?php
                        $getalltaxrates = TaxRate::find()->where(['status'=>1])->all();
                        if(isset($getalltaxrates) && count($getalltaxrates) > 0){
                          foreach($getalltaxrates as $ga){
                            if($gt->sales_tax == $ga->id){
                              echo '<option value="'.$ga->id.'" selected="selected">'.$ga->tax_rate.'% ['.$ga->province_state.']</option>';
                            }else{
                              echo '<option value="'.$ga->id.'">'.$ga->tax_rate.'% ['.$ga->province_state.']</option>';
                            }
                          }//for loop ended
                        }//if isset ended
                         ?>
                      </select>
                    </div>
                    <div class="col-lg-1">
                      <button type="button" name="button" class="btn btn-primary btn-sm btn_add_row">
                        <i class="fas fa-plus"></i>
                      </button>
                      <button type="button" name="button" class="btn btn-primary btn-sm btn_remove_row">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <?php
                }
                $i++;

              }//------for loop ended
              if($model->discount_type != "" && $model->discount_value != ""){
                if($model->discount_type == 1 && $subtotal > 0){
                  $discount_percentage = $model->discount_value*(100/$subtotal);
                }else{
                  $discount_percentage = $model->discount_value;
                }
              }
              foreach($getitems as $gt){
                $amount = $gt->total_price;
                $discount_value += ($discount_percentage/100)*$gt->total_price;
                $new_amount = $amount-(($discount_percentage/100)*$gt->total_price);
                $sub_total += $new_amount;
                $gettaxrate = TaxRate::find()->where(['id'=>$gt->sales_tax,'status'=>1])->one();
                if(isset($gettaxrate) && $gettaxrate->tax_rate != ""){
                  $taxPercentage = $gettaxrate->tax_rate;
                }else{
                  $taxPercentage = 13.00;
                }
                if($taxPercentage > 0){
                  $calc_hst = $new_amount*($taxPercentage/100);
                  $total_taxes += $calc_hst;
                }

                if (isset($taxesData[$taxPercentage])) {
                  $taxesData[$taxPercentage]['subtotal'] += $new_amount;
                } else {
                  $taxesData[$taxPercentage] = [
                    'subtotal' => $new_amount,
                    'taxAmount' => 0
                  ];
                }
              }
              foreach ($taxesData as $taxPercentage => $taxData) {
                $taxAmount = $taxData['subtotal'] * ($taxPercentage / 100);
                $taxesData[$taxPercentage]['taxAmount'] = $taxAmount;
              }
              // $total = $subtotal+$hst_13+$hst_5-$discount_value;
              $total = $subtotal+$total_taxes-$discount_value;
              ?>
              <input type="hidden" id="item_count" name="item_count" value="<?=$item_count?>">
              <?php
            }else{
              //--------no record found ----------
              ?>
              <div class="row">
                <div class="col-lg-3">
                  <label for="">Service</label>
                  <select class="form-control product_select" name="product[]" id="product_1">
                    <option value="">Select</option>
                    <?php
                      $getallservices = Services::find()->where(['status'=>1])->all();
                      if(isset($getallservices) && count($getallservices) > 0){
                        foreach($getallservices as $gp){
                          echo '<option value="'.$gp->id.'">'.$gp->title.'</option>';
                        }//----for loop ended -----
                      }//------if isset ended -----
                     ?>
                  </select>
                </div>
                <div class="col-lg-1">
                  <label for="">Qty</label>
                  <input type="text" name="qty[]" class="form-control qty" id="qty_1">
                </div>
                <div class="col-lg-1">
                  <label for="">Price</label>
                  <input type="text" name="price[]" class="form-control rate" id="rate_1">
                </div>
                <div class="col-lg-1">
                  <label for="">Amount</label>
                  <input type="text" name="total[]" class="form-control amount" readonly id="amount_1">
                </div>
                <div class="col-lg-2">
                  <label for="">Tax Rate</label>
                  <select class="form-control tax" name="tax[]" id="tax_1">
                    <?php
                    $getalltaxrates = TaxRate::find()->where(['status'=>1])->all();
                    if(isset($getalltaxrates) && count($getalltaxrates) > 0){
                      foreach($getalltaxrates as $ga){
                          echo '<option value="'.$ga->id.'">'.$ga->tax_rate.'% ['.$ga->province_state.']</option>';
                      }//for loop ended
                    }//if isset ended
                     ?>
                  </select>
                </div>
                <div class="col-lg-1 mt-4">
                  <button type="button" name="button" class="btn btn-primary btn-sm btn_add_row">
                    <i class="fas fa-plus"></i>
                  </button>
                  <button type="button" name="button" class="btn btn-primary btn-sm btn_remove_row">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <input type="hidden" id="item_count" name="item_count" value="1">
              <?php
            }//-------else of no record found ended
          }else{
            //---------new record ------------
            ?>
            <div class="row">
              <div class="col-lg-3">
                <label for="">Service</label>
                <select class="form-control product_select" name="product[]" id="product_1">
                  <option value="">Select</option>
                  <?php
                    $getallservices = Services::find()->where(['status'=>1])->all();
                    if(isset($getallservices) && count($getallservices) > 0){
                      foreach($getallservices as $gp){
                        echo '<option value="'.$gp->id.'">'.$gp->title.'</option>';
                      }//----for loop ended -----
                    }//------if isset ended -----
                   ?>
                </select>
              </div>
              <div class="col-lg-1">
                <label for="">Qty</label>
                <input type="text" name="qty[]" class="form-control qty" id="qty_1">
              </div>
              <div class="col-lg-1">
                <label for="">Price</label>
                <input type="text" name="price[]" class="form-control rate" id="rate_1">
              </div>
              <div class="col-lg-1">
                <label for="">Amount</label>
                <input type="text" name="total[]" class="form-control amount" readonly id="amount_1">
              </div>
              <div class="col-lg-2">
                <label for="">Tax Rate</label>
                <select class="form-control tax" name="tax[]" id="tax_1">
                  <?php
                  $getalltaxrates = TaxRate::find()->where(['status'=>1])->all();
                  if(isset($getalltaxrates) && count($getalltaxrates) > 0){
                    foreach($getalltaxrates as $ga){
                        echo '<option value="'.$ga->id.'">'.$ga->tax_rate.'% ['.$ga->province_state.']</option>';
                    }//for loop ended
                  }//if isset ended
                   ?>
                </select>
              </div>
              <div class="col-lg-1 mt-4">
                <button type="button" name="button" class="btn btn-primary btn-sm btn_add_row">
                  <i class="fas fa-plus"></i>
                </button>
                <button type="button" name="button" class="btn btn-primary btn-sm btn_remove_row">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <input type="hidden" id="item_count" name="item_count" value="1">
            <?php
          }
         ?>

        <div id="div_row_extra">

        </div>

      </div>
    </div>


        <div class="card shadow rounded mt-3 mb-3">
          <div class="card-body border-top">
            <div class="row">
              <div class="col-lg-6">
                <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>
              </div>
              <div class="col-2 mt-2">
              </div>
                <div class="col-4 mt-2 text-end">
                  <div class="row g-3" style="font-weight:600;">
                    <div class="col-8 mt-3">
                      Subtotal
                    </div>
                    <div class="col-4 mt-3" id="subtotal_calc">
                      $<?=number_format($subtotal,2,'.','')?>
                    </div>
                  </div>

                  <div class="row g-3">
                    <div class="col-6 mt-4 pe-1">
                      <?= $form->field($model, 'discount_type')->dropDownList($discountarr,['class'=>'form-select form-select-sm'])->label(false) ?>
                    </div>
                    <div class="col-2 mt-4 ps-1">
                      <?= $form->field($model, 'discount_value')->textInput(['maxlength' => true,'class'=>'form-control form-control-sm floatNumberField','onkeyup'=>"checkDec(this);"])->label(false) ?>
                    </div>
                    <div class="col-4 mt-4" id="discount_calc">
                      -$<?=number_format($discount_value,2,'.','')?>
                    </div>
                  </div>

                  <div class="row g-3" id="hst_13">
                    <?php
                    if(isset($taxesData) && count($taxesData) > 0){
                      $taxesHtml = "";
                      $taxesCalcHtml = "";
                      foreach ($taxesData as $taxPercentage => $taxData) {
                        $taxAmount = $taxData['taxAmount'];
                        $taxSubtotal = $taxData['subtotal'];
                        $taxesHtml .= "Tax @ $taxPercentage% on ". number_format($taxSubtotal, 2) . "<br>";
                        $taxesCalcHtml .= "$".number_format($taxAmount, 2) . "<br>";
                      }
                        ?>
                        <div class="col-8 mt-4" id="hst_val_13">
                          <?=$taxesHtml?>
                        </div>
                        <div class="col-4 mt-4" id="hst_calc_13">
                          <?=$taxesCalcHtml?>
                        </div>
                        <?php
                    }else{
                      ?>
                      <div class="col-8 mt-4" id="hst_val_13">
                        Tax @ 13% on <?=number_format($subtotal_13,2,'.','')?>
                      </div>
                      <div class="col-4 mt-4" id="hst_calc_13">
                        $<?=number_format($hst_13,2,'.','')?>
                      </div>
                      <?php
                    }
                     ?>

                  </div>
                  <div class="row g-3" style="font-weight:600;">
                    <div class="col-8 mt-3">
                      Total
                    </div>
                    <div class="col-4 mt-3" id="total_calc">
                      $<?=number_format($total,2,'.','')?>
                    </div>
                  </div>

                  <input type="hidden" id="subtotal_value" name="subtotal_value" value="<?=number_format($subtotal_0,2,'.','')?>"/>
                  <input type="hidden" id="total_value" name="total_value" value="<?=number_format($total,2,'.','')?>"/>

                </div>
            </div>
          </div>
        </div>


    <div class="card shadow rounded p-3 mt-4">
      <div class="row text-center">
        <?php
        if($model->isNewRecord){
          ?>
          <div class="d-grid gap-2 col-6 mx-auto pe-1">
            <input type="submit" name="new_view" value="Create & View" class="btn btn-primary btn-sm"/>
          </div>
          <div class="d-grid gap-2 col-6 mx-auto ps-1">
            <input type="submit" name="new_exit" value="Create & Exit" class="btn btn-secondary btn-sm"/>
          </div>
          <?php
        }else{
          ?>
          <div class="d-grid gap-2 col-6 mx-auto pe-1">
            <input type="submit" name="view" value="Update & View" class="btn btn-primary btn-sm"/>
          </div>
          <div class="d-grid gap-2 col-6 mx-auto ps-1">
            <input type="submit" name="exit" value="Update & Exit" class="btn btn-secondary btn-sm"/>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#invoices-invoice_date,#invoices-due_date", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    });
</script>
<?php
$product_str = "";
$taxrate_str = "";
$getallproducts = Services::find()->where(['status'=>1])->all();
if(isset($getallproducts) && count($getallproducts) > 0){
  foreach($getallproducts as $gp){
    $product_str .= "<option value='".$gp->id."'>".$gp->title."</option>";
  }//----for loop ended -----
}//------if isset ended -----

$getalltaxrates = TaxRate::find()->where(['status'=>1])->all();
if(isset($getalltaxrates) && count($getalltaxrates) > 0){
  foreach($getalltaxrates as $ga){
      $taxrate_str .= "<option value='".$ga->id."'>".$ga->tax_rate."% [".$ga->province_state."]</option>";
  }//for loop ended
}//if isset ended
  $this->registerJs('
    $(document).on("blur",".qty",function(){
      var id = $(this).attr("id");
      var index = id.indexOf("_");
      var idval = id.substring(parseInt(index)+parseInt(1));
      calculate(idval);
      calculatetotalwithtaxes();
    });
    $(document).on("blur",".rate",function(){
      var id = $(this).attr("id");
      var index = id.indexOf("_");
      var idval = id.substring(parseInt(index)+parseInt(1));
      calculate(idval);
      calculatetotalwithtaxes();
    });
    $(document).on("blur",".tax",function(){
      var id = $(this).attr("id");
      var index = id.indexOf("_");
      var idval = id.substring(parseInt(index)+parseInt(1));
      calculate(idval);
      calculatetotalwithtaxes();
    });
    $("#invoices-discount_value").keyup(function(){
      console.log("key down");
      calculatetotalwithtaxes();
    });
    $("#invoices-discount_type").change(function(){
      console.log("discount type change");
      calculatetotalwithtaxes();
    });
    function calculate(idval){
      var qty = $("#qty_"+idval).val();
      var rate = $("#rate_"+idval).val();
      console.log("::::::qty:::::"+qty+":::::rate::::::"+rate);
      if(qty != "" && rate != ""){
        var amount = qty*rate;
        $("#amount_"+idval).val(amount.toFixed(2));
      }
    }
    function calculatetotalwithtaxes(){
      var sub_total = 0;
      var total_hst = 0;
      var disc_applied = 0;
      var total_qty = 0;
      var total_taxes = 0;
      //check if there is an discount
      var discount_value = $("#invoices-discount_value").val();
      var discount_type = $("#invoices-discount_type").val();

      $(".amount").each(function(){
        quantity = parseFloat($(this).val());
        if (!isNaN(quantity) && quantity != "") {
          sub_total += quantity;
        }

      });//each for amount ended
      //got the subtotal now check if discount is a value then find out the %age
      var disc_percentage = 0;
      if(discount_type == 1 && discount_value != 0.00){
        disc_percentage = discount_value*(100/sub_total);
      }else if(discount_type == 2 && discount_value != 0.00){
        disc_percentage = discount_value;
      }else{
        disc_percentage = 0;
      }
      var taxes = {};
      $(".amount").each(function(){
        console.log("inside amount each");
        quantity = parseFloat($(this).val());
        var id = $(this).attr("id");
        var index = id.indexOf("_");
        var idval = id.substring(parseInt(index)+parseInt(1));
        var hst = $("#tax_"+idval).val();
        var qty = $("#qty_"+idval).val();
        console.log("qty::"+qty);
        if(!isNaN(qty) && qty != ""){
            total_qty = parseFloat(total_qty)+parseFloat(qty);
            disc_applied += (disc_percentage/100)*quantity;
            var new_quantity = quantity-((disc_percentage/100)*quantity);
        }else{
          var new_quantity = 0;
        }
        //now we need to get the value of HST
        var selectedOption = $("#tax_"+idval).find("option:selected");
        console.log("selected option::::::::"+selectedOption.text());
        var displayValue = selectedOption.text().match(/\d+\.\d+/);
        console.log("display value:::::::::"+displayValue);
        var taxPercentage = parseFloat(displayValue);
        if (taxes[taxPercentage]) {
           taxes[taxPercentage].subtotal += new_quantity;
         } else {
           taxes[taxPercentage] = {
             subtotal: new_quantity,
             taxAmount: 0
           };
         }

        //calculate taxes here
        var calc_hst = new_quantity*(displayValue/100);
        total_taxes += calc_hst;
      });//each for amount ended
      var total = parseFloat(sub_total)+parseFloat(total_taxes)-parseFloat(disc_applied);
      $("#subtotal_calc").html("$"+sub_total.toFixed(2));
      $("#subtotal_value").val(sub_total.toFixed(2));
      $("#total_calc").html("$"+total.toFixed(2));
      $("#total_value").val(total.toFixed(2));
      if(!isNaN(total_qty)){
          $("#total_qty").html(total_qty.toFixed(2));
      }else{
          $("#total_qty").html(0.00);
      }

      var amount_paid = $("#amount_paid").val();
      var pending_balance = parseFloat(total)-parseFloat(amount_paid);
      $("#balance_due").html("$"+pending_balance.toFixed(2));
      var taxesHtml = "";
      var taxesCalcHtml = "";
        for (var taxPercentage in taxes) {

          if (taxes.hasOwnProperty(taxPercentage)) {
            var taxData = taxes[taxPercentage];
            var taxAmount = taxData.subtotal * (parseFloat(taxPercentage) / 100);

            taxesHtml += "Tax @ " + taxPercentage + "%";
            taxesHtml += " on " + taxData.subtotal.toFixed(2) + "<br>";
            taxesCalcHtml +=  taxAmount.toFixed(2) + "<br>";
          }
        }
        $("#hst_val_13").html(taxesHtml);
        $("#hst_calc_13").html(taxesCalcHtml);
      if(discount_type == 1){
        //its a value
        $("#discount_calc").html("-$"+discount_value);
      }else{
        //its %age
        var cal = sub_total*(discount_value/100);
        $("#discount_calc").html("-$"+cal);
      }//else for discount type ended
    }
    $(document).on("click",".btn_add_row",function(r){
      r.preventDefault();
      var item_count = $("#item_count").val();
      var new_count = parseInt(item_count)+parseInt(1);
      var content = "<div class=\"row mt-3 row_extra\"><div class=\"col-lg-3\"><select class=\"form-control product_select\" name=\"product[]\" id=\"product_"+new_count+"\"><option value=\"\">Select</option>'.$product_str.'</select></div><div class=\"col-lg-1\"><input type=\"text\" name=\"qty[]\" class=\"form-control qty\" id=\"qty_"+new_count+"\"></div><div class=\"col-lg-1\"><input type=\"text\" name=\"price[]\" class=\"form-control rate\" id=\"rate_"+new_count+"\"></div><div class=\"col-lg-1\"><input type=\"text\" name=\"total[]\" class=\"form-control amount\" readonly id=\"amount_"+new_count+"\"></div><div class=\"col-lg-2\"><select class=\"form-control tax\" name=\"tax[]\" id=\"tax_"+new_count+"\">'.$taxrate_str.'</select></div><div class=\"col-lg-1\"><button type=\"button\" name=\"button\" class=\"btn btn-primary btn-sm btn_add_row\"><i class=\"fas fa-plus\"></i></button>&nbsp;<button type=\"button\" name=\"button\" class=\"btn btn-primary btn-sm btn_remove_row\"><i class=\"fas fa-minus\"></i></button></div></div>";
      $("#item_count").val(new_count);
      $("#div_row_extra").append(content);
    });
    $(document).on("click",".btn_remove_row",function(r){
      r.preventDefault();
      $(this).parent().parent(".row_extra").remove();
    });
  ');
 ?>
