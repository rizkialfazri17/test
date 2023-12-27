<?php

// get role string from int id
if (!function_exists('getRoleString')) {
    function getRoleString($int)
    {
        if ($int == 1 || $int == '1') {
            $result = 'Staff';
        } else if ($int == 2 || $int == '2') {
            $result = 'Admin';
        } else if ($int == 3 || $int == '3') {
            $result = 'Superadmin';
        } else if ($int == 4 || $int == '4') {
            $result = 'Programer';
        } else {
            $result = 'Unknown';
        }

        return $result;
    }
}

// get status string from int id
if (!function_exists('getStatusString')) {
    function getStatusString($int)
    {
        if ($int == 0 || $int == '0') {
            $result = 'Deactive';
        } else if ($int == 1 || $int == '1') {
            $result = 'Active';
        } else if ($int == 2 || $int == '2') {
            $result = 'Suspended';
        } else {
            $result = 'Unknown';
        }

        return $result;
    }
}

// get status logged in string from int id
if (!function_exists('getStatusLoggedInString')) {
    function getStatusLoggedInString($int)
    {
        if ($int == 0 || $int == '0') {
            $result = 'Offline';
        } else if ($int == 1 || $int == '1') {
            $result = 'Online';
        } else {
            $result = 'Unknown';
        }

        return $result;
    }
}

// get status post in string
if (!function_exists('getStatusPostInString')) {
    function getStatusPostInString($int)
    {
        if ($int == 0 || $int == '0') {
            $result = 'Hidden';
        } else if ($int == 1 || $int == '1') {
            $result = 'Publish';
        } else {
            $result = 'Unknown';
        }

        return $result;
    }
}

// get time ago
if (!function_exists('getTimeAgo')) {
    function getTimeAgo($oldTime, $newTime)
    {
        $timeAgo = strtotime($newTime) - strtotime($oldTime);

        if ($timeAgo >= (60 * 60 * 24 * 30 * 12 * 2)) {
            $timeAgo = intval($timeAgo / 60 / 60 / 24 / 30 / 12) . " Years Ago";
        } else if ($timeAgo >= (60 * 60 * 24 * 30 * 12)) {
            $timeAgo = intval($timeAgo / 60 / 60 / 24 / 30 / 12) . " Year Ago";
        } else if ($timeAgo >= (60 * 60 * 24 * 30 * 2)) {
            $timeAgo = intval($timeAgo / 60 / 60 / 24 / 30) . " Months Ago";
        } else if ($timeAgo >= (60 * 60 * 24 * 30)) {
            $timeAgo = intval($timeAgo / 60 / 60 / 24 / 30) . " Month Ago";
        } else if ($timeAgo >= (60 * 60 * 24 * 2)) {
            $timeAgo = intval($timeAgo / 60 / 60 / 24) . " Days Ago";
        } else if ($timeAgo >= (60 * 60 * 24)) {
            $timeAgo = "Yesterday";
        } else if ($timeAgo >= (60 * 60 * 2)) {
            $timeAgo = intval($timeAgo / 60 / 60) . " Hours Ago";
        } else if ($timeAgo >= (60 * 60)) {
            $timeAgo = intval($timeAgo / 60 / 60) . " Hour Ago";
        } else if ($timeAgo >= 60 * 2) {
            $timeAgo = intval($timeAgo / 60) . " Minutes Ago";
        } else if ($timeAgo >= 60) {
            $timeAgo = intval($timeAgo / 60) . " Minute Ago";
        } else if ($timeAgo > 0) {
            $timeAgo .= " Seconds Ago";
        } else {
            $timeAgo .= "Less than 1 second Ago";
        }

        return $timeAgo;
    }
}

// hari
if (!function_exists('hari')) {
    function hari($hari = null)
    {
        if (!empty($hari) || $hari != null || $hari != '') {
            $hari = $hari;
        } else {
            $hari = date('l');
        }

        if ($hari === 'Sunday') {
            $result = 'Minggu';
        } elseif ($hari === 'Monday') {
            $result = 'Senin';
        } elseif ($hari === 'Tuesday') {
            $result = 'Selasa';
        } elseif ($hari === 'Wednesday') {
            $result = 'Rabu';
        } elseif ($hari === 'Thursday') {
            $result = 'Kamis';
        } elseif ($hari === 'Friday') {
            $result = 'Jumat';
        } elseif ($hari === 'Saturday') {
            $result = 'Sabtu';
        } else {
            $result = 'Minggu';
        }

        return $result;
    }
}

// bulan
if (!function_exists('bulan')) {
    function bulan($bulan = null)
    {
        if (!empty($bulan) || $bulan != null || $bulan != '') {
            $bulan = $bulan;
        } else {
            $bulan = date('F');
        }

        if ($bulan === 'January') {
            $result = 'Januari';
        } elseif ($bulan === 'February') {
            $result = 'Februari';
        } elseif ($bulan === 'March') {
            $result = 'Maret';
        } elseif ($bulan === 'April') {
            $result = 'April';
        } elseif ($bulan === 'May') {
            $result = 'Mei';
        } elseif ($bulan === 'June') {
            $result = 'Juni';
        } elseif ($bulan === 'July') {
            $result = 'Juli';
        } elseif ($bulan === 'August') {
            $result = 'Agustus';
        } elseif ($bulan === 'September') {
            $result = 'September';
        } elseif ($bulan === 'October') {
            $result = 'Oktober';
        } elseif ($bulan === 'November') {
            $result = 'November';
        } elseif ($bulan === 'December') {
            $result = 'Desember';
        } else {
            $result = 'Januari';
        }

        return $result;
    }
}

// convert result percent to decimal
if (!function_exists('convertingPercent')) {
    function convertingPercent($number)
    {
        return number_format($number / 100, 2, ".", "");
    }
}

// number format
if (!function_exists('numberFormat')) {
    function numberFormat($number)
    {
        return number_format($number, 0, ".", "");
    }
}

// EASHash
if (!function_exists('AESHash')) {
    function AESHash($method, $string, $aeskey)
    {
        $result = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = $aeskey;
        $key = hash('sha256', $secret_key); // hash
        $substr = substr(hash('sha256', $secret_key), 0, 16);

        if ($method == 'encrypt') {
            $result = openssl_encrypt($string, $encrypt_method, $key, 0, $substr);
            $result = base64_encode($result);
        } else if ($method == 'decrypt') {
            $result = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $substr);
        }

        return $result;
    }
}

// get permission condition
if (!function_exists('getExistPermission')) {
    function getExistPermission($string, $key)
    {
        $stringToArray = explode(',', $string);

        if (in_array($key, $stringToArray)) {
            $result = TRUE;
        } else {
            $result = FALSE;
        }

        return $result;
    }
}

// get json data
if (!function_exists('getJsonData')) {
    function getJsonData($url)
    {
        // Inisialisasi cURL
        $ch = curl_init();
        // Atur opsi cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        // Lakukan permintaan GET
        $response = curl_exec($ch);

        // Periksa apakah permintaan berhasil
        if ($response === false) {
            return false;
        }

        // Tutup koneksi cURL
        curl_close($ch);

        // Parsing data JSON
        $jsonData = json_decode($response, TRUE);

        return $jsonData;
    }
}
