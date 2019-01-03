<?php
function upload_one_file($IMAGEURL, $APPKEY, $SECRETKEY, $data){
  echo "단일 파일 업로드 API <br/>";

  $url = $IMAGEURL . $APPKEY . "/images";    // url 초기화

  echo json_encode($data);

  $req_header = array(
    'Authorization: ' . $SECRETKEY,
    'Content-Type:application/octet-stream'
  );  // 요청 헤더 생성

  $req_params = '';   // 파라미터 초기화

  foreach($data as $key=>$value)          // 파라미터 형식에 맞게 변경
    $req_params .= $key.'='.$value.'&';   
  $req_params = trim($req_params, '&');   // 마지막 & 제거

  $file = file_get_contents('./sample.png');    // 단일 파일 업로드

  $curl  = curl_init();   // curl 초기화

  echo '</br>' . $url.'?'.$req_params . '</br>';

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url.'?'.$req_params,  // URL에 파라미터 추가
    CURLOPT_SSL_VERIFYPEER => FALSE,      // SSL 인증X
    CURLOPT_CUSTOMREQUEST => 'PUT',       // 메소드 정의
    CURLOPT_RETURNTRANSFER => TRUE,       // 리턴 값 출력
    CURLOPT_HTTPHEADER => $req_header,     // 헤더 추가
    CURLOPT_POSTFIELDS => $file
  )); // curl 설정

  $response = curl_exec($curl); // API 호출
  curl_close($curl);

  return $response;
}

function upload_multi_file($IMAGEURL, $APPKEY, $SECRETKEY, $data) {
  echo "다중 파일 업로드 API <br/>\n";

  $url = $IMAGEURL . $APPKEY . "/images";    // url 초기화

  $boundary = uniqid();                         // boundary 지정
  $delimiter = '-------------' . $boundary;     // delimiter 지정

  $req_header = array(
    'Authorization: ' . $SECRETKEY,
    "Content-Type: multipart/form-data; boundary=" . $delimiter,
  );  // 요청 헤더 생성

  $req_body = $data;        // body 데이터 지정

  $filenames = array("sample.jpg", "sample.png");       // 파일 경로 지정   

  $files = array();                                     // 다중 파일 업로드
  foreach ($filenames as $f){
        $files[$f] = file_get_contents("./".$f);
  }
  $post_data = build_data_files($boundary, json_encode($data), $files); // multipart 형식에 맞게 변환

  $curl  = curl_init($url); // curl 초기화

  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => FALSE,        // SSL 인증X
    CURLOPT_POST => TRUE,                   // 메소드 정의
    CURLOPT_RETURNTRANSFER => TRUE,         // 리턴 값 출력
    CURLOPT_HTTPHEADER => $req_header,      // 헤더 추가
    CURLOPT_POSTFIELDS => $post_data        // json 형식 변환
  )); // curl 설정

  $response = curl_exec($curl); // API 호출
  curl_close($curl);

  return $response;
};

function build_data_files($boundary, $fields, $files){      // multipart 형식에 맞게 변환 함수
    $data = '';
    $eol = "\r\n";
    $params = "params";
    $name = "files";

    $delimiter = '-------------' . $boundary;

    // params 지정
    $data .= "--" . $delimiter . $eol             
    . 'Content-Disposition: form-data; name="' . $params . "\"" . $eol . $eol. $fields . $eol;

    // files 지정
    foreach ($files as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="files"; filename="' . $name . '"' . $eol
            . 'Content-Transfer-Encoding: binary'.$eol;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;


    return $data;
};

$IMAGEURL = 'https://api-image.cloud.toast.com/image/v2.0/appkeys/';
$APPKEY = '{APP_KEY}';
$SECRETKEY = '{SECRET_KEY}';

$basepath = '/';                    // (필수) 생성할 폴더의 절대 경로, 상위 폴더 자동 생성
$file_name = 'sample.png';          // (필수) 단일 업로드할 파일명
$path = $basepath . $file_name;     // (필수) 생성할 절대 경로의 파일명

$overwrite = 'false';               // (선택) 같은 이름이 있을 경우 덮어쓰기 여부
$autorename = 'true';               // (선택) 같은 이름이 있을 경우 "이름(1).확장자" 형식으로 파일명 변경 여부
$operation_list = array('100x100'); // (선택) 이미지 오퍼레이션 ID 리스트
$callback_url='';                   // (선택) 처리 결과를 통보받을 콜백 Url 경로

$operaion_ids = '';
foreach($operation_list as $value)          // 파라미터 형식에 맞게 변경(string 형태에 콤마로 구분)
  $operaion_ids .= $value.',';   
$operaion_ids = trim($operaion_ids, ','); // 마지막 , 제거

$upload_one_file_option = array(            // 단일 파일 업로드 API 파라미터 목록
  'path' => $path,
  'overwrite' => $overwrite,
  'autorename' => $autorename,
  'operationIds' => $operaion_ids
);

$upload_multi_file_option = array(      // 다중 파일 업로드 API 파라미터 목록
    'basepath' => $basepath,
    'overwrite' => $overwrite,
    'autorename' => $autorename,
    'operationIds' => $operation_list,
    'callbackUrl' => $callback_url
  );

$response = upload_one_file($IMAGEURL, $APPKEY, $SECRETKEY, $upload_one_file_option);
echo "Response :  $response <br/><br/>\n";

#$response = upload_multi_file($IMAGEURL, $APPKEY, $SECRETKEY, $upload_multi_file_option);
echo "Response :  $response <br/><br/>\n";
