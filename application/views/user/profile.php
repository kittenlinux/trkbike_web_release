<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    $user = $this->ion_auth->user()->row();

    $this->db->select(array('username', 'first_name', 'last_name', 'email'));
    $this->db->from('users');
    $this->db->where('id', $user->id);

    $query = $this->db->get();
    foreach ($query->result() as $row) {
        $query1 = $row->username;
        $query2 = $row->first_name;
        $query3 = $row->last_name;
        $query4 = $row->email;
    }
?>
<section id="profile">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>โปรไฟล์ของฉัน</h2>
                    <p>ดูหรือจัดการข้อมูลส่วนตัว เปลี่ยนรหัสผ่านเข้าใช้งาน</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li>โปรไฟล์ของฉัน</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Banner -->

    <!-- Set active navigator bar menu -->
    <script>
    var nav_bar_active = 'nav-bar-member';
    </script>

    <!-- Start Content -->
    <div id="content">
        <div class="container">
            <div class="page-content">
                <div class="row">
                    <?php echo isset($_SESSION['result_message']) ? "<div class=\"alert alert-".$_SESSION['result_message_type']."\">".$_SESSION['result_message']."</div>" : false; ?>
                    <div class="col-md-8">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>รายละเอียดผู้ใช้งาน</span></h4>

                        <ul class="list-group">
                            <li class="list-group-item"><strong>ชื่อผู้ใช้งาน :</strong> <?php echo $query1; ?></li>
                            <li class="list-group-item"><strong>ชื่อ - นามสกุล :</strong>
                                <?php echo $query2.' '.$query3; ?></li>
                            <li class="list-group-item"><strong>อีเมล :</strong> <?php echo $query4; ?></li>
                        </ul>

                        <p>หากต้องการเปลี่ยนข้อมูลส่วนตัว หรือเปลี่ยนรหัสผ่านปัจจุบัน คลิกที่ปุ่มด้านล่าง</p>

                        <p><button type="button" class="btn btn-primary"
                                onclick="location.href='<?php echo base_url();?>User/Profile_Edit'">แก้ไขโปรไฟล์</button>
                            &nbsp;<button type="button" class="btn btn-warning"
                                onclick="location.href='<?php echo base_url();?>User/Change_Password'">เปลี่ยนรหัสผ่าน</button>
                        </p>

                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>

                    <div class="col-md-4">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>หากต้องการออกจากระบบ</span></h4>

                        <!-- Some Info -->
                        <p>คลิกที่ลิงก์ด้านล่างเพื่อออกจากระบบ</p>
                        <p><a href="<?php echo base_url(); ?>User/Signout">ออกจากระบบ</a></p>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End content -->

</section>

<script>
var newTitle = "โปรไฟล์ของฉัน | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}
</script>