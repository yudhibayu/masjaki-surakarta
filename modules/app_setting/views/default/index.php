<?php
$this->title = Yii::t('app', 'Global Application Setting');
$this->params['breadcrumbs'][] = Yii::t('app', 'Global Application Setting');

use yii\web\View;
use yii\bootstrap5\Html;
use yii\bootstrap5\Alert;
use app\modules\app_setting\helpers\InputHelper as IH;

?>
<?php
if ($errors) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-warning',
        ],
        'body' => Yii::t('app', 'There\'s one or more error in the input fields, please check.'),
    ]);
} else if (!$errors && Yii::$app->request->post()) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => Yii::t('app', 'Data successfully Saved'),
    ]);
}
$isian = null;
$segment_id_error = array();
?>
<div class="row">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
            </div>

            <h2 class="card-title">
                <?= Yii::t('app', 'Global Application Setting') ?>
            </h2>
            <p class="card-subtitle"><i><?= Yii::t('app', 'Edit Carefully') ?></i></p>
        </header>
        <div class="card-body">
            <div class="tabs">
                <?php echo Html::beginForm("/app_setting/default"); ?>
                <ul class="nav nav-tabs">
                    <?php
                    $cnt = 0;

                    foreach ($segment as $row_segment) {

                        if ($cnt == 0) {
                            echo "<li class='nav-item active thetab" . $row_segment['segment_id'] . "'><a class='nav-link active' data-bs-target='#thetab" . $row_segment['segment_id'] . "' data-bs-toggle='tab' href='#thetab" . $row_segment['segment_id'] . "'>" . Yii::t('app', $row_segment['name']) . "</a></li>";
                        } else {
                            echo "<li class='nav-item thetab" . $row_segment['segment_id'] . "'><a class='nav-link' data-bs-target='#thetab" . $row_segment['segment_id'] . "' data-bs-toggle='tab' href='#thetab" . $row_segment['segment_id'] . "'>" . Yii::t('app', $row_segment['name']) . "</a></li>";
                        }
                        $cnt++;
                    }
                    ?>
                </ul>

                <div class="tab-content responsive" style="padding:10px; word-wrap: break-word;">
                    <?php $scnt = 0; ?>
                    <?php foreach ($segment as $row_data) : ?>

                        <?php if ($scnt == 0) : ?>
                            <div id="thetab<?= $row_data['segment_id'] ?>" class="tab-pane active" role="tabpanel">

                                <?php foreach ($data as $row_child_data) : ?>
                                    <?php if ($row_child_data['segment_id'] == $row_data['segment_id']) : ?>
                                        <div class="row" style="padding:10px;">
                                            <div class="col-md-4"><?= $row_child_data['name'] ?> <small>(<?= $row_child_data['key'] ?>)</small><br><span class="fa fa-info-circle"></span> <small><?= $row_child_data['hints'] ?></small></div>
                                            <div class="col-md-8">
                                                <?php
                                                if (isset($req_data[$row_child_data['key']])) {
                                                    $isian = $req_data[$row_child_data['key']];
                                                } else {
                                                    $isian = $row_child_data['value'];
                                                }
                                                ?>
                                                <?= IH::inputConvert($row_child_data, $isian); ?>
                                                <br>
                                                <?php
                                                if (isset($errors[$row_child_data['key']][0])) {
                                                    echo "<span class='text-danger'>" . $errors[$row_child_data['key']][0] . "</span>";
                                                    array_push($segment_id_error, $row_child_data['segment_id']);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                        <?php else : ?>
                            <div id="thetab<?= $row_data['segment_id'] ?>" class="tab-pane" role="tabpanel">

                                <?php foreach ($data as $row_child_data) : ?>

                                    <?php if ($row_child_data['segment_id'] == $row_data['segment_id']) : ?>
                                        <div class="row" style="padding:10px;">
                                            <div class="col-md-4"><?= $row_child_data['name'] ?> <small>(<?= $row_child_data['key'] ?>)</small><br><span class="fa fa-info-circle"></span> <small><?= $row_child_data['hints'] ?></small></div>
                                            <div class="col-md-8">
                                                <?php
                                                if (isset($req_data[$row_child_data['key']])) {
                                                    $isian = $req_data[$row_child_data['key']];
                                                } else {
                                                    $isian = $row_child_data['value'];
                                                }
                                                ?>
                                                <?= IH::inputConvert($row_child_data, $isian); ?>
                                                <br>
                                                <?php
                                                if (isset($errors[$row_child_data['key']][0])) {
                                                    echo "<span class='text-danger'>" . $errors[$row_child_data['key']][0] . "</span>";
                                                    array_push($segment_id_error, $row_child_data['segment_id']);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php $scnt++; ?>
                    <?php endforeach; ?>
                </div>
                <br>
                <div class="col-md-12 submit" style="text-align: right;">
                    <?php echo Html::submitButton('Simpan', ['class' => 'btn btn-success simpan text-right']); ?>
                </div>
                <?php echo Html::endForm(); ?>
            </div>
        </div>
    </section>
</div>

<?php
$this->registerJsVar('thetabs', array_unique($segment_id_error), View::POS_READY);
$this->registerJsVar('pesan', Yii::t('app', '(Error)'), View::POS_READY);
?>

<?php
$this->registerJs(
    <<< EOT_JS_CODE

if (thetabs){
    thetabs.forEach(notifier);
} 


function notifier(item, index) {
  $('.thetab'+item+' > a').append(' <b>'+pesan+'</b>').addClass('text-warning');
}
EOT_JS_CODE,
    View::POS_READY
);
?>