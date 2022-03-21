<?php

    namespace Kerwin\Captcha;

    use Symfony\Component\HttpFoundation\Session\Session;

    class Captcha
    {        
        /**
         * \Symfony\Component\HttpFoundation\Session\Session
         */
        protected $session;
                
        /**
         * 驗證碼
         *
         * @var string
         */
        protected $code = '';

        public function __construct($bag = null) {
            $this->session = $this->setSession($bag);
        }
                
        /**
         * 取得圖片驗證碼
         *
         * @param string $type 文字類型
         * @param  int $nums 字數
         * @param  int $width 圖片寬度
         * @param  int $high 圖片長度
         * @return void
         */
        public function getImageCode(string $type, int $nums, int $width, int $high): void
        {
            $this->image($type, $nums, $width, $high);
        }
        
        /**
         * 生成圖片驗證碼
         *
         * @param string $type 文字類型
         * @param int $nums 字數
         * @param int $width 圖片寬度
         * @param int $high 圖片長度
         * @return void
         */
        private function image(string $type, int $nums, int $width, int $high): void
        {
            $code = $this->code($type, $nums);

            $this->session->set('captcha', $code);

            //建立圖示，設置寬度及高度與顏色等等條件
            $image = imagecreate($width, $high);
            $black = imagecolorallocate($image, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
            $borderColor = imagecolorallocate($image, 255, 255, 255);
            $backgroundColor = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

            //建立圖示背景
            imagefilledrectangle($image, 0, 0, $width, $high, $backgroundColor);

            //建立圖示邊框
            imagerectangle($image, 0, 0, $width-1, $high-1, $borderColor);

            //在圖示布上隨機產生大量躁點
            for ($i = 0; $i < 100; $i++) {
                imagesetpixel($image, rand(0, $width), rand(0, $high), $black);
            }

            $font = __DIR__ .'/fonts/captcha'.rand(0, 5).'.ttf';

            $strx = rand(5, 18);
            for ($i = 0; $i < $nums; $i++) {
                $strpos = rand(20, 25);
                imagettftext($image, 20, 0, $strx, $strpos, $black, $font, substr($code, $i, 1));
                $strx += rand(10, 30);
            }

            imagepng($image);
            imagedestroy($image);
        }
        
        /**
         * 生成驗證碼文字
         *
         * @param int $type 驗證碼文字類型
         * @param int $nums 字數
         * @return string
         */
        private function code(int $type, int $nums): string
        {
            //去除了數字0和1 字母小寫O和L，為了避免辨識不清楚
            switch ($type) {
                case '1':
                    $str = "0123456789";
                    break;
                case '2':
                    $str = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMOPQRSTUBWXYZ";
                    break;
                case '3':
                    $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMOPQRSTUBWXYZ";
                    break;
                default:
                    $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMOPQRSTUBWXYZ";
                    break;
            }

            for ($i = 0; $i < $nums; $i++) {
                $this->code .= $str[mt_rand(0, strlen($str)-1)];
            }

            return $this->code;
        }
             
        /**
         * 設定session，可以自行定義sessionbag名稱等參數
         *
         * @param  string $bag
         * @param  array  $options
         * @param  mixed  $handler
         * @return void
         */
        private function setSession(string $bag, $options = [], $handler = null)
        {
            if ($bag === null) {
                return new Session();
            }
            $attribute = new \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag($bag);
            $flash = new \Symfony\Component\HttpFoundation\Session\Flash\FlashBag($bag);
            $meta = new \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag($bag);
            $session = new \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage($options, $handler, $meta);
            return new Session($session, $attribute, $flash);
        }
    }
    