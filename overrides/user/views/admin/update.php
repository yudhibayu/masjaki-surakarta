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
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\User $user
 * @var string $content
 */

$this->title = Yii::t('user', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<section class="card">
    <div class="card-body">
        <?= $this->render('_menu') ?>

        <div class="row">
            <div class="col-md-3">
                <section class="card">
                    <div class="card-body bgcard">
                        <?php if ($user->profile) : ?>
                            <div class="row text-center">
                                <?php if ($user->profile->file_name != '') : ?>
                                    <img width="200px" src="<?= Url::to(['/user/settings/uploaded-picture', 'data' => $user->profile->file_name]) ?>" alt="user_profile_pic">
                                <?php else : ?>
                                    <img width="200px" src="/images/blank-propic.png" alt="blank_user_profile_pic">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <br>
                        <?= Nav::widget([
                            'options' => [
                                'class' => 'nav-pills nav-stacked menu-user-admin',
                                'style' => 'display: table'
                            ],
                            'items' => [
                                [
                                    'label' => Yii::t('user', 'Account details'),
                                    'url' => ['/user/admin/update', 'id' => $user->id]
                                ],
                                [
                                    'label' => Yii::t('user', 'Profile details'),
                                    'url' => ['/user/admin/update-profile', 'id' => $user->id]
                                ],
                                [
                                    'label' => Yii::t('user', 'Assignments'),
                                    'url' => ['/user/admin/assignments', 'id' => $user->id],
                                    'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
                                ],
                                ['label' => Yii::t('user', 'Profile Picture'), 'url' => ['/user/admin/profile-picture', 'id' => $user->id]],
                                '<hr>',
                                ['label' => Yii::t('user', 'Information'), 'url' => ['/user/admin/info', 'id' => $user->id]],
                                '<hr>',
                                [
                                    'label' => Yii::t('user', 'Confirm'),
                                    'url' => ['/user/admin/confirm', 'id' => $user->id],
                                    'visible' => !$user->isConfirmed,
                                    'linkOptions' => [
                                        'class' => 'text-success',
                                        'data-method' => 'post',
                                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                                    ],
                                ],
                                [
                                    'label' => Yii::t('user', 'Block'),
                                    'url' => ['/user/admin/block', 'id' => $user->id],
                                    'visible' => !$user->isBlocked,
                                    'linkOptions' => [
                                        'class' => 'text-danger',
                                        'data-method' => 'post',
                                        'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                                    ],
                                ],
                                [
                                    'label' => Yii::t('user', 'Unblock'),
                                    'url' => ['/user/admin/block', 'id' => $user->id],
                                    'visible' => $user->isBlocked,
                                    'linkOptions' => [
                                        'class' => 'text-success',
                                        'data-method' => 'post',
                                        'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                                    ],
                                ],
                                [
                                    'label' => Yii::t('app', 'Delete'),
                                    'url' => ['/user/admin/delete', 'id' => $user->id],
                                    'linkOptions' => [
                                        'class' => 'text-danger',
                                        'data-method' => 'post',
                                        'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?'),
                                    ],
                                ],
                            ],
                        ]) ?>
                    </div>
                </section>
            </div>
            <div class="col-md-9">
                <section class="card">
                    <div class="card-body bgcard">
                        <?= $content ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
    </div>
</section>