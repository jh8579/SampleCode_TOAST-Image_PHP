<?php
function get_realtime($IMAGEURL, $APPKEY, $SECRETKEY) {
  echo "실시간 서비스 조회 API <br/>";

  $url = $IMAGEURL . $APPKEY . "/users";    // url 초기화

  $req_header = array(
    'Authorization: ' . $SECRETKEY
  );  // 요청 헤더 생성

  $curl  = curl_init($url);   // curl 초기화

  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => FALSE,      // SSL 인증X
    CURLOPT_POST => FALSE,                // 메소드 정의
    CURLOPT_RETURNTRANSFER => TRUE,       // 리턴 값 출력
    CURLOPT_HTTPHEADER => $req_header     // 헤더 추가
  )); // curl 설정

  $response = curl_exec($curl); // API 호출
  curl_close($curl);

  return $response;
};

function put_realtime($IMAGEURL, $APPKEY, $SECRETKEY, $data) {
    echo "실시간 서비스 변경 API <br/>";

    $url = $IMAGEURL . $APPKEY . '/users';    // url 초기화
  
    $req_header = array(
      'Authorization: ' . $SECRETKEY,
      'Content-Type: application/json'
    );  // 요청 헤더 생성
  
    $curl  = curl_init($url); // curl 초기화
  
    curl_setopt_array($curl, array(
      CURLOPT_SSL_VERIFYPEER => FALSE,          // SSL 인증X
      CURLOPT_CUSTOMREQUEST => 'PUT',           // 메소드 정의
      CURLOPT_RETURNTRANSFER => TRUE,           // 리턴 값 출력
      CURLOPT_HTTPHEADER => $req_header,        // 헤더 추가
      CURLOPT_POSTFIELDS => json_encode($data)  // json 형식 변환
    )); // curl 설정
  
    $response = curl_exec($curl); // API 호출
    curl_close($curl);
  
    return $response;
};

/////////////// git add 전에 제외!!!!!! //////////////////////////////
$IMAGEURL = 'https://api-image.cloud.toast.com/image/v2.0/appkeys/';
/////////////////////////////////////////////////////////////////////

$realtime_service = 'true';                      // (필수) 실시간 서비스 제공 여부

$realtime_body = array(                         // 파라미터 json 형태로 변환
    'realtimeService' => $realtime_service
);

$response = get_realtime($IMAGEURL, $APPKEY, $SECRETKEY);
echo "Response :  $response <br/><br/>\n";

$response = put_realtime($IMAGEURL, $APPKEY, $SECRETKEY, $realtime_body);
echo "Response :  $response <br/><br/>\n";

?>