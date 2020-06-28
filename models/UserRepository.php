<?php

class UserRepository extends DbRepository {
  public function insert($user_name, $password) {
    $password = $this->hashPassword($password);
    $now = new DateTime();
    $sql = "
      INSERT INTO user(user_name, password, created_at)
      VALUES(:user_name, :password, :created_at)
    ";
    $stmt = $this->execute($sql, array(
      ':user_name'  => $user_name,
      ':password'   => $password,
      ':created_at' => $now->format('Y-m-d H:i:s'),
    ));
  }

  public function hashPassword($password){
    // 実務ではSecretKey部分をランダムな文字列とする
    // 今回はハッシュ化にSHA-1を使用したが、もっと強度の強いハッシュ関数を用いるのもOK
    return sha1($password . 'SecretKey');
  }

  // ユーザーIDからレコードを取得する
  public function fetchByUserName($user_name) {
    $sql = "SELECT * FROM user WHERE user_name = :user_name";
    return $this->fetch($sql, array(':user_name' => $user_name));
  }

  // ユーザーIDの重複を調べる
  public function isUniqueUserName($user_name) {
    $sql = "SELECT COUNT(id) as count FROM user WHERE user_name = :user_name";
    $row = $this->fetch($sql, array(':user_name' => $user_name));
    if ($row['count'] === '0') {
        return true;
    }
    return false;
}

  public function fetchAllFollowingsByUserId($user_id){
    $sql = "
    SELECT u.*
    FROM user u
    LEFT JOIN following f ON f.following_id = u.id
    WHERE f.user_id = :user_id
    ";
    return $this->fetchAll($sql, array(':user_id' => $user_id));
  }
}
