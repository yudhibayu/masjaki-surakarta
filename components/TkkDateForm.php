<?php

namespace app\components;

use DateTimeImmutable;
use yii\base\Model;

class TkkDateForm
{
    public static function normalize(Model $model, array $attributes)
    {
        $valid = true;
        foreach ($attributes as $attribute) {
            $value = $model->$attribute;
            if (!is_string($value)) {
                continue;
            }
            $value = trim($value);
            $model->$attribute = $value;
            if ($value === '') {
                continue;
            }
            $date = DateTimeImmutable::createFromFormat('!d-m-Y', $value);
            if (!$date || $date->format('d-m-Y') !== $value) {
                $model->addError($attribute, 'Gunakan tanggal yang valid dengan format dd-mm-yyyy.');
                $valid = false;
                continue;
            }
            $model->$attribute = $date->format('Y-m-d');
        }
        return $valid;
    }

    public static function display($value)
    {
        if (!is_string($value)) {
            return $value;
        }
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value
            ? $date->format('d-m-Y') : $value;
    }
}
