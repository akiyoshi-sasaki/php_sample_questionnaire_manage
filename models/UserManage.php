<?php
// それぞれのメソッドで、仮でデータをベタ打ちにしたり、日時を作成してたりしていますが
// 本来はデータベースから取得して返しているものをとしてみてください

class UserManage {
    public $id;

    /**
     * コンストラクタ
     * ユーザーIDを受け取る
     *
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id; // コンストラクタはクラスをオブジェクト化するタイミングで変数を受け取ったり処理をすることができる
    }

    /**
     * アンケートを配布したかどうかを返す
     *
     * @return bool
     */
    public function isDistributed() {
        $tributeStatuses = [
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => true,
            6 => false,
            7 => false,
            8 => false,
            9 => false
        ];
        return $tributeStatuses[$this->id];
    }

    /**
     * アンケートの配布状況と回答状況の配列を返す
     *
     * @return array
     */
    public function getAnsweres() {

        $datetime = new DateTime();
        $datetime->modify('-24 hours');
        $over24Hours = $datetime->format('Y-m-d H:i:s');

        $datetime->modify('+1 seconds');
        $lessThan24Hours = $datetime->format('Y-m-d H:i:s');
        
        $tributeStatuses = [
            1 => ['is_answer' => true, 'distributed_at' => $over24Hours],
            2 => ['is_answer' => true, 'distributed_at' => $lessThan24Hours],
            3 => ['is_answer' => false, 'distributed_at' => $over24Hours],
            4 => ['is_answer' => false, 'distributed_at' => $lessThan24Hours],
            5 => ['is_answer' => false, 'distributed_at' => $over24Hours],
            6 => ['is_answer' => false, 'distributed_at' => $lessThan24Hours],
            7 => ['is_answer' => false, 'distributed_at' => $over24Hours],
            8 => ['is_answer' => false, 'distributed_at' => $lessThan24Hours],
            9 => ['is_answer' => false, 'distributed_at' => $over24Hours],
        ];

        return $tributeStatuses[$this->id];
    }

    /**
     * アンケートを配布する
     *
     * @return bool
     */
    public function send() {
        // メールを送信するメソッド
        return true;
    }
}
