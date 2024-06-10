<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property string $created_at
 * @property int|null $created_by
 * @property string $ip
 * @property int $status
 * @property string $updated_at
 * @property int|null $updated_by
 *
 * @property ESignatures[] $eSignatures
 * @property Proposals[] $proposals
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'status', 'updated_by'], 'integer'],
            [['name', 'email'], 'string', 'max' => 100],
            [['phone', 'ip'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }
    public function beforeSave($insert) {
        $session = Yii::$app -> session;
        if ($insert) {
          $this -> ip = Yii::$app -> getRequest() -> getUserIp();
          $this -> created_by = $session -> get('userId');
          $this -> created_at = date('Y-m-d H:i:s');
        } else {
          $this -> ip = Yii::$app -> getRequest() -> getUserIp();
          $this -> updated_by = $session -> get('userId');
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
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'ip' => 'Ip',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[ESignatures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getESignatures()
    {
        return $this->hasMany(ESignatures::class, ['fk_client_id' => 'id']);
    }

    /**
     * Gets query for [[Proposals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProposals()
    {
        return $this->hasMany(Proposals::class, ['fk_client_id' => 'id']);
    }
}
