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


    /**
     * [combineUrl]
     * Combine an absolute URL with a relative URL
     * es.
     * absolute : http://www.example.com
     * relative : ../page.html
     *
     * result : http://www.example.com/page.html
     *
     * TODO:
     * This method is a mess (NPath complexity 288), need refactoring!
     *
     * @param string $absolute Absolute URL part
     * @param string $relative Relative URL part
     *
     * @return string Combined URL
     */
    public static function combineUrl($absolute, $relative)
    {
        $parserd_url = parse_url($relative);

        if (isset($parserd_url["scheme"])) {
            return $relative;
        }

        extract(parse_url($absolute));

        if (!isset($path))
            return "$scheme://$host/$relative";

        $path = dirname($path);

        if ($relative{0} == '/') {
            $cparts = array_filter(explode("/", $relative));
        } else {

            $aparts = array_filter(explode("/", $path));
            $rparts = array_filter(explode("/", $relative));
            $cparts = array_merge($aparts, $rparts);

            foreach ($cparts as $i => $part) {

                if($part == '.')
                    $cparts[$i] = null;

                if ($part == '..') {
                    $cparts[$i - 1] = null;
                    $cparts[$i] = null;
                }
            }

            $cparts = array_filter($cparts);
        }

        $path   = implode("/", $cparts);
        $url    = "";

        if (isset($scheme)) {
            $url = "$scheme://";
        }

        if (isset($user)) {
            $url .= "$user";

            if($pass)
                $url .= ":$pass";

            $url .= "@";
        }

        if (isset($host)) {
            $url .= "$host/";
        }


        $url .= $path;

        return $url;
    }
}