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
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 */

use kartik\password\PasswordInput;

$this->registerJsFile('@web/js/localisation/strength-meter-id.js', [
    'depends' => [
        \kartik\password\PasswordInputAsset::class,
    ]
]);
?>

<style>
    @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css");

    .input-group-addon>input[type="checkbox"] {
        display: none;
        visibility: hidden;
        opacity: 0;
        position: absolute;
    }

    .hei {
        margin: 0px 1px 0px 1px;
    }
</style>
<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'password')->widget(PasswordInput::classname()) ?>
<br>