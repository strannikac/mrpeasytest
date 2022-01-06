<?php 

namespace Helper;

/**
 * This helper contains some common methods for system
 * 
 * @package Helper
 */
class System {

    /**
     * clean string
     * @param string
     * @return string
     */
    public static function cleanString(string $str): string
    {
        $str = strip_tags($str);
        $str = str_replace(' ', '-', $str);
        $str = preg_replace('/[^A-Za-z0-9\-_]/', '', $str);

        return $str;
    }

    /**
     * crypt string
     * @param string
     * @return string
     */
    public static function hash(string $str): string
    {
        return password_hash($str, PASSWORD_DEFAULT);
    }

    /**
     * create token
     * @return string
     */
    public static function createToken(): string
    {
        return substr(md5(mt_rand()), 0, 8) . "-" . substr(md5(mt_rand()), 0, 8) . "-" . substr(md5(mt_rand()), 0, 8) . "-" . substr(md5(mt_rand()), 0, 8) . '-' . time();
    }

    /**
     * get correct ip 
     * @return string or null
     */
    public static function getIP(): ?string 
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
			return $_SERVER['HTTP_CF_CONNECTING_IP'];
		} else if(isset ($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
			return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
		} else if(isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} else if(isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
        }
        
        return null;
    }
}

?>