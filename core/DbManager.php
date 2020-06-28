<?php
class DbManager {
  protected $connections = array();
  protected $repository_connection_map = array();
  protected $repositories = array();

  // データベースへ接続
  // @param string $name (接続を特定し、connectionsのキーになる)
  // @param array $params(接続必要な情報の配列)
  public function connect($name, $params) {
    $params = array_merge(array(
      'dsn'      => null,
      'user'     => '',
      'password' => '',
      'options'  => array(),
    ), $params);
    $con = new PDO(
      $params['dsn'],
      $params['user'],
      $params['password'],
      $params['options']
    );
    // 『ATTR_ERRMODE』を『ERRMODE_EXCEPTION』に設定し、PDOの内部でエラーが起きた時に例外を発生させるように
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->connections[$name] = $con;
  }

  // コネクションを取得
  // @string $name  @return PDO
  public function getConnection($name = null) {
    if (is_null($name)) {
      // current(): 配列の内部ポインタが示す値(ここでは配列の先頭の値) を取得する関数
      // つまり、指定がなければ最初に作成したPDOクラスのインスタンスを返す
      return current($this->connections);
    }
    return $this->connections[$name];
  }


  // ↑p224ここまで   ↓p225で作成


  // リポジトリごとのコネクション情報を設定
  // @param string $repository_name @param string $name
  public function setRepositoryConnectionMap($repository_name, $name) {
    $this->repository_connection_map[$repository_name] = $name;
  }

  // 指定されたリポジトリに対応するコネクションを取得 
  // @param string $repository_name @return PDO
  public function getConnectionForRepository($repository_name) {
    if (isset($this->repository_connection_map[$repository_name])) {
      $name = $this->repository_connection_map[$repository_name];
      $con = $this->getConnection($name);
    } else {
      $con = $this->getConnection();
    }
    return $con;
  }


  // ↑p225ここまで   ↓pで作成


  // リポジトリを取得、実際にインスタンスを生成
  // @param string $repository_name @return DbRepository
  public function get($repository_name) {
    if (!isset($this->repositories[$repository_name])) {
      // UserRepositoryなど
      $repository_class = $repository_name . 'Repository';
      // 実際にコネクションを行うよ
      $con = $this->getConnectionForRepository($repository_name);
      $repository = new $repository_class($con);
      $this->repositories[$repository_name] = $repository;
    }
    return $this->repositories[$repository_name];
  }

  // デストラクタ  リポジトリと接続を破棄する。(インスタンスが破棄されれば自動的に呼ばれる)
  public function __destruct() {
    foreach ($this->repositories as $repository) {
      unset($repository);
    }

    foreach ($this->connections as $con) {
      unset($con);
    }
  }
}
