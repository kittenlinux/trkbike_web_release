<?php
  defined('BASEPATH') or exit('No direct script access allowed');

  $user = $this->ion_auth->user()->row();

  $this->db->from('bike');
  $this->db->where('user', $user->id);

  $count = $this->db->count_all_results();

  $this->db->select();
  $this->db->from('bike');
  $this->db->where('user', $user->id);

  $query = $this->db->get()->result_array();

  $app_version = '1.0';
?>

<section id="dashboard">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>จัดการข้อมูล</h2>
                    <p>จัดการข้อมูลรถจักรยานยนต์และการแจ้งเตือนไลน์โนติฟาย</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li>จัดการข้อมูล</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Banner -->

    <!-- Set active navigator bar menu -->
    <script>
    var nav_bar_active = 'nav-bar-dashboard';
    </script>

    <!-- Start Content -->
    <div id="content">
        <div class="container">
            <div class="page-content">
                <div class="row">
                    <?php echo isset($_SESSION['result_message']) ? "<div class=\"alert alert-".$_SESSION['result_message_type']."\">".$_SESSION['result_message']."</div>" : false; ?>
                    <div class="col-md-6">

                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>รถจักรยานยนต์</span></h4>

                        <!-- Some Text -->
                        <?php
                if ($count != 0) {
                    $cnt = 0;
                  
                    echo "<p>คุณมีรถจักรยานยนต์ในระบบทั้งหมด ".$count." คัน</p>";

                    echo "<!-- Accordion --><div class=\"panel-group\" id=\"accordion\">";

                    foreach ($query as $bike) {
                        $cnt++;

                        echo "<div class=\"panel panel-default\">
                      <div class=\"panel-heading\">
                        <h4 class=\"panel-title\">";
                        if ($cnt==1) {
                            echo "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse-".$cnt."\">";
                            echo "<i class=\"fa fa-angle-up control-icon\"></i>";
                        } else {
                            echo "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse-".$cnt."\" class=\"collapsed\">";
                            echo "<i class=\"fa fa-angle-up control-icon\"></i>";
                        }
                        echo "<i class=\"fa fa-motorcycle\"></i> ".$bike['plate'];
                        if ($bike['status']=='0') {
                            echo " (ปิดการใช้งาน)";
                        }
                        echo "</a>
                        </h4>
                      </div>";
                        if ($cnt==1) {
                            echo "<div id=\"collapse-".$cnt."\" class=\"panel-collapse collapse in\">";
                        } else {
                            echo "<div id=\"collapse-".$cnt."\" class=\"panel-collapse collapse\">";
                        }
                        echo "<div class=\"panel-body\">
                      <table width=\"100%\">
                        <tr>
                          <td><ul class=\"list-group\"><li class=\"list-group-item\"><strong>ยี่ห้อ รุ่น : </strong>".$bike['model']."</li><li class=\"list-group-item\"><strong>สี : </strong>".$bike['color']."</li></ul></td>
                        </tr>
                        <tr>";
                        if (!$bike['image_name']) {
                            echo "<td align=\"center\"><p><a href=\"".base_url()."Dashboard/Bike_PicUpload/".$bike['key']."\"><img src=\"".base_url()."images/placeholder.png\" class=\"img-responsive\"></img></a></p></td>";
                        } else {
                            echo "<td align=\"center\"><p><a href=\"".base_url()."uploads/bike/".$bike['image_name']."\" class=\"lightbox\"><img src=\"".base_url()."uploads/bike/thumbnail/".$bike['image_name']."\" class=\"img-responsive\"></img></a></p></td>";
                        }
                        echo "</tr>";
                        if ($bike['status']=='1') {
                            echo "<tr align=\"center\">
                            <td><p>เปิด<em>แอปพลิเคชันบนสมาร์ทโฟนแอนดรอยด์</em> และกดปุ่ม \"<strong>สแกนคิวอาร์โค้ด</strong>\" ทำการสแกนคิวอาร์โค้ดด้านล่าง เพื่อเริ่มติดตามรถจักรยานยนต์ของคุณ (คลิกหรือแตะที่คิวอาร์โค้ดเพื่อขยายขนาด)</p><p><a href=\"".base_url()."Dashboard/genqrcode/".$bike['key']."/x2.png\" class=\"lightbox\"><img src=\"".base_url()."Dashboard/genqrcode/".$bike['key']."/x1.png\" class=\"img-responsive\"></img></a></p></td>
                          </tr>
                          <tr>
                            <td><ul class=\"list-group\"><li class=\"list-group-item\"><strong>สถานะการผูกกับอุปกรณ์ : </strong>";
                            if ($bike['device_mac']!=null) {
                                echo 'รถจักรยานยนต์คันนี้ มีการผูกกับสมาร์ทโฟนแอนดรอยด์ที่อยู่ MAC Address '.$bike['device_mac'];
                            } else {
                                echo 'รถจักรยานยนต์คันนี้ ยังไม่มีการผูกกับสมาร์ทโฟนแอนดรอยด์เครื่องใด ๆ เปิดแอปพลิเคชันและสแกนคิวอาร์โค้ด เพื่อเริ่มต้นกระบวนการผูกอุปกรณ์เข้าสู่ระบบ';
                            }
                            echo "</li></ul></td>
                          </tr>";
                        } elseif ($bike['status']=='0') {
                            echo "<tr align=\"center\"><td><p><strong style=\"color: red;\">รถจักรยานยนต์คันนี้ถูกปิดการใช้งาน !</strong><br/>หากต้องการใช้งานการติดตามรถ โปรดเปิดใช้งานรถจักรยานยนต์ที่ปุ่มด้านล่าง</p></td></tr>";
                        }
                        if ($bike['status']=='0') {
                            echo "<tr align=\"center\"><td><button type=\"button\" class=\"btn btn-success\" id=\"switch_bike\" onclick=\"Bike_switchconfirm('".$bike['key']."', '".$bike['plate']."')\">เปิดใช้งาน</button>";
                        } elseif ($bike['status']=='1') {
                            echo "<tr align=\"center\"><td><button type=\"button\" class=\"btn btn-danger\" id=\"switch_bike\" onclick=\"Bike_switchconfirm('".$bike['key']."', '".$bike['plate']."')\">ปิดใช้งาน</button>";
                        }
                        echo "&nbsp;<button type=\"button\" class=\"btn btn-primary\" onclick=\"location.href='".base_url()."Dashboard/Bike_Edit/".$bike['key']."';\">แก้ไขข้อมูล</button>";
                        echo "&nbsp;<button type=\"button\" class=\"btn btn-default\" onclick=\"location.href='".base_url()."Dashboard/Bike_PicUpload/".$bike['key']."';\">อัพโหลด/เปลี่ยนรูป</button>";
                        echo "</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>";
                    }

                    echo "</div>";
                } else {
                    echo "<p>ดูเหมือนจะยังไม่มีรถจักรยานยนต์ในระบบ คลิกที่ปุ่มเพิ่มรถจักรยานยนต์ด้านล่างเพื่อทำการเพิ่มรถของท่าน</p>";
                }
              ?>

                        <p><button type="button" class="btn btn-primary"
                                onclick="location.href='<?php echo base_url();?>Dashboard/Bike_Add';">เพิ่มรถจักรยานยนต์</button>
                        </p>

                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>แอปพลิเคชันบนสมาร์ทโฟนแอนดรอยด์</span></h4>

                        <!-- Some Text -->
                        <p>แอปพลิเคชันติดตามรถเวอร์ชันปัจจุบันคือ<strong>เวอร์ชัน <?php echo $app_version; ?></strong>
                        </p>
                        <p>คลิกที่ปุ่มด้านล่างเพื่อดาวน์โหลดไฟล์ APK
                            และนำไปติดตั้งบนสมาร์ทโฟนแอนดรอยด์ที่ท่านต้องการใช้งานเพื่อส่งข้อมูลติดตามรถจักรยานยนต์</p>
                        <p><em>รองรับแอนดรอยด์เวอร์ชัน 4.4 (คิทแคท) ถึงเวอร์ชัน 9 (พาย)
                                และต้องการเซนเซอร์ไจโรสโคปในการทำงาน</em>
                        </p>
                        <p><button type="button" class="btn btn-success" id="app-download"
                                onclick="location.href='<?php echo base_url().'uploads/trkbike-trackclient-'.$app_version.'.apk';?>';">ดาวน์โหลด
                                APK</button></p>

                    </div>

                    <div class="col-md-6">

                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>การแจ้งเตือนไลน์โนติฟาย</span></h4>

                        <!-- Some Text -->
                        <p><?php
                    if (!$user->lineapi_key) {
                        echo "<p>หากต้องการใช้งานการส่งการแจ้งเตือนผ่านไลน์ ท่านจำเป็นต้องเพิ่มข้อมูล<strong>โทเค็นไลน์โนติฟาย</strong>ก่อน คลิกที่ปุ่มด้านล่าง เพื่อดูวิธีการรับโทเค็นและกรอกข้อมูลโทเค็นเข้าสู่ระบบ</p>";
                        echo "<p><button type=\"button\" class=\"btn btn-primary\" onclick=\"location.href='".base_url()."Dashboard/LINENotify_Edit';\">เพิ่มข้อมูลโทเค็น</button></p>";
                    } else {
                        echo "<p><strong>โทเค็นไลน์โนติฟาย</strong>ของคุณคือ <em>".$user->lineapi_key."</em><br/>หากต้องการแก้ไขข้อมูล คลิกที่ปุ่มด้านล่าง</p>";
                        echo "<p><button type=\"button\" class=\"btn btn-primary\" onclick=\"location.href='".base_url()."Dashboard/LINENotify_Edit';\">แก้ไขข้อมูลโทเค็น</button>";
                        echo "&nbsp;<button type=\"button\" class=\"btn btn-danger\" onclick=\"Linenoti_delconfirm()\">ลบโทเค็นออกจากระบบ</button></p>";
                    }
              ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End content -->
</section>

<script>
var newTitle = "จัดการข้อมูล | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

function Linenoti_delconfirm() {
    Swal.fire({
        title: 'ยืนยันการลบโทเค็น ?',
        text: "หากต้องการใช้งานการแจ้งเตือนอีกครั้ง จะต้องนำโทเค็นไลน์โนติฟายมาเพิ่มเข้าสู่ระบบในภายหลัง",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่ ต้องการลบ',
        cancelButtonText: 'ยกเลิก',
        allowEnterKey: 'false'
    }).then(function (result) {
        if (result.value) {
            // Swal.fire(
            //     title: 'สำเร็จ!',
            //     text: 'ลบโทเค็นเรียบร้อยแล้ว.',
            //     icon: 'success'
            // )
            window.location.href = "<?php echo base_url().'Dashboard/LINENotify_Remove/'.$user->lineapi_key; ?>";
        }
    })
}

function Bike_switchconfirm(bike_key, bike_plate) {
    Swal.fire({
        title: 'ยืนยันการเปลี่ยนสถานะ ?',
        text: "ยืนยันการเปลี่ยนสถานะรถจักรยานยนต์ หมายเลขทะเบียน " + bike_plate + " ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
        allowEnterKey: 'true'
    }).then(function (result) {
        if (result.value) {
            // Swal.fire(
            //     title: 'สำเร็จ!',
            //     text: 'ลบโทเค็นเรียบร้อยแล้ว.',
            //     icon: 'success'
            // )
            window.location.href = "<?php echo base_url().'Dashboard/Bike_Switch/'; ?>" + bike_key;
        }
    })
}
</script>