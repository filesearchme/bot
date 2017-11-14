<?php
namespace App\Hosts;

use Sunra\PhpSimple\HtmlDomParser;

class Zippyshare {

    /**
     * The curl command timeout in seconds.
     *
     * @var integer
     */
    const timeout = 5;

    public static function parse($job, $server_addr)
    {
        /**
         * Fetch source from URL.
         */
        $data = GetData::parse( $job->url, self::timeout, $server_addr );

        if( !strlen($data) || strpos( $data, 'File does not exist') !== false )
        {
            return ['error' => true];
        }

        /**
         * Load data into html dom parser.
         */
        $dom = HtmlDomParser::str_get_html( $data );

        $next=false;
        $font_blocks = $dom->find('font');
        foreach( $font_blocks AS $fb )
        {
            if( $next )
            {
                $return['size'] = $fb->plaintext;
                break;
            }
            if( $fb->plaintext == 'Size:' ) $next = true;
        }

        /**
         * Grab filename.
         */
        $return['filename'] = trim($dom->find('meta[property="og:title"]',0)->attr['content']);

        /**
         * Return results.
         */
        return $return;
    }
}