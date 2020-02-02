<?php

namespace backend\forms\core;

use core\helpers\CurrencyHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\Currency;

/**
 * CurrencySearch represents the model behind the search form of `core\entities\Core\Currency`.
 */
class CurrencySearch extends Currency
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'active', 'base'], 'integer'],
            [['code'], 'safe'],
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

    public function statusList(): array
    {
        return CurrencyHelper::statusList();
    }

    public function baseList(): array
    {
        return CurrencyHelper::baseList();
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
        $query = Currency::find();

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
            'active' => $this->active,
            'base' => $this->base,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
