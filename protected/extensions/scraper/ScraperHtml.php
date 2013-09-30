<?php
/**
 * Specialized Scraper for HTML page
 */
class ScraperHtml extends Scraper
{
    /**
     * [$permitted_content_types]
     * Permitted content types
     * @var array
     */
    public $permitted_content_types = array(
        'text/html' => 'html'
    );

    private $_dom_document;
    private $_head_meta = array();
    private $_head_link = array();

    /**
     * [__construct]
     *
     * @param string $url Url to scrape
     */
    public function __construct($url)
    {
        parent::__construct($url);

        if ($this->isHtml()) {
            $this->initDomDocument($this->getResponseBody());
        }
    }

    /**
     * [initDomDocument]
     *
     * @param string $html HTML document
     *
     * @return null
     */
    public function initDomDocument($html)
    {
        $this->_dom_document = new DOMDocument();
        @$this->_dom_document->loadHTML($html);
    }

    /**
     * [isHtml]
     * Check if content type of the response is HTML
     *
     * @return boolean TRUE if HTML, FALSE if not
     */
    public function isHtml()
    {
        $content_type = $this->getResponseContenType();

        if ($content_type == 'text/html') {
            return true;
        }

        return false;
    }

    /**
     * [getTitle]
     * Get the title of the HTML document
     *
     * @return string Document title
     */
    public function getTitle()
    {
        $nodes = $this->_dom_document->getElementsByTagName('title');
        return $nodes->item(0)->nodeValue;
    }

    /**
     * [getMetas]
     * Get the meta tags of the HTML document
     *
     * @return array Meta tags array
     */
    public function getMetas()
    {
        $dom_metas = $this->_dom_document->getElementsByTagName('meta');

        for ($i = 0; $i < $dom_metas->length; $i++) {

            $meta = $dom_metas->item($i);

            $attribute_name     = $meta->getAttribute('name');
            $attribute_content  = $meta->getAttribute('content');

            $this->_head_meta[$attribute_name] = $attribute_content;
        }

        return $this->_head_meta;
    }

    /**
     * [getMeta]
     * Get a specific meta tag
     *
     * @param string $name Meta tag name
     *
     * @return array Meta tag array
     */
    public function getMeta($name)
    {
        $meta = $this->getMetas();

        if (isset($meta[$name])) {
            return $meta[$name];
        }

        return null;
    }

    /**
     * [getHeadLinks]
     * Get all the head links of the HTML document
     *
     * @return array Head links array
     */
    public function getHeadLinks()
    {
        $dom_links = $this->_dom_document->getElementsByTagName('link');

        for ($i = 0; $i < $dom_links->length; $i++) {

            $link = $dom_links->item($i);

            $attribute_rel  = $link->getAttribute('rel');
            $attribute_type = $link->getAttribute('type');
            $attribute_href = $link->getAttribute('href');

            $this->_head_link[$attribute_rel][] = array(
                'type'  => $attribute_type,
                'href'  => $attribute_href
            );
        }

        return $this->_head_link;
    }

    /**
     * [getHeadLinksByRel]
     * Get a specific type of head links by rel
     *
     * @param string $rel Rel of head links
     *
     * @return array Head links array
     */
    public function getHeadLinksByRel($rel)
    {
        $links = $this->getHeadLinks();

        if (isset($links[$rel])) {
            return $links[$rel];
        }

        return null;
    }

    /**
     * [getFaviconUrl]
     * Get the favicon url of the HTML document
     *
     * @return string Favicon url
     */
    public function getFaviconUrl()
    {
        $links = $this->getHeadLinksByRel('shortcut icon');

        if (!empty($links) && isset($links[0]['href'])) {
            $url = $links[0]['href'];
        }

        if ($this->isAbsoluteUrl($url)) {
            return $url;
        } else {
            return UrlHelper::combineUrl($this->getUrl(), $url);
        }
    }

    /**
     * [getFaviconMimeType]
     * Get the favicon mimetype of the HTML document
     *
     * @return string Favicon mimetype
     */
    public function getFaviconMimeType()
    {
        $links = $this->getHeadLinksByRel('shortcut icon');

        if (!empty($links) && isset($links[0]['type'])) {
            $type = $links[0]['type'];
        }

        return $type;
    }

    /**
     * [getFavicon]
     * Scrape the Favicon of the document
     * and save it in local storage
     *
     * @return ScraperFavicon The scraper object
     */
    public function getFavicon()
    {
        $favicon_type   = $this->getFaviconMimeType();
        $favicon_url    = $this->getFaviconUrl();
        $favicon        = new ScraperFavicon($favicon_url, $favicon_type);

        return $favicon;
    }

    public function getFaviconName()
    {
        try {
            $favicon = $this->getFavicon();

        } catch (ScraperException $e) {
            return null;
        }

        return $favicon->getFileName() . '.png';
    }
}