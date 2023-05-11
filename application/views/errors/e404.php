<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Start Content -->
<div id="content">
    <div class="container">
        <div class="page-content">
            <div class="error-page">
                <h1>404</h1>
                <h3>ไม่พบหน้านี้</h3>
                <p>ขออภัย หน้านี้ไม่มีอยู่จริงตามที่อยู่ที่ระบุไว้</p>
                <div class="text-center"><a href="<?php echo base_url(); ?>"
                        class="btn-system btn-small">กลับหน้าหลัก</a></div>
            </div>
        </div>
    </div>
</div>
<!-- End Content -->

<!-- Set active navigator bar menu -->
<script>
var nav_bar_active = 'nav-bar-home';
var newTitle = "404 ไม่พบหน้านี้ | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}
</script>