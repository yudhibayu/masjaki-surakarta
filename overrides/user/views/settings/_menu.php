<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap5\Nav;
//use dektrium\user\widgets\UserMenu;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

/**
 * @var dektrium\user\models\User $user
 */

$user = Yii::$app->user->identity;
?>
<div class="row">
    <section class="card">
        <header class="card-header">
            <div class=" card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
            </div>
            <h4 class="panel-title">
                <?= Html::img($user->profile->getAvatarUrl(24), [
                    'class' => 'img-rounded',
                    'alt' => $user->profile->name ? $user->profile->name : $user->username,
                ]) ?>
                <?= $user->profile->name ? $user->profile->name : $user->username ?>
            </h4>
        </header>

        <div class="card-body menu-user-admin">


            <?php if ($user->profile) : ?>
                <div class="row text-center">
                    <?php if ($user->profile->file_name != '') : ?>
                        <img src="<?= Url::to(['/user/settings/uploaded-picture', 'data' => $user->profile->file_name]) ?>" class="img-circle profile_img2" alt="user_profile_pic">
                    <?php else : ?>
                        <img src="/images/blank-propic.png" class="img-circle profile_img2" alt="user_profile_pic">
                    <?php endif; ?>
                </div>
            <?php endif; ?>


            <br>

            <?php
            $networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
            $module_user = \Yii::$app->getModule('user');
            $items = [
                ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile' . $suffix]],
                ['label' => Yii::t('user', 'Account'), 'url' => ['/user/settings/account' . $suffix]],
                [
                    'label' => Yii::t('user', 'Networks'),
                    'url' => ['/user/settings/networks'],
                    'visible' => $networksVisible
                ],
                ['label' => Yii::t('user', 'Profile Picture'), 'url' => ['/user/settings/profile-picture']],
            ];
            ?>
            <?= Nav::widget([
                'options' => [
                    'class' => 'nav-pills nav-stacked menu-user-admin nav',
                    'style' => 'display: table'
                ],
                'items' => $items,
            ]);
            ?>
        </div>
    </section>
</div>