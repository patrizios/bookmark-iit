<?php
/**
 * Simple helper class oriented to URL
 */
class UrlHelper
{
    /**
     * [isValidUrl]
     * Check if a string is a valid URL
     *
     * @param string $url String to check
     *
     * @return boolean TRUE if valid string, FALSE if not
     */
    public static function isValidUrl($url)
    {
        // scheme
        $regex = "^(https?|ftp)\:\/\/";

        // user and pass (optional)
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";

        // http://x.xx(x) = minimum
        $regex .= "([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}";

        // port (optional)
        $regex .= "(\:[0-9]{2,5})?";

        // path (optional)
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";

        return preg_match("/$regex/siU", $url);
    }
}