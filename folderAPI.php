<?php
function post_folder($IMAGEURL, $APPKEY, $SECRETKEY, $path) {
  echo " 폴더 생성 API <br/>\n";

  $url = $IMAGEURL . $APPKEY . "/folders";    // url 초기화

  $req_header = array(
    'Authorization: ' . $SECRETKEY,
    'Content-Type: application/json'
  );  // 요청 헤더 생성

  $req_body = array(
      'path' => $path
  );  // 요청 본문 생성

  $curl  = curl_init($url); // curl 초기화

  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => FALSE,      // SSL 인증X
    CURLOPT_POST => TRUE,                // 메소드 정의
    CURLOPT_RETURNTRANSFER => TRUE,       // 리턴 값 출력
    CURLOPT_HTTPHEADER => $req_header,    // 헤더 추가
    CURLOPT_POSTFIELDS => json_encode($req_body)  // json 형식 변환
  )); // curl 설정

  $response = curl_exec($curl); // API 호출
  curl_close($curl);

  return $response;
};

function get_file($IMAGEURL, $APPKEY, $SECRETKEY, $data){
  echo " 폴더 내 파일 목록 조회 API <br/>\n";

  $url = $IMAGEURL . $APPKEY . "/folders";    // url 초기화

  $req_header = array(
    'Authorization: ' . $SECRETKEY
  );  // 요청 헤더 생성

  $req_params = '';   // 파라미터 초기화

  foreach($data as $key=>$value)          // 파라미터 형식에 맞게 변경
    $req_params .= $key.'='.$value.'&';   
  $req_params = trim($req_params, '&');   // 마지막 & 제거

  $curl  = curl_init();   // curl 초기화

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url.'?'.$req_params,  // URL에 파라미터 추가
    CURLOPT_SSL_VERIFYPEER => FALSE,      // SSL 인증X
    CURLOPT_POST => FALSE,                // 메소드 정의
    CURLOPT_RETURNTRANSFER => TRUE,       // 리턴 값 출력
    CURLOPT_HTTPHEADER => $req_header     // 헤더 추가
  )); // curl 설정

  $response = curl_exec($curl); // API 호출
  curl_close($curl);

  return $response;
};

function get_folder($IMAGEURL, $APPKEY, $SECRETKEY, $path){
  echo " 폴더 속성 조회 API <br/>\n";

  $url = $IMAGEURL . $APPKEY . "/properties";   // url 초기화

  $req_header = array(
    'Authorization: ' . $SECRETKEY
  );  // 요청 헤더 생성

  $req_params = 'path='.$path;    // 파라미터 초기화

  $curl  = curl_init($url);   // curl 초기화

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url.'?'.$req_params,  // URL에 파라미터 추가
    CURLOPT_SSL_VERIFYPEER => FALSE,      // SSL 인증X
    CURLOPT_POST => FALSE,                // 메소드 정의
    CURLOPT_RETURNTRANSFER => TRUE,       // 리턴 값 출력
    CURLOPT_HTTPHEADER => $req_header     // 헤더 추가
  )); // curl 설정

  $response = curl_exec($curl); // API 호출
  curl_close($curl);

  return $response;
}
/////////////// git add 전에 제외!!!!!! //////////////////////////////
$IMAGEURL = 'https://api-image.cloud.toast.com/image/v2.0/appkeys/';
/////////////////////////////////////////////////////////////////////

$path = '/dddd';       // (필수) 생성할 폴더의 절대 경로, 상위 폴더 자동 생성

$basepath = '/';        // (필수) 조회할 폴더의 전체 경로

$created_by = 'U';      // (선택) 목록 조회 대상 
$name = 'sample.jpg';   // (선택) 검색할 이미지 이름
$page = 1;              // (선택) 페이지 번호
$rows = 100;            // (선택) 조회 개수
$sort = 'name:asc';     // (선택) 정렬 방식 (정렬대상 : name or date, 정렬방식 : asc or desc)

$get_file_option = array(     // 폴더 내 파일 목록 조회 API 파라미터 목록
  'basepath' => $basepath,
  'createdBy' => $created_by,
  'name' => $name,
  'page' => $page,
  'rows' => $rows,
  'sort' => $sort
);

$response = post_folder($IMAGEURL, $APPKEY, $SECRETKEY, $path);
echo "Response :  $response <br/><br/>\n";

$response = get_file($IMAGEURL, $APPKEY, $SECRETKEY, $get_file_option);
echo "Response :  $response <br/><br/>\n";

$response = get_folder($IMAGEURL, $APPKEY, $SECRETKEY, $path);
echo "Response :  $response <br/><br/>\n";
?>