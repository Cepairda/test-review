<?php

namespace Application\Classes;

class Captcha {
    public function getCaptchaCode($length)
    {
        $randomAlpha = md5(random_bytes(64));
        $captchaCode = substr($randomAlpha, 0, $length);

        return $captchaCode;
    }

    public function setSession($key, $value)
    {
        $_SESSION["$key"] = $value;
        $_SESSION['captcha_time'] = time();
    }

    function getSession($key)
    {
        @session_start();

        $value = "";

        if (!empty($key) && !empty($_SESSION["$key"])) {
            $value = $_SESSION["$key"];
        }

        return $value;
    }

    public function createCaptchaImage($captcha_code)
    {
        $target_layer = imagecreatetruecolor(72,28);
        $captcha_background = imagecolorallocate($target_layer, 204, 204, 204);
        imagefill($target_layer,0,0,$captcha_background);
        $captcha_text_color = imagecolorallocate($target_layer, 0, 0, 0);
        imagestring($target_layer, 5, 10, 5, $captcha_code, $captcha_text_color);

        return $target_layer;
    }

    public function renderCaptchaImage($imageData)
    {
        if ($this->validateCaptchaTime()) {
            header("Content-type: image/jpeg");
            imagejpeg($imageData);
        }
    }

    public function validateCaptcha($formData)
    {
        $isValid = false;
        $captchaSessionData = $this->getSession("captcha_code");

        if ($captchaSessionData == $formData) {
            $isValid = true;
        }

        return $isValid;
    }

    public function validateCaptchaTime()
    {
        $isValid = false;
        $captchaSessionData = $this->getSession("captcha_time");

        if (time() - $captchaSessionData <= 60) {
            $isValid = true;
        }

        return $isValid;
    }
}

