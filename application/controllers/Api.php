<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->helper('url');
    }
    
    public function index()
    {
        redirect('');
    }

    public function V1($object='')
    {
        switch ($object) {
            case 'track':
                $this->track();
                break;
            case 'register_check':
                $this->register_check();
                break;
            case 'register_confirm':
                $this->register_confirm();
                break;
            case 'track_ajax':
                $this->track_ajax();
                break;
            default:
                $json = array();
                $json['code'] = 'FAIL';
                $json['message'] = 'Unknown request!';
                echo json_encode($json);
        }
    }

    public function register_check()
    {
        header("Content-Type: application/json");
        $result = array();

        $result['code'] = 'FAIL';
        $result['message'] = 'Unknown Function / ฟังก์ชันไม่ถูกต้อง !';
        $result['data'] = null;

        try {
            $postdata = file_get_contents('php://input');
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if (empty($postdata)) {
            $result['code'] = 'FAIL';
            $result['message'] = 'Unknown Data / ข้อมูลไม่ถูกต้อง !';
            $result['data'] = null;
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } elseif (isset($postdata)) {
            $json = json_decode($postdata);

            if (isset($json->{'customerId'})&&isset($json->{'bikeId'})&&isset($json->{'macAddr'})) {
                $this->db->trans_start();
                $this->db->select(array('key', 'username', 'id'));
                $this->db->from('users');
                $this->db->where('key', $json->{'customerId'});

                $query1 = $this->db->get();
                foreach ($query1->result() as $row) {
                    $users_id = $row->key;
                    $users_username = $row->username;
                    $users_user = $row->id;
                }
                $this->db->trans_complete();

                $this->db->trans_start();
                $this->db->select(array('plate', 'model', 'color', 'key', 'user', 'device_mac'));
                $this->db->from('bike');
                $this->db->where('key', $json->{'bikeId'});
            
                $query2 = $this->db->get();
                foreach ($query2->result() as $row) {
                    $bike_id = $row->key;
                    $bike_plate = $row->plate;
                    $bike_model = $row->model;
                    $bike_color = $row->color;
                    $bike_user = $row->user;
                    $bike_mac = $row->device_mac;
                }
                $this->db->trans_complete();

                if ($bike_mac==null) {
                    $bike_macstatus = '0';
                } elseif ($bike_mac==$json->{'macAddr'}) {
                    $bike_macstatus = '1';
                } elseif ($bike_mac!=$json->{'macAddr'}) {
                    $bike_macstatus = '2';
                }
                
                if ((!isset($users_user)||!isset($bike_user))||($users_user!=$bike_user)) {
                    $result['code'] = 'FAIL';
                    $result['message'] = 'Unknown Data / ข้อมูลไม่ถูกต้อง !';
                    $result['data'] = null;
                } else {
                    $result['code'] = 'SUCCESS';
                    $result['message'] = 'Valid Data / ข้อมูลถูกต้อง !';
                    $result['data'] = array(
                                            'username' => $users_username,
                                            'users_user' => $users_user,
                                            'bike_id' => $bike_id,
                                            'plate' => $bike_plate,
                                            'model' => $bike_model,
                                            'color' => $bike_color,
                                            'mac_status' => $bike_macstatus
                                        );
                }
                
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function register_confirm()
    {
        header("Content-Type: application/json");
        $result = array();

        $result['code'] = 'FAIL';
        $result['message'] = 'Unknown Function / ฟังก์ชันไม่ถูกต้อง !';
        $result['data'] = null;

        try {
            $postdata = file_get_contents('php://input');
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if (empty($postdata)) {
            $result['code'] = 'FAIL';
            $result['message'] = 'Unknown Data / ข้อมูลไม่ถูกต้อง !';
            $result['data'] = null;
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } elseif (isset($postdata)) {
            $json = json_decode($postdata);

            if (isset($json->{'user'})&&isset($json->{'bikeId'})&&isset($json->{'macAddr'})) {
                $this->db->trans_start();
                $this->db->select('id');
                $this->db->from('bike');
                $this->db->where('key', $json->{'bikeId'});
            
                $query2 = $this->db->get();
                $row_count = $query2->num_rows();
                $this->db->trans_complete();

                if ($row_count) {
                    $this->db->trans_start();

                    $data = array(
                        'key' => $json->{'bikeId'},
                        'device_mac' => $json->{'macAddr'}
                    );

                    $this->db->where('key', $json->{'bikeId'});
                    $this->db->update('bike', $data);

                    $this->db->trans_complete();

                    $result['code'] = 'SUCCESS';
                    $result['message'] = 'Device Successfully Registered / ลงทะเบียนอุปกรณ์เสร็จสิ้น !';
                    $result['data'] = null;
                
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                } else {
                    $result['code'] = 'FAIL';
                    $result['message'] = 'Unknown Data / ข้อมูลไม่ถูกต้อง !';
                    $result['data'] = null;
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function track()
    {
        header("Content-Type: application/json");
        $result = array();

        $result['code'] = 'FAIL';
        $result['message'] = 'Unknown Function / ฟังก์ชันไม่ถูกต้อง !';
        $result['data'] = null;

        try {
            $postdata = file_get_contents('php://input');
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if (empty($postdata)) {
            $result['code'] = 'FAIL';
            $result['message'] = 'Unknown Data / ข้อมูลไม่ถูกต้อง !';
            $result['data'] = null;
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } elseif (isset($postdata)) {
            $json = json_decode($postdata);

            if (isset($json->{'user'})&&isset($json->{'bikeId'})&&isset($json->{'macAddr'})&&isset($json->{'lat'})&&isset($json->{'lng'})&&isset($json->{'event'})) {
                $this->db->trans_start();
                $this->db->select(array('id', 'user', 'key', 'plate', 'device_mac', 'status'));
                $this->db->from('bike');
                $this->db->where('user', $json->{'user'});
                $this->db->where('key', $json->{'bikeId'});
            
                $query2 = $this->db->get();
                $row_count = $query2->num_rows();
                foreach ($query2->result() as $row) {
                    $bike_id = $row->id;
                    $bike_user = $row->user;
                    $bike_key = $row->key;
                    $bike_plate = $row->plate;
                    $bike_mac = $row->device_mac;
                    $bike_status = $row->status;
                }
                $this->db->trans_complete();

                if ($row_count) {
                    if (!$json->{'macAddr'}||$json->{'macAddr'}!=$bike_mac) {
                        $result['code'] = 'INVALID';
                        $result['message'] = 'MAC Address not matched / ที่อยู่ MAC Address ไม่ตรงกับในระบบ !';
                        $result['data'] = null;
                        echo json_encode($result, JSON_UNESCAPED_UNICODE);
                    } elseif ($bike_status=='0') {
                        $result['code'] = 'DISABLED';
                        $result['message'] = 'Bike is disabled / รถจักรยานยนต์คันนี้ถูกปิดการใช้งาน !';
                        $result['data'] = null;
                        echo json_encode($result, JSON_UNESCAPED_UNICODE);
                    } else {
                        $this->db->trans_start();
                        $this->db->select(array('id', 'name', 'alert'));
                        $this->db->from('event');
                        $this->db->where('id', $json->{'event'});
            
                        $query3 = $this->db->get();
                        $row_count2 = $query3->num_rows();
                        foreach ($query3->result() as $row) {
                            $event_id = $row->id;
                            $event_name = $row->name;
                            $event_alert = $row->alert;
                        }
                        $this->db->trans_complete();

                        if ($row_count2) {
                            $this->db->trans_start();

                            $data = array(
                                'time' => date('Y-m-d H:i:s'),
                                'bike' => $bike_id,
                                'lat' => number_format($json->{'lat'}, 8),
                                'lng' => number_format($json->{'lng'}, 8),
                                'event' => $event_id
                            );
                            $this->db->insert('track', $data);

                            $this->db->trans_complete();

                            if ($event_alert=='1') {
                                $this->db->trans_start();
                                $this->db->select(array('id', 'username', 'lineapi_key'));
                                $this->db->from('users');
                                $this->db->where('id', $bike_user);

                                $query1 = $this->db->get();
                                foreach ($query1->result() as $row) {
                                    $users_id = $row->id;
                                    $users_username = $row->username;
                                    $users_lineapi_key = $row->lineapi_key;
                                }
                                $this->db->trans_complete();

                                if ($users_lineapi_key) {
                                    $this->Line_notify_model->notify($users_lineapi_key, "[Track My Bikes] รถจักรยานยนต์หมายเลขทะเบียน ".$bike_plate." มีเหตุการณ์ ".$event_name." เมื่อเวลา ".$data['time']);
                                }
                            }

                            $result['code'] = 'SUCCESS';
                            $result['message'] = 'Tracking Response Success / รับข้อมูลการติดตามเรียบร้อย !';
                            $result['data'] = null;
                            echo json_encode($result, JSON_UNESCAPED_UNICODE);
                        } else {
                            $result['code'] = 'FAIL';
                            $result['message'] = 'Event not found / ไม่มีเหตุการณ์นี้ในระบบ !';
                            $result['data'] = null;
                            echo json_encode($result, JSON_UNESCAPED_UNICODE);
                        }
                    }
                }
            } else {
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function track_ajax()
    {
        $response = array();
        $response['code'] = 'FAIL';
        $response['message'] = 'Unknown Customer Key!';
        $response['data'] = array();

        if (isset($_REQUEST['customerKey']) && isset($_REQUEST['act'])) {
            $customerKey = $_REQUEST['customerKey'];
            $act = $_REQUEST['act'];
            if ($act == 'get') {
                $trackData = $_REQUEST['trackData'];
                $json = json_decode($trackData);
                $bikeKeys = $json->bikeKeys;

                $this->db->trans_start();
                $this->db->select(array('key', 'id'));
                $this->db->from('users');
                $this->db->where('key', $customerKey);

                $query1 = $this->db->get();
                foreach ($query1->result() as $row) {
                    $users_key = $row->key;
                    $users_id = $row->id;
                }
                $this->db->trans_complete();

                $this->db->trans_start();
                $this->db->select(array('id', 'key', 'plate', 'user'));
                $this->db->from('bike');
                $this->db->where('key', $bikeKeys);
            
                $query2 = $this->db->get();
                foreach ($query2->result() as $row) {
                    $bike_id = $row->id;
                    $bike_key = $row->key;
                    $bike_plate = $row->plate;
                    $bike_user = $row->user;
                }
                $this->db->trans_complete();
                
                if ($query1->num_rows() && $query2->num_rows()) {
                    $customerId = $users_id;
    
                    $response['code'] = 'OK';
                    $response['message'] = 'Request Tracking Data is Successful';
                    $response['data']['customerKey'] = $customerKey;
    
                    $start_date = date('Y-m-d H:i:s', $json->dateBegin);
                    $end_date = date('Y-m-d H:i:s', $json->dateEnd);

                    $this->db->select("DATE_FORMAT(time, '%M %e, %Y %T') as date, bike, event, lat, lng");
                    $this->db->from('track');
                    $this->db->where('time >=', $start_date);
                    $this->db->where('time <=', $end_date);
                    $this->db->where('bike', $bike_id);
                    $query3 = $this->db->get();
                    
                    $this->db->trans_complete();
    
                    $response['data']['cars'] = array();
                    $car = 0;
                    foreach ($query3->result_array() as $lo) {
                        if ($car != $lo['bike']) {
                            $car = $car!=$lo['bike'];
                            $carKey = $bike_key;
                            $response['data']['cars'][$carKey] = array();
                        }
                        array_push($response['data']['cars'][$carKey], $lo);
                    }
                }
            }
        }
    
        echo json_encode($response);
    }
}
