<?php
class Request {

  // リクエストメソッドがPOSTかどうか判定@return boolean
  public function isPost() {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          return true;
      }
      return false;
  }

  // GETパラメータを取得 
  // @param string $name
  // @param mixed $default 指定したキーが存在しない場合のデフォルト値
  // @return mixed
  public function getGet($name, $default = null) {
      if (isset($_GET[$name])) {
          return $_GET[$name];
      }
      return $default;
  }

  // POSTパラメータを取得
  public function getPost($name, $default = null){
      if (isset($_POST[$name])) {
          return $_POST[$name];
      }
      return $default;
  }

  // サーバーのホスト名を取得 @return string
  public function getHost() {
      if (!empty($_SERVER['HTTP_HOST'])) {
          return $_SERVER['HTTP_HOST'];
      }
      return $_SERVER['SERVER_NAME'];
  }

  // SSLでアクセスされたかどうか判定 (もしtrueの時は、"on"という文字が含まれる)
  public function isSsl() {
      if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
          return true;
      }
      return false;
  }

  //リクエストURIを取得 @return string
  // $_SERVERの中から取得
  public function getRequestUri() {  //index.php/list?foo=bar
      return $_SERVER['REQUEST_URI'];
  }


  // ↓ p212で追加

  // ベースURLを取得 @return string
  public function getBaseUrl() {
      $script_name = $_SERVER['SCRIPT_NAME']; //index.php(フロントコントローラ。)
      $request_uri = $this->getRequestUri();  //index.php/list?foo=bar

      // strpos: 第一引数に指定した文字列の中から、第二引数が最初に主告園する位置を把握
      if (0 === strpos($request_uri, $script_name)) {
          // フロントコントローラ(index.php)がURLに含まれてる時
          return $script_name;
      } else if (0 === strpos($request_uri, dirname($script_name))) { 
          // フロントコントローラが省略されている時。
          // dirname: ファイルのパス名からディレクトリ部分を抽出
          // ttrimを使う事で、/ を削除
          return rtrim(dirname($script_name), '/');
      }
      return '';
  }

  // PATH_INFOを取得 @return string
  public function getPathInfo() {
      $base_url = $this->getBaseUrl();        // index.php
      $request_uri = $this->getRequestUri();  // index.php/list?foo=bar

      if (false !== ($pos = strpos($request_uri, '?'))) {
        // ?getパラメータを取り除く(?以降の文字列を取り除く)
        $request_uri = substr($request_uri, 0, $pos);
      }
      $path_info = (string)substr($request_uri, strlen($base_url));
      return $path_info;
  }
}