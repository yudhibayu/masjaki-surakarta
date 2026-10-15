<style>
.first {
    height: 100%;
    margin: 0;
}

.containerr {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 80vh;
}
</style>
<div class="first">
    <div class="containerr">
        <div class="header-buttons d-flex align-items-center">
            <a class="btn btn-danger me-3 p-3"
                href="business-entity"><?= Yii::t('user', 'Daftar Sebagai Badan Perusahaan') ?></a>
            <a class="btn btn-warning ml-2 p-3" href="labor"><?= Yii::t('user', 'Daftar Sebagai Tenaga Kerja') ?></a>
        </div>
    </div>
</div>