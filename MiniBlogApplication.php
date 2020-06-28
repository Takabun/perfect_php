<?php

// Applicationクラスの子クラス
class MiniBlogApplication extends Application {
    //ログインが必要になった時にsigninアクションが実行されるようになった(Application.phpの131行目あたり)
    protected $login_action = array('account', 'signin');
    // ルートディレクトリへのパスを返す(当アプリではここ自身がRoot)
    public function getRootDir(){
      return dirname(__FILE__);
    }

    protected function registerRoutes() {
      return array(
        '/'
            => array('controller' => 'status', 'action' => 'index'),
        '/status/post'
            => array('controller' => 'status', 'action' => 'post'),
        '/user/:user_name'
            => array('controller' => 'status', 'action' => 'user'),
        '/user/:user_name/status/:id'
            => array('controller' => 'status', 'action' => 'show'),
        '/account'
            => array('controller' => 'account', 'action' => 'index'),
        '/account/:action'
            => array('controller' => 'account'),
        '/follow'
            => array('controller' => 'account', 'action' => 'follow'),
      );
    }

    protected function configure() {
      $this->db_manager->connect('master', array(
        'dsn'      => 'mysql:dbname=mini_blog;host=localhost',
        'user'     => 'root',
        'password' => '',
      ));
    }
}