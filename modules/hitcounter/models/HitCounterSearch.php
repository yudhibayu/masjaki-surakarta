<?php

namespace app\modules\hitcounter\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hitcounter\models\HitCounter;

/**
 * HitCounterSearch represents the model behind the search form of `app\modules\hitcounter\models\HitCounter`.
 */
class HitCounterSearch extends HitCounter
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'js_cookei_enabled', 'js_java_enabled', 'js_timezone_offset', 'js_screen_width', 'js_screen_height', 'js_color_depth', 'js_history_length', 'js_is_toutch_device', 'js_processor_ram', 'serv_auth_user_id', 'serv_port', 'serv_is_proxy_or_vpn'], 'integer'],
            [['counter_id', 'cookie_mark', 'js_timezone', 'js_connection', 'js_current_url', 'js_referer_url', 'js_browser_language', 'serv_ip', 'serv_user_agent', 'serv_referer_url', 'serv_server_name', 'serv_cookies', 'serv_os', 'serv_client', 'serv_device', 'serv_brand', 'serv_model', 'serv_bot', 'serv_host_by_ip', 'created_at'], 'safe'],
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
        $query = HitCounter::find();
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
            'js_cookei_enabled' => $this->js_cookei_enabled,
            'js_java_enabled' => $this->js_java_enabled,
            'js_timezone_offset' => $this->js_timezone_offset,
            'js_screen_width' => $this->js_screen_width,
            'js_screen_height' => $this->js_screen_height,
            'js_color_depth' => $this->js_color_depth,
            'js_history_length' => $this->js_history_length,
            'js_is_toutch_device' => $this->js_is_toutch_device,
            'js_processor_ram' => $this->js_processor_ram,
            'serv_auth_user_id' => $this->serv_auth_user_id,
            'serv_port' => $this->serv_port,
            'serv_is_proxy_or_vpn' => $this->serv_is_proxy_or_vpn,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'counter_id', $this->counter_id])
            ->andFilterWhere(['like', 'cookie_mark', $this->cookie_mark])
            ->andFilterWhere(['like', 'js_timezone', $this->js_timezone])
            ->andFilterWhere(['like', 'js_connection', $this->js_connection])
            ->andFilterWhere(['like', 'js_current_url', $this->js_current_url])
            ->andFilterWhere(['like', 'js_referer_url', $this->js_referer_url])
            ->andFilterWhere(['like', 'js_browser_language', $this->js_browser_language])
            ->andFilterWhere(['like', 'serv_ip', $this->serv_ip])
            ->andFilterWhere(['like', 'serv_user_agent', $this->serv_user_agent])
            ->andFilterWhere(['like', 'serv_referer_url', $this->serv_referer_url])
            ->andFilterWhere(['like', 'serv_server_name', $this->serv_server_name])
            ->andFilterWhere(['like', 'serv_cookies', $this->serv_cookies])
            ->andFilterWhere(['like', 'serv_os', $this->serv_os])
            ->andFilterWhere(['like', 'serv_client', $this->serv_client])
            ->andFilterWhere(['like', 'serv_device', $this->serv_device])
            ->andFilterWhere(['like', 'serv_brand', $this->serv_brand])
            ->andFilterWhere(['like', 'serv_model', $this->serv_model])
            ->andFilterWhere(['like', 'serv_bot', $this->serv_bot])
            ->andFilterWhere(['like', 'serv_host_by_ip', $this->serv_host_by_ip]);

        return $dataProvider;
    }
}
