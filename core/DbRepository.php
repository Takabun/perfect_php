<?php
abstract class DbRepository {
    protected $con;

    // コンストラクタ @param PDO $con
    public function __construct($con) {
        $this->setConnection($con);
    }

    // コネクションを設定 @param PDO $con
    public function setConnection($con) {
        $this->con = $con;
    }

    // クエリを実行
    // @param string $sql    @param array $params    @return PDOStatement $stmt
    // SQLには直接変数を入れずに、":name"のような文字列を指定する。
    // プリペアードステートメントにより、適切にエスケープされる(SQLインジェクション対策)
    public function execute($sql, $params = array()) {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // クエリを実行し、結果を1行取得
    // @param string $sql    @param array $params    @return array
    public function fetch($sql, $params = array()) {
        // PDO::FETCH_ASSOC: 取得結果を連想配列として受け取るという意味。
        // これを指定しないと、結果として取得した配列のキーがすべて数字の連番になる
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    // クエリを実行し、結果をすべて取得
    // @param string $sql    @param array $params    @return array
    public function fetchAll($sql, $params = array()) {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}