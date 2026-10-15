<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var $this  \yii\web\View
 * @var $model \dektrium\rbac\models\Rule
 */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'enableClientValidation' => false,
                    'enableAjaxValidation'   => true,
                    'fieldConfig' => [
                        'horizontalCssClasses' => [
                            'wrapper' => 'col-md-10 form-line',
                            'label' => 'col-md-2',
                        ],
                        'options' => [
                            //'style'=>'margin-bottom:10px'
                        ]
                    ],
                    
                ]) ?>

                <?= $form->field($model, 'name') ?>

                <?= $form->field($model, 'class') ?>

                <div class="form-group">
                    <div class="pull-right">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>