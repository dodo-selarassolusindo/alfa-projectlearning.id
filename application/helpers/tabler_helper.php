<?php
defined('BASEPATH') OR exit('No direct script access allowed');


    /**
     * get new nomor
     */
    //
    function get_new_nomor($table, $kode, $tanggal)
    {

        $CI =& get_instance();

        if ($tanggal != null) {
            // echo pre(dateMysql($date)); exit;
        }

        $nextNomor = "";
        $lastNomor = "";

        $prefix = $tanggal != null ? $kode . substr($tanggal, -2) . substr($tanggal, 3, 2) : $kode . date('ym'); // date('ym');
        $nextNomor = $prefix . "001";

        $len_kode = strlen($kode)+4; // pre($prefix);
        $len_next_nomor = $len_kode + 3;
        $CI->db->where('left(nomor, '.$len_kode.') = ', $prefix);
        $CI->db->order_by('nomor', 'desc');
        $CI->db->limit(1);
        $row = $CI->db->get($table)->row();
        if ($row) {
            $value = $row->nomor;
            if ($prefix == substr($value, 0, $len_kode)) {
                // pre(substr($value, 0, $len_kode));
                /**
                 * masih pada bulan yang sama
                 */
                $lastNomor = intval(substr($value, $len_kode, 3)); // pre($lastNomor);
                $lastNomor = intval($lastNomor) + 1; // pre($lastNomor);
                $nextNomor = $prefix . sprintf('%03s', $lastNomor); // pre($nextNomor);
                if (strlen($nextNomor) > $len_next_nomor) {
                    $nextNomor = $prefix . "999";
                }
            }
        }
        return $nextNomor;
    }


    /**
     * get jenis pembayaran by id
     */
    function get_jenis_pembayaran($id)
    {
        $ci =& get_instance();
        $q = 'select nama from t01_jenis_pembayaran where id = '.$id;
        $row = $ci->db->query($q)->row();
        return $row->nama;
    }


    /**
     * get informasi tanggal periode terakhir
     */
    function is_periode_terbaru($tanggal)
    {
        $ci =& get_instance();
        $q = 'select max(tanggal) as max_tanggal from t03_periode';
        $row = $ci->db->query($q)->row();
        $tanggal_periode_terbaru = $row->max_tanggal;
        return $tanggal_periode_terbaru == $tanggal;
    }


    /**
     * get mata uang kode by id
     */
    function get_mata_uang_kode($id)
    {
        $ci =& get_instance();
        $q = 'select kode from t00_mata_uang where id = '.$id;
        $row = $ci->db->query($q)->row();
        return $row->kode;
    }


    /**
     * mengubah format tanggal menjadi format dd-mm-yyyy
     */
    function date_to_dmy($value)
    {
        return date_format(date_create($value), 'd-m-Y');
    }


    /**
     * mengubah format tanggal menjadi format yyyy-mm-dd
     */
    function date_to_ymd($value)
    {
        return date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }


    /**
     * menampilkan nilai variabel
     */
    function pre($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
