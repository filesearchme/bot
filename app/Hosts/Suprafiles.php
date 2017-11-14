<?php
namespace App\Hosts;

use Sunra\PhpSimple\HtmlDomParser;

class Suprafiles {

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

        if( !strlen($data) || strpos( $data, 'File Not Found') !== false )
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
        $return['size'] = $dom->find( '.filepanel tr td span b', 0 )->plaintext;

        /**
         * Grab filename.
         */
        $return['filename'] = trim( str_replace('Download File ','',$dom->find('.dfilename',0)->plaintext));

        /**
         * Check to see if filename was cut short.
         */
        if (substr($return['filename'], -6) == '&#133;')
        {
            /**
             * Filename was cut short.
             */
            $parse = parse_url( $job->url );
            /**
             * Create URL for report page to grab full filename.
             */
            $report_url = $parse['scheme'].'://'.$parse['host'].'/?op=report_file&id='.ltrim($parse['path'],'/');
            /**
             * Fetch source from report URL.
             */
            $data = GetData::parse( $report_url, self::timeout, $server_addr );
            /**
             * Load data into html dom parser.
             */
            $dom = HtmlDomParser::str_get_html( $data );
            /**
             * Grab full filename.
             */
            $return['filename'] = $dom->find('.tbl1 tr',1)->find('td', 1)->plaintext;
        }
        /**
         * Return results.
         */
        return $return;
    }
}