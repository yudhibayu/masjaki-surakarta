<?php

namespace app\overrides\hitcounter\models;

use Yii;

class HitCounterSearch extends HitCounter
{

    public $date_start;
    public $date_end;
    public $date_filter;

    public $filter_by = 1;

    const FILTER_BY_DATE = 1;
    const FILTER_BY_WEEK = 2;
    const FILTER_BY_MONTH = 3;
    const FILTER_BY_YEAR = 4;

    public static function dropdownFilter()
    {
        return [
            self::FILTER_BY_DATE => Yii::t('app', 'Date'),
            self::FILTER_BY_WEEK => Yii::t('app', 'Week'),
            self::FILTER_BY_MONTH => Yii::t('app', 'Month'),
            self::FILTER_BY_YEAR => Yii::t('app', 'Year'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_start', 'date_end', 'filter_by', 'date_filter'], 'safe'],
            // [['date_start', 'date_end',], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'filter_by' => Yii::t('app', 'Filter By'),
            'date_filter' => Yii::t('app', 'Date Filter'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return Command
     */
    public function searchCount($params)
    {
        $date_start = null;
        $date_end = null;

        $query = self::find();
        // add conditions that should always apply here

        $this->load($params);

        if ($this->filter_by == self::FILTER_BY_YEAR) {
            $query->select([
                'filter_label' => 'YEAR(created_at)',
                'user_count' => 'COUNT(DISTINCT cookie_mark)',
                'year' => 'YEAR(created_at)',
            ])
                ->groupBy(['YEAR(created_at)']);
        } else if ($this->filter_by == self::FILTER_BY_MONTH) {
            $query->select([
                'filter_label' => 'CONCAT("Bulan Ke-",MONTH(created_at)," (",YEAR(created_at),")")',
                'user_count' => 'COUNT(DISTINCT cookie_mark)',
                'year' => 'YEAR(created_at)',
            ])
                ->groupBy(['MONTH(created_at)', 'YEAR(created_at)']);
        } else if ($this->filter_by == self::FILTER_BY_WEEK) {
            $query->select([
                'filter_label' => 'CONCAT("Minggu Ke-",WEEK(created_at)," (",YEAR(created_at),")")',
                'user_count' => 'COUNT(DISTINCT cookie_mark)',
                'year' => 'YEAR(created_at)',
            ])
                ->groupBy(['WEEK(created_at)', 'YEAR(created_at)']);
        } else {
            $query->select([
                'filter_label' => 'DATE(created_at)',
                'user_count' => 'COUNT(DISTINCT cookie_mark)',
                'year' => 'YEAR(created_at)',
            ])
                ->groupBy(['DATE(created_at)']);
        }

        if ($this->date_start) {
            $date_start = date('Y-m-d 00:00:00', strtotime($this->date_start));
        } else {
            $date_start = date('Y-m-d 00:00:00');
        }
        $query->andFilterWhere([
            '>=', 'created_at', $date_start,
        ]);

        if ($this->date_end) {
            $date_end = date('Y-m-d 23:59:59', strtotime($this->date_end));
        } else {
            $date_end = date('Y-m-d 23:59:59');
        }
        $query->andFilterWhere([
            '<=', 'created_at', $date_end,
        ]);

        $query->orderBy(['created_at' => SORT_ASC]);

        // cache for at least 1 hour
        $query->cache(3600);

        return $query;
    }
}
