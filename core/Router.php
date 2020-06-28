<?php

class Router {
  protected $routes;

  // コンストラクタ @param array $definitions
  public function __construct($definitions) {
    $this->routes = $this->compileRoutes($definitions);
  }

  // ルーティング定義配列を内部用に変換する (ルーティング定義配列中の動的パラメータ指定を正規表現で扱える形式にする)
  // @param array $definitions @return array
  public function compileRoutes($definitions) {
    $routes = array();
    foreach ($definitions as $url => $params) {
      // /ごとに分割する
      $tokens = explode('/', ltrim($url, '/'));
      foreach ($tokens as $i => $token) {
          // :で始まる箇所があれば正規表現の形式とする
          if (0 === strpos($token, ':')) {
              $name = substr($token, 1);
              $token = '(?P<' . $name . '>[^/]+)';
          }
          $tokens[$i] = $token;
      }
      // 分割したURLを再度/(スラッシュ)で繋げる
      $pattern = '/' . implode('/', $tokens);
      $routes[$pattern] = $params;
    }
    return $routes;
  }

  // 指定されたPATH_INFOを元にルーティングパラメータを特定する  @param string $path_info @return array|false
  // Applicationコントローラーのrunメソッド内で実行
  public function resolve($path_info) {
    // $path_infoの先頭がスラッシュでない場合、スラッシュを付与
    if ('/' !== substr($path_info, 0, 1)) {
      $path_info = '/' . $path_info;
    }

    foreach ($this->routes as $pattern => $params) {
      if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
        // マッチした場合の処理
        $params = array_merge($params, $matches); 
        return $params;
      }
    }
    return false;
  }
}
