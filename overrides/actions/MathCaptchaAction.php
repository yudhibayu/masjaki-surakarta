<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\overrides\actions;

use Yii;
use yii\captcha\CaptchaAction;

/**
 * AdminController allows you to administrate users.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class MathCaptchaAction extends CaptchaAction
{
    /**
     * {@inheritdoc}
     */
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 3;
        }
        if ($this->maxLength > 20) {
            $this->maxLength = 20;
        }

        $numbers = [];
        for ($i=$this->minLength; $i <= $this->maxLength; $i++) { 
            $numbers[] = mt_rand(10,100);
        }
        shuffle($numbers);

        if($this->maxLength == $this->minLength){
            return ''.$numbers[0];
        }

        return ''.$numbers[mt_rand(0,($this->maxLength - $this->minLength))];
    }

    /**
     * {@inheritdoc}
     */
    protected function renderImage($code)
    {
        return parent::renderImage($this->getText($code));
    }

    protected function getText($code)
    {
        $code = (int)$code;
        $rand = 0;
        while (($rand == 0) || ($rand == $code)) {
            $rand = mt_rand(min(1,$code -1 ),max(1, $code + 1));
        }
        $operation = mt_rand(0, 1);
        if($operation === 1) {
            return $code - $rand .'+'. $rand.' =';
        }else{
            return $code + $rand .'-'. $rand.' =';
        }
    }
}
