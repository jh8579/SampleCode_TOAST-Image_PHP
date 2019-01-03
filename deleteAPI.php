<?php
function delete_one_file($IMAGEURL, $APPKEY, $SECRETKEY, $delete_flag, $data) {
  echo " 단일 삭제 API <br/>\n";
  
  $url = $IMAGEURL . $APPKEY . "/images/sync";    // url 초기화

  $req_header = array(
    'Authorization: ' . $SECRETKEY
  );  // 요청 헤더 생성

  if($delete_flag == 'D'){      // flag에 따른 data 처리
    unset($data['fileId']);     // 폴더 선택시 파일 ID 제거
  }else{
    unset($data['folderId']);   // 파일 선택시 폴더 ID 제거
  }

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
};

function delete_multi_file($IMAGEURL, $APPKEY, $SECRETKEY, $data){
    echo " 다중 삭제 API <br/>\n";
    
    $url = $IMAGEURL . $APPKEY . "/images/async";    // url 초기화
  
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
};

/////////////// git add 전에 제외!!!!!! //////////////////////////////
$IMAGEURL = 'https://api-image.cloud.toast.com/image/v2.0/appkeys/';
/////////////////////////////////////////////////////////////////////

$folder_id = '3fffa007-c214-47dc-a275-e79839d19bd4';      // (필수) 삭제할 폴더의 ID
$file_id = '8e49dd0b-4974-4125-93e6-a15c1cb44252';        // (필수) 삭제할 파일의 ID
$include_thumbnail = 'false';     // (선택) 삭제할 파일에 의해 생성된 오퍼레이션 파일도 삭제

//$delete_flag = 'D';         // 폴더나 파일 중 선택(단일 삭제로는 폴더와 파일 모두 삭제 불가) 
$delete_flag = 'F';       // 폴더 삭제시 'D', 파일 삭제시 'F'

$folder_list = array('5866887d', '0697a7e2');     // (필수) 삭제할 폴더의 ID 리스트
$file_list = array('eab1cdce', '31e999ee');       // (필수) 삭제할 파일의 ID 리스트

$folder_ids = '';
$file_ids = '';

foreach($folder_list as $value)          // 파라미터 형식에 맞게 변경(string 형태에 콤마로 구분)
    $folder_ids .= $value.',';   
  $folder_ids = trim($folder_ids, ','); // 마지막 , 제거

foreach($file_list as $value)           // 파라미터 형식에 맞게 변경(string 형태에 콤마로 구분)
  $file_ids .= $value.',';   
$file_ids = trim($file_ids, ',');       // 마지막 , 제거

$delete_one_file_option = array(        // 단일 삭제 파라미터 목록
  'folderId' => $folder_id,
  'fileId' => $file_id,
  'includeThumbnail' => $include_thumbnail
);

$delete_multi_file_option = array(      // 다중 삭제 파라미터 목록
    'folderIds' => $folder_ids,
    'fileIds' => $file_ids,
    'includeThumbnail' => $include_thumbnail
  );

$response = delete_one_file($IMAGEURL, $APPKEY, $SECRETKEY, $delete_flag, $delete_one_file_option);
echo "Response :  $response <br/><br/>\n";

$response = delete_multi_file($IMAGEURL, $APPKEY, $SECRETKEY, $delete_multi_file_option);
echo "Response :  $response <br/><br/>\n";
