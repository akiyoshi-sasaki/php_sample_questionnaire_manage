<?php
// このコントローラーは社員へのアンケートを管理するものです、以下が処理の流れです
// 1. 全社員のIDを取得する（ただし退社済みを除く）
// 2. アンケートを配布していなければ配布する
// 3. アンケートに回答していない、かつ前回の配布から一定時間以上経過してれば再配布する
// php_sample_user_manageフォルダの中で「php controllers/questionnaire_controller.php」などで実行します
// viewファイルはありません、またmodelファイルは本来DBから取得するデータをベタ打ちしています

// ===========================================================================

require './models/User.php'; // modelファイルを読み込む
require './models/UserManage.php'; // modelファイルを読み込む

$user = new User(); // Userクラスをインスタンス化する
$userIds = $user->getAllUserIds(1); // 引数を1にすることで退社した社員を含まない

foreach ($userIds as $id) {
    $userManage = new UserManage($id); // Userクラスをインスタンス化する
    // 【上記のポイント】ループごとに作られる$userManageオブジェクトが、それぞれ1社員のオブジェクトになる

    $invited = $userManage->isDistributed(); // アンケートを配布済みかどうか
    if ($invited === false) { // アンケートをそもそも配布していなかったら配布する
        $userManage->send();
        echo "ユーザーID：" . $id  . "にアンケートを配布しました\n";
        continue; 
    }

    $answers = $userManage->getAnsweres(); // アンケートに回答済みかどうか、いつ回答したかなどを配列で取得

    $distributedAt = $answers['distributed_at']; // 前回の配布日時を取得
    $distributedAtDateime = new DateTime($distributedAt); // DateTimeオブジェクトに変換
    $currentDatetime = new DateTime(); // 現在時刻を作成
    $intervalInSeconds = $currentDatetime->getTimestamp() - $distributedAtDateime->getTimestamp(); // 経過時間を「秒」で取得

    if ($answers['is_answer'] === false && $intervalInSeconds >= 86400) { // 未回答かつ前回の配布から24時間以上経過したか
        $userManage->send();
        echo "ユーザーID：" . $id  . "にアンケートを再配布しました\n";
        continue; 
    }

    echo "ユーザーID：" . $id  . "はすでにアンケートに回答済み、もしくは前回の配布から24時間経過していません\n";
}

// 課題：
// Q1.1つ目のcontinueと2つ目のcontinueのそれぞれの役割は何か、無いとどうなるか
// Q2.それぞれの変数の意図は？なぜそういう変数名にした？
// 　　・isXXXからはじまる変数
//    ・XXXAt、XXDatetime、XXXsで終わる変数
// Q3.「24時間経過したか」を確認するプログラムはもっと短くならないか
// Q4.「前回の配布から24時間以上経過していたら再配布」の仕様が「前回の配布から24時間以内であれば再配布しない」
// 　　という仕様に変わった場合はコードの修正が必要か？
// Q5.「24時間」という閾値が「36時間」に変更された場合、何箇所の修正が必要か（コメントアウトも含む）
//　　　またその修正を1箇所にするにはどうすればいいか
// Q6.今は取得していなかった「退職済みユーザー」を取得対象にし、退職済みの場合に「退職済みのためスキップ」と表示させるにはどうするか
// 