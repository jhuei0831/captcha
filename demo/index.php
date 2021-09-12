<?php
    require(__DIR__.'/../vendor/autoload.php');
    use Symfony\Component\HttpFoundation\Session\Session;

    $session = new Session();

    if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
        if ($_POST['captcha'] == $session->get('captcha')) {
            echo '<script>alert("通過");</script>';
        }
        else {
            echo '<script>alert("失敗");</script>';
        }
    }
?>

<script>
    function refreshCaptcha() {
        document.getElementById("imgcode").src = "captcha.php?" +Date.now();
    }
</script>

<div>
    <form method="post">
        <img id="imgcode" src="captcha.php" onclick="refreshCaptcha()" />
        <p>可以點擊圖片更換驗證碼</p>
        <input type="text" name="captcha" autocomplete="off" placeholder="請輸入上方圖片驗證碼" required>
        <input type="submit">
    </form>
</div>

