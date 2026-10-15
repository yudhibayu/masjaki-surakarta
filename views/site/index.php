<?php

use coderius\hitCounter\widgets\hitCounter\HitCounterWidget;

/* @var $this yii\web\View */

$this->title = Yii::$app->name . ' - Beranda';
?>


<!-- ======= Hero Section ======= -->
<!-- ======= Counts Section ======= -->
<main class="main">
  <section id="hero" class="hero section light-background">
    <div class="container">
      <div class="row gy-4 justify-content-center justify-content-lg-between">
        <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
          <h1 data-aos="fade-up">MAS JAKI</h1>
          <h1 data-aos="fade-up" style="color: var(--bs-primary);">Kota Surakarta</h1>
          <p data-aos="fade-up" data-aos-delay="100">
            Manajemen Informasi Jasa Konstruksi Dan Pelaporan Kecelakaan Kerja. Bertujuan untuk memberi
            layanan kepada masyarakat untuk memperoleh informasi pasar kerja konstruksi serta dapat memantau
            kecelakaan kerja konstruksi.
          </p>
          <div class="d-flex gap-3 mt-3" data-aos="fade-up" data-aos-delay="200">
            <a href="/lowongan" class="btn btn-outline-danger fw-bold">Lihat Lowongan Konstruksi</a>
            <a href="/pelatihan" class="btn btn-outline-danger fw-bold">Ikut Pelatihan</a>
          </div>
          <?= HitCounterWidget::widget([]); ?>
          <!-- <h5 class="mt-3">Pengunjung Keseluruhan : <= $visitor ?></h5>
          <h5 class="mt-3">Pengunjung Bulan Ini : <= $hitsPerMonth ?></h5> -->
        </div>
        <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
          <img src="/images/konstruksi.png" class="img-fluid animated" alt="">
        </div>
      </div>
      <!-- <h5 class="mt-3 text-end">Pengunjung Hari Ini : <?= $hitsPerDay ?> orang</h5> -->
    </div>
  </section>
</main>

<style>
  .hero .btn {
    border-radius: 8px;
    border: 2px solid var(--bs-danger);
    color: var(--bs-danger);
    background-color: transparent;
    padding: 8px 16px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  .hero .btn:hover {
    background-color: var(--bs-danger);
    color: white;
    border-color: var(--bs-danger);
  }

  .hero .gap-3 {
    gap: 1rem;
  }
</style>


<!-- <section style="padding: 0px; background: url('images/gambar1.png') center center no-repeat;" id="counts" class="counts animate__animated animate__fadeInDown">
    <div class="position-relative">
        <div class="test text-center title animate__animated animate__fadeIn" style="padding: 10px; position: absolute; animation-delay: 2s; background-color: #ffffff55; justify-content: center; align-items: center; position: absolute; top: 30%; left: 50%; transform: translate(-50%, -50%); border-radius: 25px;">
            <br>
            <img src="images/simonela.png" width="30%">
            <br><br>
            <h6 style="color:#000">Aplikasi SIMONELA (Sistem Monitoring dan Evaluasi Lapangan)<br>KOTA SURAKARTA</h6>
            
            <h6 style="color:#000">dengan modul</h6>
            <img src="images/masinis.png" width="15%">
            <br><br>
            <h6 style="color:#000">MASINIS (Monitoring dan Evaluasi Spesifikasi Teknis)</h6>
        </div>
        <img src="images/kotasurakarta2.png" width="100%" class="position-relative animate__animated animate__fadeInUp" style="animation-delay: 1s;">
    </div>
</section> -->
<!-- End Counts Section -->
<!-- <section id="hero">
    <div class="hero-container">
        <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">

            <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

            <div class="carousel-inner" role="listbox">

                <-- Slide 1 --
                <div class="carousel-item active" style="background-image: url(images/pracima.jpg);">
                    <div class="carousel-container">
                        <div class="carousel-content">
                            <-- <img src="images/kotasurakarta.png" class="animate__animated animate__fadeInUp"> --
                            <h2 class="animate__animated animate__fadeInDown">Selamat Datang</h2>
                            <h4 class="animate__animated animate__fadeInUp">di Aplikasi SIMONELA <br>(Sistem Monitoring dan Evaluasi Lapangan)<br>KOTA SURAKARTA</h4>
                            <-- <a href="#about" class="btn-get-started scrollto animate__animated animate__fadeInUp">Read More</a> --
                        </div>
                    </div>
                </div>

            </div> -->

<!-- <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon ri-arrow-left-line" aria-hidden="true"></span>
            </a>

            <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon ri-arrow-right-line" aria-hidden="true"></span>
            </a> -->

<!-- </div>
    </div>
</section> -->
<!-- End Hero -->