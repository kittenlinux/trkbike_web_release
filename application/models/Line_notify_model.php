<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Line_notify_model extends CI_Model
    {
        public function notify($token, $message)
        {
            $this->tkn = $token;
            $this->txt = trim($message);
            date_default_timezone_set("Asia/Bangkok");
            $this->dat = curl_init();
            curl_setopt($this->dat, CURLOPT_URL, "https://notify-api.line.me/api/notify");
            curl_setopt($this->dat, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->dat, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->dat, CURLOPT_POST, 1);
            curl_setopt($this->dat, CURLOPT_POSTFIELDS, "message=$this->txt");
            curl_setopt($this->dat, CURLOPT_FOLLOWLOCATION, 1);
            $this->hdr = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$this->tkn.'');
            curl_setopt($this->dat, CURLOPT_HTTPHEADER, $this->hdr);
            curl_setopt($this->dat, CURLOPT_RETURNTRANSFER, 1);
            $this->rslt = curl_exec($this->dat);
            if (curl_error($this->dat)) {
                echo 'error:'.curl_error($this->dat);
            } else {
                $this->rslt_ = json_decode($this->rslt, true);
                return $this->rslt_['status'];
            }
            curl_close($this->dat);
        }

        public function verification($token)
        {
            $this->tkn = $token;
            date_default_timezone_set("Asia/Bangkok");
            $this->dat = curl_init();
            curl_setopt($this->dat, CURLOPT_URL, "https://notify-api.line.me/api/status");
            curl_setopt($this->dat, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->dat, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->dat, CURLOPT_FOLLOWLOCATION, 1);
            $this->hdr = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$this->tkn.'');
            curl_setopt($this->dat, CURLOPT_HTTPHEADER, $this->hdr);
            curl_setopt($this->dat, CURLOPT_RETURNTRANSFER, 1);
            $this->rslt = curl_exec($this->dat);
            if (curl_error($this->dat)) {
                echo 'error:'.curl_error($this->dat);
            } else {
                $this->rslt_ = json_decode($this->rslt, true);
                return $this->rslt_['status'];
            }
            curl_close($this->dat);
        }
    }
