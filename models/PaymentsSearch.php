<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payments;

/**
 * PaymentsSearch represents the model behind the search form of `app\models\Payments`.
 */
class PaymentsSearch extends Payments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_invoice_id', 'fk_method_id', 'created_by', 'updated_by'], 'integer'],
            [['payment_date', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Payments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fk_invoice_id' => $this->fk_invoice_id,
            'payment_date' => $this->payment_date,
            'amount' => $this->amount,
            'fk_method_id' => $this->fk_method_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
