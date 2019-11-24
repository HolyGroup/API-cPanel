<?php 
// Change this!!!

$cpanel_user = $utilisateur;
$cpanel_password = $password;

$host = $domaine;

$server_ip = '0.0.0.0';

function CpanelRequest($user,$password,$hosts,$query,$ip=false) {
    
    if($ip){
        $query = 'https://'.$ip.':2083'.$query;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: Basic " . base64_encode($user. ":" . $password) . "\n\r";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $query);
        $result = curl_exec($curl);
        if ($result == false) {
            error_log("curl_exec threw error \"" . curl_error($curl) . "\" for $query");
        }
        curl_close($curl);
        $result = json_encode($result,1);
        return $result;
    } else {
        return false;
    }
} ?>

<?php
    $qry = '/execute/Backup/fullbackup_to_ftp?variant=active&username='.$cpanel_user.'&password='.$cpanel_password.'&host='.$host.'&port=21&directory=%2Fpublic_ftp&email=username%40example.com';
    $test = CpanelRequest($cpanel_user,$cpanel_password,$host,$qry,$server_ip);
    //$parsed_json = json_decode($test, JSON_UNESCAPED_SLASHES);
    //echo $parsed_json;
?>
