<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Start Home Page Slider -->
<section id="home">
    <!-- Carousel -->
    <div id="main-slide" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#main-slide" data-slide-to="0" class="active"></li>
            <li data-target="#main-slide" data-slide-to="1"></li>
            <li data-target="#main-slide" data-slide-to="2"></li>
            <li data-target="#main-slide" data-slide-to="3"></li>
        </ol>
        <!--/ Indicators end-->

        <!-- Carousel inner -->
        <div class="carousel-inner">
            <div class="item active">
                <img class="img-responsive" src="<?php echo base_url(); ?>themes/default/images/slider/bg1.jpg"
                    alt="slider">
                <div class="slider-content">
                    <div class="col-md-12 text-center">
                        <h2 class="animated7">
                            <span>ยินดีต้อนรับสู่ <strong>Track My Bikes</strong></span>
                        </h2>
                        <h3 class="animated8 white">
                            <span>โปรแกรมที่จะช่วยให้คุณติดตามรถของคุณได้สะดวกยิ่งขึ้น</span>
                        </h3>
                        <p class="animated6"><a href="<?php echo base_url(); ?>User/Register"
                                class="slider btn btn-system btn-large">ลงทะเบียนตอนนี้</a></p>
                    </div>
                </div>
            </div>
            <!--/ Carousel item end -->
            <div class="item">
                <img class="img-responsive" src="<?php echo base_url(); ?>themes/default/images/slider/bg2.jpg"
                    alt="slider">
                <div class="slider-content">
                    <div class="col-md-12 text-center">
                        <h2 class="animated7">
                            <span>ระบบติดตามรถจักรยานยนต์ที่<strong>ราคาถูก</strong>ที่สุด</span>
                        </h2>
                        <h3 class="animated8 white">
                            <span>โดยใช้โทรศัพท์มือถือแอนดรอยด์เครื่องเก่าของคุณ</span>
                        </h3>
                        <p class="animated6"><a href="<?php echo base_url(); ?>User/Register"
                                class="slider btn btn-system btn-large">ลงทะเบียนตอนนี้</a></p>
                    </div>
                </div>
            </div>
            <!--/ Carousel item end -->
            <div class="item">
                <img class="img-responsive" src="<?php echo base_url(); ?>themes/default/images/slider/bg3.jpg"
                    alt="slider">
                <div class="slider-content">
                    <div class="col-md-12 text-center">
                        <h2 class="animated7">
                            <span>ระบบ <strong>ติดตามรถ</strong> แบบเรียลไทม์</span>
                        </h2>
                        <h3 class="animated8 white">
                            <span>รถถูกยก, ถูกขับออกนอกพื้นที่ และอื่น ๆ</span>
                        </h3>
                        <p class="animated6"><a href="<?php echo base_url(); ?>User/Register"
                                class="slider btn btn-system btn-large">ลงทะเบียนตอนนี้</a></p>
                    </div>
                </div>
            </div>
            <!--/ Carousel item end -->
            <div class="item">
                <img class="img-responsive" src="<?php echo base_url(); ?>themes/default/images/slider/bg3.jpg"
                    alt="slider">
                <div class="slider-content">
                    <div class="col-md-12 text-center">
                        <h2 class="animated7">
                            <span>ค้นหา<strong>ว่ารถจักรยานยนต์อยู่ที่ไหน</strong></span>
                        </h2>
                        <h3 class="animated8 white">
                            <span>หาตำแหน่งรถด้วยกูเกิลแมพ</span>
                        </h3>
                        <p class="animated6"><a href="<?php echo base_url(); ?>User/Register"
                                class="slider btn btn-system btn-large">ลงทะเบียนตอนนี้</a></p>
                    </div>
                </div>
            </div>
            <!--/ Carousel item end -->
        </div>
        <!-- Carousel inner end-->

        <!-- Controls -->
        <a class="left carousel-control" href="#main-slide" data-slide="prev">
            <span><i class="fa fa-angle-left"></i></span>
        </a>
        <a class="right carousel-control" href="#main-slide" data-slide="next">
            <span><i class="fa fa-angle-right"></i></span>
        </a>
    </div>
    <!-- /carousel -->
</section>
<!-- End Home Page Slider -->

<!-- Set active navigator bar menu -->
<script>
var nav_bar_active = 'nav-bar-home';
</script>