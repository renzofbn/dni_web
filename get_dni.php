<?php
$API_KEY = "d1e84e6b334a9564048bb75552f8539e6e925466249eef13506c8776833d9da3";

$dni=$_POST['dni'];

if(!strlen($dni)== 8){
  echo 1;
}
else{
  $dni_request = curl_init();
  curl_setopt_array($dni_request, [
    CURLOPT_URL => "https://www.apisperu.net/api/dni/$dni",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $API_KEY"],
    ]);
  $dni_response = curl_exec($dni_request);
  $dni_err = curl_error($dni_request);
  curl_close($dni_request);
  if ($dni_err){
    echo 1;
    return;
  }
  $dni_response_obj = json_decode($dni_response);
  $CLIENT_RUC = "10".$dni_response_obj->data->numero.$dni_response_obj->data->codigo_verificacion;

  $ruc_request = curl_init();
  curl_setopt_array($ruc_request, [
    CURLOPT_URL => "https://www.apisperu.net/api/ruc/$CLIENT_RUC",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $API_KEY"
    ],
  ]);
  $ruc_response = curl_exec($ruc_request);
  $ruc_err = curl_error($ruc_request);
  curl_close($ruc_request);
  if ($ruc_err) {
    echo 1;
  }else {
    $ruc_response_obj = json_decode($ruc_response);
    $final_result = array_merge_recursive((array)$dni_response_obj->data, (array)$ruc_response_obj->data);
    echo json_encode($final_result);
  }
}
?>
