<?php
namespace App\Hosts;

use Sunra\PhpSimple\HtmlDomParser;

class Dbree {

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

        if( !strlen($data) || strpos( $data, 'page was removed') !== false )
        {
            return ['error' => true];
        }

        /**
         * Load data into html dom parser.
         */
        $dom = HtmlDomParser::str_get_html( $data );

        /**
         * Grab filename.
         */
        $return['filename'] = trim($dom->find('h1.header-file__title',0)->plaintext);

        /**
         * Grab file size.
         */
        $return['size'] = trim($dom->find('dd.dd-size',0)->plaintext);

        /**
         * Return results.
         */
        return $return;
    }
}