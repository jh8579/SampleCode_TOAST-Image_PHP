<?php
function put_operation($IMAGEURL, $APPKEY, $SECRETKEY, $operation_id, $data) {
  echo "이미지 오퍼레이션 생성 및 수정 API <br/>";

  $url = $IMAGEURL . $APPKEY . "/operations" . "/" . $operation_id;    // url 초기화

  $json_data = json_encode($data);
  echo $json_data;

  $req_header = array(
    'Authorization: ' . $SECRETKEY,
    'Content-Type: application/json'
  );  // 요청 헤더 생성

  $curl  = curl_init($url); // curl 초기화

  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => FALSE,      // SSL 인증X
    CURLOPT_CUSTOMREQUEST => 'PUT',       // 메소드 정의
    CURLOPT_RETURNTRANSFER => TRUE,       // 리턴 값 출력
    CURLOPT_HTTPHEADER => $req_header,    // 헤더 추가
    CURLOPT_POSTFIELDS => $json_data      // json 형식 변환
  )); // curl 설정

  $response = curl_exec($curl); // API 호출
  curl_close($curl);

  return $response;
};

function get_operation($IMAGEURL, $APPKEY, $SECRETKEY, $data) {
  echo "이미지 오퍼레이션 목록 조회 API <br/>\n";

  $url = $IMAGEURL . $APPKEY . "/operations";    // url 초기화

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

function get_detail_operation($IMAGEURL, $APPKEY, $SECRETKEY, $operation_id){
    echo "이미지 오퍼레이션 상세 조회 API <br/>\n";

    $url = $IMAGEURL . $APPKEY . "/operations" . "/" .$operation_id;    // url 초기화
  
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
}

function execute_operation($IMAGEURL, $APPKEY, $SECRETKEY, $data){
    echo "이미지 오퍼레이션 실행 API <br/>\n";

  $url = $IMAGEURL . $APPKEY . "/operations-exec";    // url 초기화

  $req_header = array(
    'Authorization: ' . $SECRETKEY,
    'Content-Type: application/json'
  );  // 요청 헤더 생성

  $req_body = $data;

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
}

function delete_operation($IMAGEURL, $APPKEY, $SECRETKEY, $operation_id, $data){
    echo "이미지 오퍼레이션 삭제 API <br/>\n";
    
    $url = $IMAGEURL . $APPKEY . "/operations" . "/" . $operation_id;    // url 초기화
  
    $req_header = array(
      'Authorization: ' . $SECRETKEY
    );  // 요청 헤더 생성
  
    $req_params = '';   // 파라미터 초기화
  
    foreach($data as $key=>$value)          // 파라미터 형식에 맞게 변경
      $req_params .= $key.'='.$value.'&';   
    $req_params = trim($req_params, '&');   // 마지막 & 제거
  
    $curl  = curl_init(); // curl 초기화
  
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url.'?'.$req_params,  // URL에 파라미터 추가
      CURLOPT_SSL_VERIFYPEER => FALSE,      // SSL 인증X
      CURLOPT_CUSTOMREQUEST => "DELETE",    // 메소드 정의
      CURLOPT_RETURNTRANSFER => TRUE,       // 리턴 값 출력
      CURLOPT_HTTPHEADER => $req_header,    // 헤더 추가
    )); // curl 설정
  
    $response = curl_exec($curl); // API 호출
    curl_close($curl);
  
    return $response;
}

/////////////// git add 전에 제외!!!!!! //////////////////////////////
$IMAGEURL = 'https://api-image.cloud.toast.com/image/v2.0/appkeys/';
/////////////////////////////////////////////////////////////////////

$operation_id = '100x100';                      // (필수) 생성할 폴더의 절대 경로, 상위 폴더 자동 생성
$description = '100x100 크기 변환';                              //
$realtime_service = false;                      //
$delete_thumbnail = false;                      //
$data = array(                                  // 
            array(                              //
            'templateOperationId' => 'resize_max_fit',  
            'option' => array(
                'width' => 100,
                'height' => 100,
                'upDownSizeType' => 'downOnly'
            )
        )
);


$name = '100x100';      // (선택) 검색할 이미지 이름
$page = 1;              // (선택) 페이지 번호
$rows = 100;            // (선택) 조회 개수
$sort = 'name:asc';     // (선택) 정렬 방식 (정렬대상 : name or date, 정렬방식 : asc or desc)
$template = 'false';    // (선택) 정렬 방식 (정렬대상 : name or date, 정렬방식 : asc or desc)

$basepath = '/';
$filepaths = array('/sample.png', '/sample.jpg');
$operation_ids = array('100x100');
$callback_url = '';

$put_operation_option = array(     // 이미지 오퍼레이션 생성 및 수정 API 파라미터 목록
  'description' => $description,
  'realtimeService' => $realtime_service,
  'deleteThumbnail' => $delete_thumbnail,
  'data' => $data
);


$get_operation_option = array(     // 이미지 오퍼레이션 목록 조회 API 파라미터 목록
    'name' => $name,
    'page' => $page,
    'rows' => $rows,
    'sort' => $sort,
    'template' => $template
  );

$execute_operation_option = array(     // 이미지 오퍼레이션 실행 API 파라미터 목록
    'basepath' => $basepath,
    'filepaths' => $filepaths,
    'operationIds' => $operation_ids,
    'callbackUrl' => $callback_url
  );

$delete_operation_option = array(     // 이미지 오퍼레이션 삭제 API 파라미터 목록
    'deleteThumbnail' => $delete_thumbnail
);


$response = put_operation($IMAGEURL, $APPKEY, $SECRETKEY, $operation_id, $put_operation_option);
echo "Response :  $response <br/><br/>\n";

$response = get_operation($IMAGEURL, $APPKEY, $SECRETKEY, $get_operation_option);
echo "Response :  $response <br/><br/>\n";

$response = get_detail_operation($IMAGEURL, $APPKEY, $SECRETKEY, $operation_id);
echo "Response :  $response <br/><br/>\n";

$response = execute_operation($IMAGEURL, $APPKEY, $SECRETKEY, $execute_operation_option);
echo "Response :  $response <br/><br/>\n";

#$response = delete_operation($IMAGEURL, $APPKEY, $SECRETKEY, $operation_id, $delete_operation_option);
echo "Response :  $response <br/><br/>\n";

