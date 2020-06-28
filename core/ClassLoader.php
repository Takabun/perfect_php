<?php
class ClassLoader {
  protected $dirs;
  // 自身をPHPのオートロードスタックに登録
  public function register() {
    // loadClassメソッドを呼ぶ
      spl_autoload_register(array($this, 'loadClass'));
  }
  // オートロード対象のディレクトリを登録 @param string
  public function registerDir($dir) {
      $this->dirs[] = $dir;
  }
  // オートロードの実行時にクラスを読み込む @param string
  public function loadClass($class) {
      foreach ($this->dirs as $dir) {
          $file = $dir . '/' . $class . '.php';
          if (is_readable($file)) {
              require $file;
              return;
          }
      }
  }
}