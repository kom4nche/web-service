<?php
define("GOOGLE_API_KEY", "AIzaSyDroxqOpL3ht4yW7VNxKAmcoun8ygD35PY");
define("GOOGLE_GCM_URL", "https://android.googleapis.com/gcm/send");

function send_gcm_notify($reg_id, $message, $hora) {


    $fields = array(
        'registration_ids'  => array( $reg_id ),
        'data'              => array( "message" => $message, "hora" => $hora),
    );

    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, GOOGLE_GCM_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Problem occurred: ' . curl_error($ch));
    }

    curl_close($ch);
    echo $result;
 }

//$reg_id = "APA91bEbQJQ3w6xS8zy1xLHtQKVHEJmvmt2plfXId56YIEw_6fMV_TWQWkOTm-dwd9rpXjG2Sjq_77V7SYg7UTHhAeSgXatCjq5dt28bPTuN0_w-kjA-VUlHI5ut9bvpOmaQ6WYQGxOd0-HEMd2sp6wEmJsT56-DYA";
//$msg = "hola pame! msje de prueba!";

//send_gcm_notify($reg_id, $msg);

?>