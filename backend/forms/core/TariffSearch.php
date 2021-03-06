<?php

namespace backend\forms\core;

use core\entities\Core\CategoryTariffs;
use core\helpers\TariffHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\Tariff;

/**
 * TariffSearch represents the model behind the search form of `core\entities\Core\Tariff`.
 */
class TariffSearch extends Tariff
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'number', 'status', 'category_id'], 'integer'],
            [['name', 'qty_proxy',], 'safe'],
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
        return TariffHelper::statusList();
    }

    public function categoryList()
    {
        return TariffHelper::categoryList();
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
        $query = Tariff::find()->joinWith(['category']);;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['category_id'] = [
            'asc' => [CategoryTariffs::tableName().'.name' => SORT_ASC],
            'desc' => [CategoryTariffs::tableName().'.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'price' => $this->price,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', Tariff::tableName().'.name', $this->name])
            ->andFilterWhere(['like', 'qty_proxy', $this->name]);

        return $dataProvider;
    }
}
