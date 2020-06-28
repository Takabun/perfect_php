<?php

require 'core/ClassLoader.php';

$loader = new ClassLoader();
// 今回はcoreディレクトリとmodelsディレクトリからクラスを読み込むよ
// ココで、coreとmodels内のモジュールを読み込むよ！
$loader->registerDir(dirname(__FILE__).'/core');
$loader->registerDir(dirname(__FILE__).'/models');
$loader->register();
