<?php
/**
 * class.ico.php
 *
 * @(#) $Header: /home/jeph/repository/classes/ico/class.ico.php,v 0.1 2005/06/08 15:12:24 jeph Exp $
 **/

/**
 * Class Ico
 * Open ICO files and extract any size/depth to PNG format
 *
 * @author Diogo Resende <me@diogoresende.net>
 * @version 0.1
 *
 * @method public  Ico($path = '')
 * @method public  LoadFile($path)
 * @method private LoadData($data)
 * @method public  TotalIcons()
 * @method public  GetIconInfo($index)
 * @method public  GetIcon($index)
 * @method private allocate_color(&$im, $red, $green, $blue, $alpha = 0)
 **/
class Ico extends CApplicationComponent
{
    /**
     * Ico::bgcolor
     * Background color on icon extraction
     *
     * @type array(R, G, B) = array(255, 255, 255)
     * @var  public
     **/
    var $bgcolor = array(255, 255, 255);

    /**
     * Ico::bgcolor_transparent
     * Is background color transparent?
     *
     * @type boolean = false
     * @var  public
     **/
    var $bgcolor_transparent = true;

    private $_filename;
    public $formats;
    public $ico;

    /**
     * [__construct()]
     * Class constructor
     *
     * @param string $path Path to ICO file
     *
     * @return  void
     **/
    function __construct($path = '')
    {
        if (strlen($path) > 0)
            $this->load_file($path);

        return $this;
    }

    /**
     * Ico::load_file()
     * Load an ICO file (don't need to call this is if fill the
     * parameter in the class constructor)
     *
     * @param string $path Path to ICO file
     *
     * @return boolean Success
     **/
    function load_file($path) {
        $this->_filename = $path;

        if (($fp = @fopen($path, 'rb')) !== false)
        {
            $data = '';

            while (!feof($fp)) {
                $data .= fread($fp, 4096);
            }
            fclose($fp);

            return $this->load_data($data);
        }
        return false;
    }

    function finfo($image_data) {
        $file_info = finfo_open();
        $type = finfo_buffer($file_info, $image_data);
        return $type;
    }

    function get_mimetype($file_path) {
        return mime_content_type ($file_path);
    }

    function get_type($file_path) {

        $mimetype = $this->get_mimetype($file_path);

        $image_type = array(
            'image/png'                 => 'png',
            'image/jpeg'                => 'jpg',
            'image/gif'                 => 'gif',
            'image/bmp'                 => 'bmp',
            'image/vnd.microsoft.icon'  => 'ico',
            'image/x-ico'               => 'ico',
            'image/tiff'                => 'tiff',
            'image/svg+xml'             => 'svg',
        );
        return isset($image_type[$mimetype]) ? $image_type[$mimetype] : false ;
    }

    /**
     * Ico::LoadData()
     * Load an ICO data. If you prefer to open the file
     * and return the binary data you can use this function
     * directly. Otherwise use LoadFile() instead.
     *
     * @param   string   $data   Binary data of ICO file
     * @return  boolean       Success
     **/
    function load_data($data) {
        $this->formats = array();

        /**
         * ICO header
         **/
        $icodata    = unpack("SReserved/SType/SCount", $data);
        $this->ico  = $icodata;
        $data       = substr($data, 6);

        /**
         * Extract each icon header
         **/
        for ($i = 0; $i < $this->ico['Count']; $i ++)
        {
            $icodata = unpack("CWidth/CHeight/CColorCount/CReserved/SPlanes/SBitCount/LSizeInBytes/LFileOffset", $data);
            $icodata['FileOffset'] -= ($this->ico['Count'] * 16) + 6;

            if ($icodata['ColorCount'] == 0) $icodata['ColorCount'] = 256;
                $this->formats[] = $icodata;

            $data = substr($data, 16);
        }

        /**
         * Extract aditional headers for each extracted icon header
         **/
        for ($i = 0; $i < count($this->formats); $i++)
        {
            $icodata = unpack("LSize/LWidth/LHeight/SPlanes/SBitCount/LCompression/LImageSize/LXpixelsPerM/LYpixelsPerM/LColorsUsed/LColorsImportant", substr($data, $this->formats[$i]['FileOffset']));

            $this->formats[$i]['header']    = $icodata;
            $this->formats[$i]['colors']    = array();
            $this->formats[$i]['BitCount']  = $this->formats[$i]['header']['BitCount'];

            switch ($this->formats[$i]['BitCount'])
            {
                case 32:
                case 24:
                    $length = $this->formats[$i]['header']['Width'] * $this->formats[$i]['header']['Height'] * ($this->formats[$i]['BitCount'] / 8);
                    $this->formats[$i]['data'] = substr($data, $this->formats[$i]['FileOffset'] + $this->formats[$i]['header']['Size'], $length);
                    break;
                case 8:
                case 4:
                    $icodata    = substr($data, $this->formats[$i]['FileOffset'] + $icodata['Size'], $this->formats[$i]['ColorCount'] * 4);
                    $offset     = 0;

                    for ($j = 0; $j < $this->formats[$i]['ColorCount']; $j++)
                    {
                        $this->formats[$i]['colors'][] = array(
                            'red'       => ord($icodata[$offset]),
                            'green'     => ord($icodata[$offset + 1]),
                            'blue'      => ord($icodata[$offset + 2]),
                            'reserved'  => ord($icodata[$offset + 3])
                        );
                        $offset += 4;
                    }

                    $length = $this->formats[$i]['header']['Width'] * $this->formats[$i]['header']['Height'] * (1 + $this->formats[$i]['BitCount']) / $this->formats[$i]['BitCount'];
                    $this->formats[$i]['data'] = substr($data, $this->formats[$i]['FileOffset'] + ($this->formats[$i]['ColorCount'] * 4) + $this->formats[$i]['header']['Size'], $length);

                    break;
                case 1:
                    $icodata    = substr($data, $this->formats[$i]['FileOffset'] + $icodata['Size'], $this->formats[$i]['ColorCount'] * 4);

                    $this->formats[$i]['colors'][] = array(
                        'blue'      => ord($icodata[0]),
                        'green'     => ord($icodata[1]),
                        'red'       => ord($icodata[2]),
                        'reserved'  => ord($icodata[3])
                    );

                    $this->formats[$i]['colors'][] = array(
                        'blue'      => ord($icodata[4]),
                        'green'     => ord($icodata[5]),
                        'red'       => ord($icodata[6]),
                        'reserved'  => ord($icodata[7])
                    );

                    $length = $this->formats[$i]['header']['Width'] * $this->formats[$i]['header']['Height'] / 8;
                    $this->formats[$i]['data'] = substr($data, $this->formats[$i]['FileOffset'] + $this->formats[$i]['header']['Size'] + 8, $length);

                    break;
            }
            $this->formats[$i]['data_length'] = strlen($this->formats[$i]['data']);
        }
        return true;
    }

    /**
     * Ico::total_icons()
     * Return the total icons extracted at the moment
     *
     * @return  integer   Total icons
     **/
    function total_icons() {
        return count($this->formats);
    }

    /**
     * Ico::get_info()
     * Return the icon header corresponding to that index
     *
     * @param   integer   $index    Icon index
     * @return  resource            Icon header
     **/
    function get_info($index) {
        if (isset($this->formats[$index]))
            return $this->formats[$index];

        return false;
    }

    /**
     * Ico::set_bg()
     * Changes background color of extraction. You can set
     * the 3 color components or set $red = '#xxxxxx' (HTML format)
     * and leave all other blanks.
     *
     * @param   optional   integer   $red    Red component
     * @param   optional   integer   $green   Green component
     * @param   optional   integer   $blue  Blue component
     * @return           void
     **/
    function set_bg($red = 255, $green = 255, $blue = 255)
    {
        if (is_string($red) && preg_match('/^\#[0-9a-f]{6}$/', $red)) {
            $green  = hexdec($red[3] . $red[4]);
            $blue   = hexdec($red[5] . $red[6]);
            $red    = hexdec($red[1] . $red[2]);
        }

        $this->bgcolor = array($red, $green, $blue);
    }

    /**
     * Ico::set_bg_transparent()
     * Set background color to be saved as transparent
     *
     * @param   optional    boolean     $is_transparent     Is Transparent or not
     * @return              boolean                         Is Transparent or not
     **/
    function set_bg_transparent($is_transparent = true) {
        return ($this->bgcolor_transparent = $is_transparent);
    }

    /**
     * Ico::GetImage()
     * Return an image resource with the icon stored
     * on the $index position of the ICO file
     *
     * @param   integer     $index  Position of the icon inside ICO
     * @return  resource            Image resource
     **/
    function get_icon($index) {

        if (is_null($index)) {
            $index = count($this->formats)-1;
        }

        if (!isset($this->formats[$index])) {
            return false;
        }

        /**
         * create image
         **/
        $im = imagecreatetruecolor($this->formats[$index]['Width'], $this->formats[$index]['Height']);

        /**
         * paint background
         **/
        $bgcolor = $this->allocate_color($im, $this->bgcolor[0], $this->bgcolor[1], $this->bgcolor[2]);
        imagefilledrectangle($im, 0 , 0, $this->formats[$index]['Width'], $this->formats[$index]['Height'], $bgcolor);

        /**
         * set background color transparent
         **/
        if ($this->bgcolor_transparent) {
            imagecolortransparent($im, $bgcolor);
        }

        /**
         * allocate pallete and get XOR image
         **/
        if (in_array($this->formats[$index]['BitCount'], array(1, 4, 8, 24))) {
            if ($this->formats[$index]['BitCount'] != 24) {
                /**
                 * color pallete
                 **/
                $c = array();
                for ($i = 0; $i < $this->formats[$index]['ColorCount']; $i++) {
                    $c[$i] = $this->allocate_color($im, $this->formats[$index]['colors'][$i]['blue'],
                                                        $this->formats[$index]['colors'][$i]['green'],
                                                        $this->formats[$index]['colors'][$i]['red'],
                                                        round($this->formats[$index]['colors'][$i]['reserved'] / 255 * 127));
                }
            }

            /**
             * XOR image
             **/
            $width = $this->formats[$index]['Width'];

            if (($width % 32) > 0) {
                $width += (32 - ($this->formats[$index]['Width'] % 32));
            }

            $offset         = $this->formats[$index]['Width'] * $this->formats[$index]['Height'] * $this->formats[$index]['BitCount'] / 8;
            $total_bytes    = ($width * $this->formats[$index]['Height']) / 8;

            $bits   = '';
            $bytes  = 0;

            $bytes_per_line     = ($this->formats[$index]['Width'] / 8);
            $bytes_to_remove    = (($width - $this->formats[$index]['Width']) / 8);

            for ($i = 0; $i < $total_bytes; $i++)
            {
                $bits .= str_pad(decbin(ord($this->formats[$index]['data'][$offset + $i])), 8, '0', STR_PAD_LEFT);
                $bytes++;

                if ($bytes == $bytes_per_line) {
                    $i += $bytes_to_remove;
                    $bytes = 0;
                }
            }
        }

        /**
         * paint each pixel depending on bit count
         **/
        switch ($this->formats[$index]['BitCount']) {
            case 32:
                /**
                 * 32 bits: 4 bytes per pixel [ B | G | R | ALPHA ]
                 **/
                $offset = 0;
                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--)
                {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        $color = substr($this->formats[$index]['data'], $offset, 4);
                        if (ord($color[3]) > 0) {
                            $c = $this->allocate_color($im, ord($color[2]),
                                                           ord($color[1]),
                                                           ord($color[0]),
                                                           127 - round(ord($color[3]) / 255 * 127));
                            imagesetpixel($im, $j, $i, $c);
                        }
                        $offset += 4;
                    }
                }
                break;
            case 24:
                /**
                 * 24 bits: 3 bytes per pixel [ B | G | R ]
                 **/
                $offset     = 0;
                $bitoffset  = 0;

                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {

                        if ($bits[$bitoffset] == 0) {
                            $color  = substr($this->formats[$index]['data'], $offset, 3);
                            $c      = $this->allocate_color($im, ord($color[2]), ord($color[1]), ord($color[0]));
                            imagesetpixel($im, $j, $i, $c);
                        }

                        $offset += 3;
                        $bitoffset++;
                    }
                }
                break;
            case 8:
                /**
                 * 8 bits: 1 byte per pixel [ COLOR INDEX ]
                 **/
                $offset = 0;
                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        if ($bits[$offset] == 0) {
                            $color = ord(substr($this->formats[$index]['data'], $offset, 1));
                            imagesetpixel($im, $j, $i, $c[$color]);
                        }
                        $offset++;
                    }
                }
                break;
            case 4:
                /**
                 * 4 bits: half byte/nibble per pixel [ COLOR INDEX ]
                 **/
                $offset     = 0;
                $maskoffset = 0;
                $leftbits   = true;

                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {

                        if ($leftbits)
                        {
                            $color = substr($this->formats[$index]['data'], $offset, 1);
                            /*
                            var_dump(str_pad(decbin(ord($color)), 8, '0', STR_PAD_RIGHT) );
                            echo '<br/>';

                            var_dump($c); */

                            $color = array(
                                'High'  => bindec(substr(
                                    str_pad(decbin(ord($color)), 8, '0', STR_PAD_LEFT)
                                    , 0, 4)),
                                'Low'   => bindec(substr(
                                    str_pad(decbin(ord($color)), 8, '0', STR_PAD_LEFT)
                                    , 4)),
                            );
                            /*
                            $color = array(
                                'High'  => bindec(substr(decbin(ord($color)), 0, 4)),
                                'Low'   => bindec(substr(decbin(ord($color)), 4)),
                            );
                            */

                            if ($bits[$maskoffset++] == 0) {
                                imagesetpixel($im, $j, $i, $c[$color['High']]);
                            }

                            $leftbits = false;
                        } else {

                            if ($bits[$maskoffset++] == 0) {
                                imagesetpixel($im, $j, $i, $c[$color['Low']]);
                            }
                            $leftbits = true;
                            $offset++;
                        }
                        //var_dump($j, $i, dechex($c[$color['High']]), dechex($c[$color['Low']]));
                        //echo '<br/>';
                    }
                }
                break;
            case 1:
                /**
                 * 1 bit: 1 bit per pixel (2 colors, usually black&white) [ COLOR INDEX ]
                 **/
                $colorbits  = '';
                $total      = strlen($this->formats[$index]['data']);

                for ($i = 0; $i < $total; $i++) {
                    $colorbits .= str_pad(decbin(ord($this->formats[$index]['data'][$i])), 8, '0', STR_PAD_LEFT);
                }

                $total  = strlen($colorbits);
                $offset = 0;

                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        if ($bits[$offset] == 0) {
                            imagesetpixel($im, $j, $i, $c[$colorbits[$offset]]);
                        }
                        $offset++;
                    }
                }
                break;
        }

        return $im;
    }

    function resize($file,$scale="",$width="",$height="")
    {
        // If they wish to scale the image.
        if (isset($scale))
        {
            // Create our image object from the image.
            $fullImage = imagecreatefrompng($file);

            // Get the image size, used in calculations later.
            $fullSize = getimagesize($file);

            // Create our thumbnail size, so we can resize to this, and save it.
            $tnImage = imagecreatetruecolor($fullSize[0]/$scale, $fullSize[1]/$scale);

            // Resize the image.
            imagecopyresampled($tnImage, $fullImage, 0, 0, 0, 0, $fullSize[0]/$scale,$fullSize[1]/$scale,$fullSize[0],$fullSize[1]);
            // Create a new image thumbnail.
            imagepng($tnImage, $file . '3');

            // Clean Up.
            imagedestroy($fullImage);
            imagedestroy($tnImage);
            // Return our new image.

        }



    }


    /**
     * Ico::allocate_color()
     * Allocate a color on $im resource. This function prevents
     * from allocating same colors on the same pallete. Instead
     * if it finds that the color is already allocated, it only
     * returns the index to that color.
     * It supports alpha channel.
     *
     * @param              resource $im    Image resource
     * @param              integer   $red     Red component
     * @param              integer   $green Green component
     * @param              integer   $blue   Blue component
     * @param   optional    integer  $alphpa   Alpha channel
     * @return            integer              Color index
     **/
    function allocate_color(&$im, $red, $green, $blue, $alpha = 0) {
        $c = imagecolorexactalpha($im, $red, $green, $blue, $alpha);

        if ($c >= 0)
            return $c;

        return imagecolorallocatealpha($im, $red, $green, $blue, $alpha);
    }

    /*********************************************/
    /* Fonction: ImageCreateFromBMP              */
    /* Author:   DHKold                          */
    /* Contact:  admin@dhkold.com                */
    /* Date:     The 15th of June 2005           */
    /* Version:  2.0B                            */
    /*********************************************/

    function imagecreatefrombmp($filename)
    {
        //Ouverture du fichier en mode binaire
        if (! $f1 = fopen($filename,"rb"))
            return FALSE;

        //1 : Chargement des ent�tes FICHIER
        $file = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));

        if ($file['file_type'] != 19778)
            return FALSE;

        //2 : Chargement des ent�tes BMP
        $bmp = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
                    '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
                    '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));

        $bmp['colors'] = pow(2,$bmp['bits_per_pixel']);

        if ($bmp['size_bitmap'] == 0)
            $bmp['size_bitmap'] = $file['file_size'] - $file['bitmap_offset'];

        $bmp['bytes_per_pixel']     = $bmp['bits_per_pixel']/8;
        $bmp['bytes_per_pixel2']    = ceil($bmp['bytes_per_pixel']);

        $bmp['decal']   = ($bmp['width']*$bmp['bytes_per_pixel']/4);
        $bmp['decal']   -= floor($bmp['width']*$bmp['bytes_per_pixel']/4);
        $bmp['decal']   = 4-(4*$bmp['decal']);

        if ($bmp['decal'] == 4)
            $bmp['decal'] = 0;

        //3 : Chargement des couleurs de la palette
        $palette = array();
        if ($bmp['colors'] < 16777216) {
            $palette = unpack('V'.$bmp['colors'], fread($f1,$bmp['colors']*4));
        }

        //4 : Cr�ation de l'image
        $img    = fread($f1,$bmp['size_bitmap']);
        $vide   = chr(0);

        $res = imagecreatetruecolor($bmp['width'],$bmp['height']);
        $p = 0;
        $y = $bmp['height']-1;

        while ($y >= 0)
        {
            $x = 0;
            while ($x < $bmp['width'])
            {
                if ($bmp['bits_per_pixel'] == 24)
                    $color = unpack("V",substr($img,$p,3).$vide);
                elseif ($bmp['bits_per_pixel'] == 16)
                {
                    $color = unpack("n",substr($img,$p,2));
                    $color[1] = $palette[$color[1]+1];
                }
                elseif ($bmp['bits_per_pixel'] == 8)
                {
                    $color = unpack("n",$vide.substr($img,$p,1));
                    $color[1] = $palette[$color[1]+1];
                }
                elseif ($bmp['bits_per_pixel'] == 4)
                {
                    $color = unpack("n",$vide.substr($img,floor($p),1));
                    if (($p*2)%2 == 0)
                        $color[1] = ($color[1] >> 4);
                    else
                        $color[1] = ($color[1] & 0x0F);

                    $color[1] = $palette[$color[1]+1];
                }
                elseif ($bmp['bits_per_pixel'] == 1)
                {
                    $color = unpack("n",$vide.substr($img,floor($p),1));
                    if     (($p*8)%8 == 0) $color[1] =  $color[1]        >>7;
                    elseif (($p*8)%8 == 1) $color[1] = ($color[1] & 0x40)>>6;
                    elseif (($p*8)%8 == 2) $color[1] = ($color[1] & 0x20)>>5;
                    elseif (($p*8)%8 == 3) $color[1] = ($color[1] & 0x10)>>4;
                    elseif (($p*8)%8 == 4) $color[1] = ($color[1] & 0x8)>>3;
                    elseif (($p*8)%8 == 5) $color[1] = ($color[1] & 0x4)>>2;
                    elseif (($p*8)%8 == 6) $color[1] = ($color[1] & 0x2)>>1;
                    elseif (($p*8)%8 == 7) $color[1] = ($color[1] & 0x1);
                    $color[1] = $palette[$color[1]+1];
                }
                else
                    return FALSE;

                imagesetpixel($res,$x,$y,$color[1]);
                $x++;
                $p += $bmp['bytes_per_pixel'];
            }
            $y--;
            $p += $bmp['decal'];
        }

        //Fermeture du fichier
        fclose($f1);

        return $res;
    }
}
?>