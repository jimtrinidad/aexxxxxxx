<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function microsecID() {
	$v = round(microtime(true) * 1000);
    // just returning $v as floats converts to exponential value
    return number_format($v, 0, '', '');
}

function generate_mabuhay_id($lastname, $suffix_length = 6)
{
    // remove all except letters
    $clean  = preg_replace("/[^A-Za-z]/", '', $lastname);
    $name   = strtoupper($clean);

    $randomNumber = random_number($suffix_length);

    $id = $name . $randomNumber;

    // check if not exists
    $ci =& get_instance();
    $query = $ci->db->where('MabuhayID', $id)->get('UserAccountInformation');
    if ($query->num_rows() > 0) {
        // retry if exists
        $id = generate_mabuhay_id($lastname);
    }

    return $id;
}

function current_controller()
{
    $ci =& get_instance();
    return $ci->router->fetch_class();
}

function current_method()
{
    $ci =& get_instance();
    return $ci->router->fetch_method();
}

function get_current_url()
{
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}

function is_current_url($controller, $method = false)
{
    if (current_controller() != $controller) {
        return false;
    }

    if ($method && current_method() != $method) {
        return false;
    }

    return true;
}

function is_setting_page()
{
    if (in_array(current_controller(), array('accounts','department','documents','services','zones'))) {
        return true;
    }

    return false;
}

function random_number($length)
{
    return join('', array_map(function($value) { return mt_rand(0, 9); }, range(1, $length)));
}


function random_password($length = 8) 
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $password = array(); 
    $alpha_length = strlen($alphabet) - 1; 
    for ($i = 0; $i < $length; $i++) 
    {
        $n = rand(0, $alpha_length);
        $password[] = $alphabet[$n];
    }
    return implode($password); 
}

function random_letters($length = 8) 
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $letters  = array(); 
    $alpha_length = strlen($alphabet) - 1; 
    for ($i = 0; $i < $length; $i++) 
    {
        $n = rand(0, $alpha_length);
        $letters[] = $alphabet[$n];
    }
    return implode($letters); 
}

/**
* get qr file
* generate new if not exists
*/
function get_qr_file($data, $size = 3)
{
    $extension  = 'png';
    $key        = md5($data);
    $filename   = $key . '.' . $extension;
    $qr_path    = PUBLIC_DIRECTORY . 'assets/qr/' . $filename;
    if (file_exists($qr_path)) {
        return $filename;
    } else {
        // generate new
        $ci =& get_instance();
        $ci->load->library('qr/ciqrcode', array(
            'cachedir'  => APPPATH . 'cache/',
            'errorlog'  => APPPATH . 'logs/'
        ));

        $qrparams['data']   = $data;
        $qrparams['level']  = 'H';
        $qrparams['size']   = $size;
        $qrparams['black']  = array(13, 54, 17);
        $qrparams['savename'] = $qr_path;
        $ci->ciqrcode->generate($qrparams);
        if (file_exists($qr_path)) {
            return $filename;
        }
    }
    return false;
}

/**
* image to data uri
*/
function getDataURI($image, $mime = '') {
    return 'data: '.(function_exists('mime_content_type') ? mime_content_type($image) : $mime).';base64,'.base64_encode(file_get_contents($image));
}

/**
* quick print r with pre and exit;
*/
function print_data($data, $exit = false)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($exit) {
        exit;
    }
}


/**
* time ago
* get time difference
*/
function time_ago($from, $until = 'now', $format = 'string')
{
    $date = new DateTime($from);
    $interval = $date->diff(new DateTime($until));

    $diff = array(
            'y' => $interval->y,
            'm' => $interval->m,
            'd' => $interval->d,
            'h' => $interval->h,
            'i' => $interval->i,
            's' => $interval->s,
        );

    if ($format == 'array') {
        return $diff;
    } else {
        $str = '';
        if ($diff['y']) {
            $str .= '%yy, ';
        }
        if ($diff['m']) {
            $str .= '%mm, ';
        }
        if ($diff['d']) {
            $str .= '%dd, ';
        }
        if ($diff['h']) {
            $str .= '%hh, ';
        }
        $str .= '%imin';

        return $interval->format($str);
    }
}


/**
* compute expiration date
    1 => '1 month upon generation',
    2 => '6 months upon generation',
    3 => '1 year upon generation',
    4 => 'lifetime',
    5 => 'end of the current month',
    6 => 'end of the current year',
*/
function compute_expiration_date($validity, $from = 'now')
{
    
    $date = new DateTime($from);

    switch ($validity) {
        case 1:
            $date->add(new DateInterval('P1M'));
            return $date->format('Y-m-d');
        case 2:
            $date->add(new DateInterval('P6M'));
            return $date->format('Y-m-d');
        case 3:
            $date->add(new DateInterval('P1Y'));
            return $date->format('Y-m-d');
        case 4:
            return '9999-12-31';
        case 5:
            return $date->format('Y-m-t');
        case 6:
            return $date->format('Y-12-31');
    }

    return false;

}


/**
* number to words
*/
function number_to_words($number)
{
    $f = new NumberFormatter("en_US", NumberFormatter::SPELLOUT);
    $f->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-numbering-verbose");
    return $f->format($number);
}


function price_savings($budget, $actual)
{
    if ($actual < $budget) {
        return 'P' . number_format(($budget - $actual)) . ' savings';
    } else if ($actual > $budget) {
        return 'P' . number_format(($actual - $budget)) . ' over';
    }
    return '';
}


function containsWord($str, $word)
{
    return !!preg_match('#\\b' . preg_quote($word, '#') . '\\b#i', $str);
}

function wordMatch($str1, $str2)
{
    $words = preg_split('/[,;\s]+/', $str1, -1, PREG_SPLIT_NO_EMPTY);

    $words = array_diff($words, array('are','to','and','is','or','in')); // exclude from search

    if(empty($words)){
        return false;
    }

    foreach ($words as $word) {
        if (containsWord($str2, $word)) {
            return true;
        }
    }

    return false;
}

function compress_image($file)
{

    if (file_exists($file) && !is_dir($file)) {
        $im = new Imagick($file);

        $size = filesize($file);
        // greater then 50KB
        if ($size > 50000) {
            // Optimize the image layers
            $im->optimizeImageLayers();
            // Compression and quality
            $im->setImageCompression(Imagick::COMPRESSION_JPEG);
            $im->setImageCompressionQuality(25);

            syslog(LOG_INFO, basename($file) . ' - scale down size: ' . $size);
        }

        $width = $im->getImageWidth();
        if ($width > 1000) {
            $im->scaleImage(1000, 0);
            syslog(LOG_INFO, basename($file) . ' - scale down width: ' . $width);
        }

        // Write the image back
        $im->writeImages($file, true);
    }

}