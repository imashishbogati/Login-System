<?php
require 'dbh.inc.php';
if (isset($_POST['login-submit'])) {
    $mailuid = $_POST['mailuid'];
    $password = $_POST['pwd'];

    if (empty($mailuid) || empty($password)) {
        header("Location: ../index.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE uidUsers = ? OR emailUsers = ?";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location : ../index.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($password, $row['pwdUsers']);
                if ($pwdCheck == false) {
                    header("Location: ../index.php?error=wrongpwd");
                    exit();
                } else if ($pwdCheck == true) {
                    echo $row['idUsers'];
                    echo $row['uidUsers'];
                    session_start();
                    $_SESSION['userId'] = $row['idUsers'];
                    $_SESSION['uidUsers'] = $row['uidUsers'];

                    header("Location: ../index.php?login=success");
                    exit();
                } else {
                    echo "Wrong password";
                    header("Location: ../index.php?error=wrongpwd");
                    exit();
                }
            } else {
                echo "No users";
                header("Location: ../index.php?error=nousers");
                exit();
            }
        }
    }
} else {
    header("Location : ../index.php");
    exit();
}
