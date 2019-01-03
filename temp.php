<?php
function get_token($auth_url, $tenant_id, $username, $password) {
  $url = "$auth_url/tokens";
  $req_body = array(
      'auth' => array(
          'tenantId' => $tenant_id,
          'passwordCredentials' => array(
              'username' => $username,
              'password' => $password
          )
      )
  );  // 요청 본문 생성
  $req_header = array(
    'Content-Type: application/json'
  );  // 요청 헤더 생성

  echo "URL :  $url <br/>\n";

  $curl  = curl_init($url); // curl 초기화

  curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_VERBOSE, true);
  $verbose = fopen('php://temp', 'w+');
  curl_setopt($curl, CURLOPT_STDERR, $verbose);

  curl_setopt_array($curl, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => $req_header,
    CURLOPT_POSTFIELDS => json_encode($req_body)
  )); // 파라미터 설정
  $response = curl_exec($curl); // API 호출

  if ($response === FALSE) {
    printf("cUrl error (#%d): %s<br>\n", curl_errno($curl),
           htmlspecialchars(curl_error($curl)));
    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);
    echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
}
  curl_close($curl);

  echo "Response :  $response <br/>\n";

  return $response;
}

$AUTH_URL = 'https://api-compute.cloud.toast.com/identity/v2.0';
$TENANT_ID = '';
$USERNAME = '';
$PASSWORD = '';

$token = get_token($AUTH_URL, $TENANT_ID, $USERNAME, $PASSWORD);

printf("%s\n", $token);
echo "Token : $token  <br/>\n";
?>