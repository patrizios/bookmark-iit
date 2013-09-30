<?php
/**
 * Helper for simple text manipulation
 */
class TextHelper {

    /**
     * [shortenize]
     * Cut a string for display purpose
     *
     * @param string  $string     String to cut
     * @param numeric $max_length String max lenght
     * @param string  $string_end String to add ad the end of result
     *
     * @return string             String shortened
     */
    public static function shortenize(
        $string,
        $max_length,
        $string_end = null
    ) {
        if (!is_numeric($max_length)) {
            return false;
        }

        if (strlen($string) > $max_length) {
            $sub_string = substr($string, 0, $max_length);

            if ($string_end) {
                $sub_string .= $string_end;
            }

            return $sub_string;

        } else {
            return $string;
        }
    }
}