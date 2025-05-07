<?php

class User {
    public function getAllUserIds($excludingRetirement) {
        // ※本来はデータベースから取得するものだが、仮で下手打ちにしている
        if ($excludingRetirement === 1) {
            $userIDs = [1,2,3,4,6,7,8,9];
        } else {
            $userIDs = [1,2,3,4,5,6,7,8,9,10];
        }

        return $userIDs;
    }
}
