<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proposals".
 *
 * @property int $id
 * @property int|null $fk_client_id
 * @property int|null $fk_user_id
 * @property string $proposal_text
 * @property int|null $fk_status_id
 * @property string $created_at
 * @property int|null $created_by
 * @property string $ip
 * @property int $status
 * @property string $updated_at
 * @property int|null $updated_by
 *
 * @property ESignatures[] $eSignatures
 * @property Clients $fkClient
 * @property StatusValues $fkStatus
 * @property Users $fkUser
 */
class Proposals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proposals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_client_id', 'fk_user_id', 'fk_status_id', 'created_by', 'status', 'updated_by'], 'integer'],
            [['proposal_text'], 'required'],
            [['proposal_text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['ip'], 'string', 'max' => 20],
            [['fk_client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::class, 'targetAttribute' => ['fk_client_id' => 'id']],
            [['fk_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['fk_user_id' => 'id']],
            [['fk_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusValues::class, 'targetAttribute' => ['fk_status_id' => 'id']],
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
            'fk_client_id' => 'Client',
            'fk_user_id' => 'Prepared By',
            'proposal_text' => 'Proposal Text',
            'fk_status_id' => 'Status',
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
        return $this->hasMany(ESignatures::class, ['fk_proposal_id' => 'id']);
    }

    /**
     * Gets query for [[FkClient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkClient()
    {
        return $this->hasOne(Clients::class, ['id' => 'fk_client_id']);
    }

    /**
     * Gets query for [[FkStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkStatus()
    {
        return $this->hasOne(StatusValues::class, ['id' => 'fk_status_id']);
    }

    /**
     * Gets query for [[FkUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkUser()
    {
        return $this->hasOne(Users::class, ['id' => 'fk_user_id']);
    }
}
