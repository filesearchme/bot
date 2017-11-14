<?php
namespace App\Hosts;

use Sunra\PhpSimple\HtmlDomParser;

class Uploadboy {

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

        if( !strlen($data) || strpos( $data, 'could not be found') !== false )
        {
            return ['error' => true];
        }

        /**
         * Load data into html dom parser.
         */
        $dom = HtmlDomParser::str_get_html( $data );

        /**
         * Grab file size.
         */
        $size = $dom->find('.xs-block h4 span',0)->plaintext;
        $return['size'] = trim(str_replace(['(',')'],'',$size));

        /**
         * Grab filename.
         */
        $return['filename'] = trim(str_replace($size,'',$dom->find('.xs-block h4',0)->plaintext));

        /**
         * Return results.
         */
        return $return;
    }
}