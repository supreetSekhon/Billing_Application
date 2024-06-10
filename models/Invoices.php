<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $fk_customer_id
 * @property string $invoice_date
 * @property string $due_date
 * @property float $total_amount
 * @property string|null $comments
 * @property int|null $status
 * @property string|null $ip
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Customers $fkCustomer
 * @property InvoiceItems[] $invoiceItems
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_customer_id', 'invoice_date', 'due_date', 'total_amount'], 'required'],
            [['fk_customer_id', 'status', 'created_by', 'updated_by','discount_type','invoice_number'], 'integer'],
            [['invoice_date', 'due_date', 'created_at', 'updated_at','discount_value'], 'safe'],
            [['total_amount'], 'number'],
            [['comments'], 'string'],
            [['ip'], 'string', 'max' => 20],
            [['fk_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::class, 'targetAttribute' => ['fk_customer_id' => 'id']],
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
            'fk_customer_id' => 'Fk Customer ID',
            'invoice_date' => 'Invoice Date',
            'due_date' => 'Due Date',
            'total_amount' => 'Total Amount',
            'comments' => 'Comments',
            'status' => 'Status',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[FkCustomer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkCustomer()
    {
        return $this->hasOne(Clients::class, ['id' => 'fk_customer_id']);
    }

    /**
     * Gets query for [[TblInvoiceItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceItems()
    {
        return $this->hasMany(InvoiceItems::class, ['fk_invoice_id' => 'id']);
    }
}
