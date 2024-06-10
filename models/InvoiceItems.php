<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_invoice_item".
 *
 * @property int $id
 * @property int $fk_invoice_id
 * @property int $fk_service_id
 * @property int $quantity
 * @property float $unit_price
 * @property float $total_price
 * @property int|null $status
 * @property string|null $ip
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Invoices $fkInvoice
 * @property Services $fkService
 */
class InvoiceItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_invoice_id', 'fk_service_id', 'quantity', 'unit_price', 'total_price'], 'required'],
            [['fk_invoice_id', 'fk_service_id', 'quantity', 'status', 'created_by', 'updated_by'], 'integer'],
            [['unit_price', 'total_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['ip'], 'string', 'max' => 20],
            [['fk_invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoices::class, 'targetAttribute' => ['fk_invoice_id' => 'id']],
            [['fk_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Services::class, 'targetAttribute' => ['fk_service_id' => 'id']],
        ];
    }
    public function beforeSave($insert) {
        $session = Yii::$app -> session;
        if ($insert) {
          $this -> ip = Yii::$app -> getRequest() -> getUserIp();
          $this -> created_by = $session -> get('id');
          $this -> created_at = date('Y-m-d H:i:s');
        } else {
          $this -> ip = Yii::$app -> getRequest() -> getUserIp();
          $this -> updated_by = $session -> get('id');
          $this -> updated_at = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_invoice_id' => 'Fk Invoice ID',
            'fk_service_id' => 'Fk Service ID',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'total_price' => 'Total Price',
            'status' => 'Status',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[FkInvoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkInvoice()
    {
        return $this->hasOne(Invoices::class, ['id' => 'fk_invoice_id']);
    }

    /**
     * Gets query for [[FkProduct]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkService()
    {
        return $this->hasOne(Services::class, ['id' => 'fk_service_id']);
    }
}
