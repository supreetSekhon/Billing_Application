<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_status_values".
 *
 * @property int $id
 * @property string $status_name
 * @property string|null $icon
 * @property string|null $class
 * @property string $created_at
 * @property int|null $created_by
 * @property string $ip
 * @property int $status
 * @property string $updated_at
 * @property int|null $updated_by
 */
class InvoiceStatusValues extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_status_values';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'status', 'updated_by'], 'integer'],
            [['status_name', 'class'], 'string', 'max' => 50],
            [['icon'], 'string', 'max' => 100],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_name' => 'Status Name',
            'icon' => 'Icon',
            'class' => 'Class',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'ip' => 'Ip',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
