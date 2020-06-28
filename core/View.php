<?php
class View {
    protected $base_dir;
    protected $defaults;
    protected $layout_variables = array();

    // コンストラクタ
    // @param string $base_dir
    // @param array $defaults | ビューファイルに渡す変数のデフォルトを設定できる
    public function __construct($base_dir, $defaults = array()) {
      $this->base_dir = $base_dir;
      $this->defaults = $defaults;
    }

    // レイアウトに渡す変数を指定
    // @param string $name
    // @param mixed $value
    public function setLayoutVar($name, $value) {
      $this->layout_variables[$name] = $value;
    }

    // ビューファイルをレンダリング*
    // @param string $_path
    // @param array $_variables
    // @param mixed $_layout | レイアウトファイル名。Controllerクラスから呼び出された場合のみ必要となるので、デフォルトでfalse
    // @return string
    public function render($_path, $_variables = array(), $_layout = false) {
      $_file = $this->base_dir . '/' . $_path . '.php';
      // $defaultsプロパティに$_variablesを値として設定(今後はビューファイルから、変数にアクセス可能)
      extract(array_merge($this->defaults, $_variables));
        // ob_start: アウトプットバッファリングを開始する。
        // PHPブロックで囲まれていないHTML箇所など全て出力として扱われてしまうので、これで文字列として出力する
        // バッファリング中にechoで出力された文字列は画面には直接表示されず、内部のバッファに溜め込まれる
      ob_start();
        // バッファの自動フラッシュを無効化
      ob_implicit_flush(0);
      require $_file;
        // バッファの内容を$content変数に取り出す
      $content = ob_get_clean();
        // レイアウト読み込み
      if ($_layout) {
        $content = $this->render($_layout,
          array_merge($this->layout_variables, array(
            // バッファの内容($content)を_contentというキーにあれあれ
            // レイアウトファイルの中で $_content変数の内容を出力する事で1つのHTMLとなる
            '_content' => $content,
          )
        ));
      }
      return $content;
    }

    // 指定された値をHTMLエスケープする @param string $string | @return string
    public function escape($string) {
      return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}