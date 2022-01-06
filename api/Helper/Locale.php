<?php 

namespace Helper;

/**
 * String constants (errors, messages)
 * 
 * @package Helper
 */
class Locale {
    private string $ERR_100 = 'Undefined error';

    private string $ERR_1000 = 'Unspecified Error';
    private string $ERR_1001 = 'Request used forbidden method.';
    private string $ERR_1002 = 'Request used forbidden controller or action.';
    private string $ERR_1003 = 'Incorrect data in request.';

    private string $ERR_2000 = 'Incorrect password.';
    private string $ERR_2001 = 'Token was expired.';

    public function get(string $name): string
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }

        return $this->ERR_100;
    }
}

?>