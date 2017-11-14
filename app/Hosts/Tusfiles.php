<?php
namespace App\Hosts;

use Sunra\PhpSimple\HtmlDomParser;

class Tusfiles {

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

        if( !strlen($data) || strpos( $data, 'File not found') !== false )
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
        $return['filename'] = trim($dom->find('.p-a-xs img',0)->attr['alt']);

        /**
         * Grab file size.
         */
        $return['size'] = trim(str_replace(['(',')'],'',$dom->find('span.l-h-1x .text-xs small b',0)->plaintext));

        /**
         * Return results.
         */
        return $return;
    }
}