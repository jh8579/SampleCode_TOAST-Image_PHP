<?php
function get_task($IMAGEURL, $APPKEY, $SECRETKEY, $queue_id){
  echo '작업 조회 API <br/>\n';

  $url = $IMAGEURL . $APPKEY . '/queues'. '/' . $queue_id;    // url 초기화

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

$IMAGEURL = 'https://api-image.cloud.toast.com/image/v2.0/appkeys/';
$APPKEY = '{APP_KEY}';
$SECRETKEY = '{SECRET_KEY}';

$queue_id = '6691a01a-4585-4e26-989c-8ef25dd627a0';     // (필수) 조회할 작업 고유 ID

$response = get_task($IMAGEURL, $APPKEY, $SECRETKEY, $queue_id);
echo "Response :  $response <br/><br/>\n";
?>