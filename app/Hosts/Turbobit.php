<?php
namespace App\Hosts;

use Sunra\PhpSimple\HtmlDomParser;

class Turbobit {

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

        if( !strlen($data) || strpos( $data, 'Searching for the file') !== false )
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
        $size = trim($dom->find('.file-size',0)->plaintext);
        $return['size'] = str_replace([',','Кб','Мб'],['.','Kb','Mb'],$size);

        /**
         * Grab filename.
         * Download file ILOVEMAKONNEN – Super Chef 3 (Freestyle) (musicdabster.com).mp3 (3,09 Mb) | Turbobit.net
         */
        $filename = trim(str_replace(' ('.$size.') | Turbobit.net','',$dom->find('title',0)->plaintext));
        $words = explode(' ', $filename);
        unset( $words[0] );
        unset( $words[1] );
        $filename = implode(' ', $words);
        $return['filename'] = $filename;

        /**
         * Return results.
         */
        return $return;
    }
}