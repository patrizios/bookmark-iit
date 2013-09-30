<?php
class ScraperFavicon extends Scraper
{
    /**
     * [$permitted_content_types]
     * Permitted content types
     * @var array
     */
    public $permitted_content_types = array(
        'image/png'                 => 'png',
        'image/jpeg'                => 'jpg',
        'image/gif'                 => 'gif',
        'image/bmp'                 => 'bmp',
        'image/x-ms-bmp'            => 'bmp',
        'image/vnd.microsoft.icon'  => 'ico',
        'image/x-ico'               => 'ico',
        'image/x-icon'              => 'ico',
        //'image/tiff'                => 'tiff',
        'image/svg+xml'             => 'svg',
    );


    public $png_width = 16;

    public $png_height = 16;

    public $delete_temp = true;

    protected $mimetype;

    /**
     * [__construct]
     *
     * @param string $url Url to scrape
     */
    public function __construct($url, $mimetype)
    {
        $this->setUrl($url);
        $this->setMimeType($mimetype);

        $this->run();

        $this->saveTempFile();
        $this->converToPng();

        if ($this->delete_temp) {
            $this->deleteTempFile();
        }
    }

    /**
     * [getSavePath]
     * Get the local path for temp favicon file
     *
     * @return string Local path for temp favicon file
     */
    public function getSavePath()
    {
        $savePath = Yii::app()->params['favicon_save_path'];
        return YiiBase::getPathOfAlias($savePath);
    }

    /**
     * [getFileName]
     * Get the name of favicon file without extension
     *
     * @return string Favicon name
     */
    public function getFileName()
    {
        return md5($this->getUrl());
    }

    /**
     * [getTempIcoPath]
     *
     * @return string Complete local path for the temp favicon file
     */
    public function getTempIcoPath()
    {
        return $this->getSavePath()
            . DIRECTORY_SEPARATOR
            . $this->getFileName()
            . '.tmp';
    }

    /**
     * [getPngIcoPath]
     *
     * @return string Complete local path for the favicon file
     */
    public function getPngIcoPath()
    {
        return $this->getSavePath()
            . DIRECTORY_SEPARATOR
            . $this->getFileName()
            . '.png';
    }

    /**
     * [saveTempFile]
     * Save the temp file on local storage
     *
     * @return null
     */
    public function saveTempFile()
    {
        $path = $this->getTempIcoPath();

        if (!file_exists($path)) {
            file_put_contents($path, $this->getResponseBody());
        }
    }

    /**
     * [deleteTempFile]
     * Delete the temp favicon file
     *
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function deleteTempFile()
    {
        return unlink($this->getTempIcoPath());
    }

    /**
     * [setMimeType]
     *
     * @param string $mimetype Set mimetype
     */
    public function setMimeType($mimetype)
    {
        $this->mimetype = $mimetype;
    }

    /**
     * [getMimeType]
     * Get the mimetype of ResponseBody
     *
     * @return string ResponseBody mimetype
     */
    public function getMimeType()
    {
        return $this->mimetype;
    }

    /**
     * [getIconType]
     * Get the type of favicon
     *
     * @param string $mimetype Mimetype of favicon
     *
     * @return string Favicon type
     */
    public function getIconType($mimetype)
    {
        if (isset($this->permitted_content_types[$mimetype])) {
            return $this->permitted_content_types[$mimetype];
        }

        return null;
    }

    /**
     * [converToPng]
     * Convert the favicon into a PNG file
     *
     * @return [type] [description]
     */
    public function converToPng()
    {
        $source_type    = $this->getIconType($this->getMimeType());
        $image_path     = $this->getTempIcoPath();

        $image = self::createImageResource(
            $source_type,
            $image_path
        );

        if (isset($image) && $image) {

            $image = self::resizeImageResource($image, 16, 16);

            if (self::savePngFromImage($image, $this->getPngIcoPath())) {
                imagedestroy($image);
                return true;
            }
        } else {
            return false;
        }
    }

    public function savePngFromImage($image_resource, $image_path)
    {
        return imagepng(
            $image_resource,
            $this->getPngIcoPath()
        );
    }

    public static function createImageResource($source_type, $image_path)
    {
        switch ($source_type) {

        case 'png':
            $image = imagecreatefrompng($image_path);
            break;

        case 'gif':
            $image = imagecreatefromgif($image_path);
            break;

        case 'ico':
            $image = self::imageCreateFromICO($image_path);
            break;

        case 'bmp':
            $ico = new Ico($image_path);
            $image = $ico->imagecreatefrombmp($image_path);
            break;

        default:
            $image = false;
            break;
        }

        return $image;
    }


    public static function imageCreateFromICO($image_path)
    {
        $ico    = new Ico($image_path);
        $icons  = array();

        for ($index = 0; $index < $ico->total_icons(); $index++) {

            $icon = $ico->get_info($index);

            $icons[$index]['width']     = $icon['Width'];
            $icons[$index]['bitcount']  = $icon['BitCount'];
        }

        asort($icons);
        $array_keys = array_keys($icons);

        // get the key of the last icon (highest quality) in the sorted list
        $best = end($array_keys);

        $image = $ico->get_icon($best);

        return $image;
    }

    public static function resizeImageResource($src_image, $width, $height)
    {
        $im_width   = imagesx($src_image);
        $im_height  = imagesy($src_image);
        $dst_image  = imagecreatetruecolor($width, $height);

        /**
         * Resize the image.
         */
        imagecopyresampled(
            $dst_image,     // dst_image Destination image link resource.
            $src_image,     // src_image Source image link resource.
            0,              // dst_x x-coordinate of destination point.
            0,              // dst_y y-coordinate of destination point.
            0,              // src_x x-coordinate of source point.
            0,              // rc_y y-coordinate of source point.
            $width,         // dst_w Destination width.
            $height,        // dst_h Destination height.
            $im_width,      // src_w Source width.
            $im_height      // src_h  Source height.
        );

        /**
         * Clean old image
         */
        imagedestroy($src_image);

        /**
         * Return new image.
         */
        return $dst_image;
    }
}