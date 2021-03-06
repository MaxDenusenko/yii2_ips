<?php

namespace backend\forms\core;

use core\helpers\TariffDefaultsHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\TariffDefaults;

/**
 * TariffDefaultsSearch represents the model behind the search form of `core\entities\Core\TariffDefaults`.
 */
class TariffDefaultsSearch extends TariffDefaults
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mb_limit', 'quantity_incoming_traffic', 'quantity_outgoing_traffic',
                'ip_quantity', 'type', 'extend_days', 'extend_hours', 'extend_minutes'], 'integer'],
        ];
    }

    public function statusList(): array
    {
        return TariffDefaultsHelper::statusList();
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
        $query = TariffDefaults::find();

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
            'mb_limit' => $this->mb_limit,
            'quantity_incoming_traffic' => $this->quantity_incoming_traffic,
            'quantity_outgoing_traffic' => $this->quantity_outgoing_traffic,
            'ip_quantity' => $this->ip_quantity,
            'type' => $this->type,
            'extend_days' => $this->extend_days,
            'extend_hours' => $this->extend_hours,
            'extend_minutes' => $this->extend_minutes,
        ]);

        return $dataProvider;
    }
}
