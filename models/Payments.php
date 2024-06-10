<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int|null $fk_invoice_id
 * @property string $payment_date
 * @property float $amount
 * @property int $fk_method_id
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Invoices $fkInvoice
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_invoice_id', 'fk_method_id', 'created_by', 'updated_by'], 'integer'],
            [['payment_date', 'amount', 'fk_method_id', 'created_by'], 'required'],
            [['payment_date', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
            [['fk_invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoices::class, 'targetAttribute' => ['fk_invoice_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_invoice_id' => 'Fk Invoice ID',
            'payment_date' => 'Payment Date',
            'amount' => 'Amount',
            'fk_method_id' => 'Fk Method ID',
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
}
