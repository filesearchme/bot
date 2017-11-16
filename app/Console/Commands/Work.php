<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Http\Request;

class Work extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'work';
    /**
     * The server address.
     *
     * @var string
     */
    private $server_addr = null;
    /**
     * The filesearch API key.
     *
     * @var string
     */
    private $api_key = null;

    /**
     * Regex to capture URLs
     *
     * @var string
     */
    private $regex = '/(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/';

    /**
     * url extensions to filter
     *
     * @var array
     */
    private $filters = [
        'ai'      => 'application/postscript',
        'aif'     => 'audio/x-aiff',
        'aifc'    => 'audio/x-aiff',
        'aiff'    => 'audio/x-aiff',
        'asc'     => 'text/plain',
        //'atom'    => 'application/atom+xml',
        //'atom'    => 'application/atom+xml',
        'au'      => 'audio/basic',
        'avi'     => 'video/x-msvideo',
        'bcpio'   => 'application/x-bcpio',
        'bin'     => 'application/octet-stream',
        'bmp'     => 'image/bmp',
        'cdf'     => 'application/x-netcdf',
        'cgm'     => 'image/cgm',
        'class'   => 'application/octet-stream',
        'cpio'    => 'application/x-cpio',
        'cpt'     => 'application/mac-compactpro',
        'csh'     => 'application/x-csh',
        'css'     => 'text/css',
        'csv'     => 'text/csv',
        'dcr'     => 'application/x-director',
        'dir'     => 'application/x-director',
        'djv'     => 'image/vnd.djvu',
        'djvu'    => 'image/vnd.djvu',
        'dll'     => 'application/octet-stream',
        'dmg'     => 'application/octet-stream',
        'dms'     => 'application/octet-stream',
        'doc'     => 'application/msword',
        'dtd'     => 'application/xml-dtd',
        'dvi'     => 'application/x-dvi',
        'dxr'     => 'application/x-director',
        'eps'     => 'application/postscript',
        'etx'     => 'text/x-setext',
        'exe'     => 'application/octet-stream',
        'ez'      => 'application/andrew-inset',
        'gif'     => 'image/gif',
        'gram'    => 'application/srgs',
        'grxml'   => 'application/srgs+xml',
        'gtar'    => 'application/x-gtar',
        'hdf'     => 'application/x-hdf',
        'hqx'     => 'application/mac-binhex40',
        //'htm'     => 'text/html',
        //'html'    => 'text/html',
        'ice'     => 'x-conference/x-cooltalk',
        'ico'     => 'image/x-icon',
        'ics'     => 'text/calendar',
        'ief'     => 'image/ief',
        'ifb'     => 'text/calendar',
        'iges'    => 'model/iges',
        'igs'     => 'model/iges',
        'jpe'     => 'image/jpeg',
        'jpeg'    => 'image/jpeg',
        'jpg'     => 'image/jpeg',
        'js'      => 'application/x-javascript',
        'json'    => 'application/json',
        'kar'     => 'audio/midi',
        'latex'   => 'application/x-latex',
        'lha'     => 'application/octet-stream',
        'lzh'     => 'application/octet-stream',
        'm3u'     => 'audio/x-mpegurl',
        'man'     => 'application/x-troff-man',
        'mathml'  => 'application/mathml+xml',
        'me'      => 'application/x-troff-me',
        'mesh'    => 'model/mesh',
        'mid'     => 'audio/midi',
        'midi'    => 'audio/midi',
        'mif'     => 'application/vnd.mif',
        'mov'     => 'video/quicktime',
        'movie'   => 'video/x-sgi-movie',
        'mp2'     => 'audio/mpeg',
        'mp3'     => 'audio/mpeg',
        'mpe'     => 'video/mpeg',
        'mpeg'    => 'video/mpeg',
        'mpg'     => 'video/mpeg',
        'mpga'    => 'audio/mpeg',
        'ms'      => 'application/x-troff-ms',
        'msh'     => 'model/mesh',
        'mxu'     => 'video/vnd.mpegurl',
        'nc'      => 'application/x-netcdf',
        'oda'     => 'application/oda',
        'ogg'     => 'application/ogg',
        'pbm'     => 'image/x-portable-bitmap',
        'pdb'     => 'chemical/x-pdb',
        'pdf'     => 'application/pdf',
        'pgm'     => 'image/x-portable-graymap',
        'pgn'     => 'application/x-chess-pgn',
        'png'     => 'image/png',
        'pnm'     => 'image/x-portable-anymap',
        'ppm'     => 'image/x-portable-pixmap',
        'ppt'     => 'application/vnd.ms-powerpoint',
        'ps'      => 'application/postscript',
        'qt'      => 'video/quicktime',
        'ra'      => 'audio/x-pn-realaudio',
        'ram'     => 'audio/x-pn-realaudio',
        'ras'     => 'image/x-cmu-raster',
        'rdf'     => 'application/rdf+xml',
        'rgb'     => 'image/x-rgb',
        'rm'      => 'application/vnd.rn-realmedia',
        'roff'    => 'application/x-troff',
        'rss'     => 'application/rss+xml',
        'rtf'     => 'text/rtf',
        'rtx'     => 'text/richtext',
        'sgm'     => 'text/sgml',
        'sgml'    => 'text/sgml',
        'sh'      => 'application/x-sh',
        'shar'    => 'application/x-shar',
        'silo'    => 'model/mesh',
        'sit'     => 'application/x-stuffit',
        'skd'     => 'application/x-koan',
        'skm'     => 'application/x-koan',
        'skp'     => 'application/x-koan',
        'skt'     => 'application/x-koan',
        'smi'     => 'application/smil',
        'smil'    => 'application/smil',
        'snd'     => 'audio/basic',
        'so'      => 'application/octet-stream',
        'spl'     => 'application/x-futuresplash',
        'src'     => 'application/x-wais-source',
        'sv4cpio' => 'application/x-sv4cpio',
        'sv4crc'  => 'application/x-sv4crc',
        'svg'     => 'image/svg+xml',
        'svgz'    => 'image/svg+xml',
        'swf'     => 'application/x-shockwave-flash',
        't'       => 'application/x-troff',
        'tar'     => 'application/x-tar',
        'tcl'     => 'application/x-tcl',
        'tex'     => 'application/x-tex',
        'texi'    => 'application/x-texinfo',
        'texinfo' => 'application/x-texinfo',
        'tif'     => 'image/tiff',
        'tiff'    => 'image/tiff',
        'tr'      => 'application/x-troff',
        'tsv'     => 'text/tab-separated-values',
        //'txt'     => 'text/plain',
        'ustar'   => 'application/x-ustar',
        'vcd'     => 'application/x-cdlink',
        'vrml'    => 'model/vrml',
        'vxml'    => 'application/voicexml+xml',
        'wav'     => 'audio/x-wav',
        'wbmp'    => 'image/vnd.wap.wbmp',
        'wbxml'   => 'application/vnd.wap.wbxml',
        'wml'     => 'text/vnd.wap.wml',
        'wmlc'    => 'application/vnd.wap.wmlc',
        'wmls'    => 'text/vnd.wap.wmlscript',
        'wmlsc'   => 'application/vnd.wap.wmlscriptc',
        'wrl'     => 'model/vrml',
        'xbm'     => 'image/x-xbitmap',
        'xht'     => 'application/xhtml+xml',
        'xhtml'   => 'application/xhtml+xml',
        'xls'     => 'application/vnd.ms-excel',
        //'xml'     => 'application/xml',
        'xpm'     => 'image/x-xpixmap',
        'xsl'     => 'application/xml',
        'xslt'    => 'application/xslt+xml',
        'xul'     => 'application/vnd.mozilla.xul+xml',
        'xwd'     => 'image/x-xwindowdump',
        'xyz'     => 'chemical/x-xyz',
        'zip'     => 'application/zip'
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Receive jobs from FileSearch.me API";
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire(Request $request)
    {
        /**
         * Set FileSearch.me API key.
         */
        $this->api_key     = env('API_KEY');

        /**
         * Get server IP to generate bot identifier.
         */
        $this->server_addr = $request->server('SERVER_ADDR');

        /**
         * Set job limit.
         */
        $job_limit   = $this->input->getOption('jobs');

        /**
         * Initiate guzzle client.
         */
        $client = new \GuzzleHttp\Client();

        /**
         * Fetch FileSearch.me jobs.
         */
        $res = $client->request('POST', 'http://spider.filesearch.me/api/jobs/fetch', [
            'headers' => [
                'x-authorization' => $this->api_key
            ],
            'form_params' => [
                'version' => env('APP_BOT_VERSION'),
                'identifier' => md5($this->server_addr),
                'jobs' => env('JOBS_PER_FIRE')
            ]
        ]);

        /**
         * Decode json response.
         */
        $jobs = json_decode( $res->getBody() );

        $completed = [];

        /**
         * Make sure there are jobs to process.
         */
        if( count( $jobs ) )
        {
            /**
             * Process jobs.
             */
            foreach( $jobs->jobs AS $job )
            {
                switch( $job->type )
                {
                    case "spider":
                        $completed[$job->id] = $this->spider($job,$jobs->hosts);
                        break;
                    case "parse":
                        $completed[$job->id] = $this->parse($job);
                        break;
                }
            }
            $client->request('POST', 'http://spider.filesearch.me/api/jobs/receive', [
                'headers' => [
                    'x-authorization' => $this->api_key
                ],
                'form_params' => [
                    'version' => env('APP_BOT_VERSION'),
                    'identifier' => md5($this->server_addr),
                    'data' => json_encode($completed)
                ]
            ]);
        }
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('jobs', null, InputOption::VALUE_OPTIONAL, 'The number of jobs to process per fire.', 25)
        );
    }

    private function spider($job,$hosts)
    {
        $data = ['spider'=>[],'parse'=>[]];
        /**
         * Parse website URL to find base domain.
         */
        $pu = parse_url( $job->url );
        $pu = explode('.',$pu['host']);
        $domain = $pu[count($pu)-2] . "." . $pu[count($pu)-1];

        /**
         * Grab source code from website.
         */
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $res = $client->request('GET', $job->url);

        /**
         * Check for valid 200 response.
         */
        if( $res->getStatusCode() == 200 )
        {
            $page_title = $this->parseTitle($res->getBody());
            $page_description = $this->parseDescription($res->getBody());
            /**
             * Find all URLs on page using regex.
             */
            preg_match_all($this->regex, $res->getBody(), $matches, PREG_SET_ORDER, 0);
            /**
             * Remove any duplicate URLs.
             */
            $matches = array_unique($matches, SORT_REGULAR);
            /**
             * Filter each URL.
             */
            foreach( $matches AS $m )
            {
                $url_ = $m[0];

                /**
                 * don't reprocess current URL.
                 */
                if( $url_ == $job->url ) continue;

                if( $this->contains( $url_, $this->filters ) ) continue;

                /**
                 * Parse URL to find base domain.
                 */
                $pu_ = parse_url( $url_ );
                $pu_ = explode('.',$pu_['host']);
                $domain_ = $pu_[count($pu_)-2] . "." . $pu_[count($pu_)-1];

                /**
                 * Compare website domain to URL domain. If same domain add to spider queue array.
                 */
                if( $domain == $domain_ )
                {
                    if( $job->depth >= $job->website->depth ) continue;
                    $data['spider'][] = [
                        'depth' => ($job->depth+1),
                        'url' => $url_
                    ];
                }
                else
                {
                    /**
                     * Remote URL found, is it a download link?
                     */
                    foreach( $hosts AS $host )
                    {
                        foreach( $host->hostnames AS $hostname )
                        {
                            if( $domain_ == $hostname )
                            {
                                $data['parse'][] = [
                                    'host' => $host->id,
                                    'url' => $url_,
                                    'page_title' => $page_title,
                                    'page_description' => $page_description,
                                    'page_url' => $job->url
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    private function parse($job)
    {
        switch( $job->host_id )
        {
            case 1:
                /**
                 * Parse Suprafiles URL and return filename/size.
                 */
                $return = \App\Hosts\Suprafiles::parse( $job, $this->server_addr );
                break;
            case 2:
                /**
                 * Parse Cloudyfiles URL and return filename/size.
                 */
                $return = \App\Hosts\Cloudyfiles::parse( $job, $this->server_addr );
                break;
            case 3:
                /**
                 * Parse Dopefile URL and return filename/size.
                 */
                $return = \App\Hosts\Dopefile::parse( $job, $this->server_addr );
                break;
            case 4:
                /**
                 * Parse Zippyshare URL and return filename/size.
                 */
                $return = \App\Hosts\Zippyshare::parse( $job, $this->server_addr );
                break;
            case 5:
                /**
                 * Parse Dbree URL and return filename/size.
                 */
                $return = \App\Hosts\Dbree::parse( $job, $this->server_addr );
                break;
            case 6:
                /**
                 * Parse Tusfiles URL and return filename/size.
                 */
                $return = \App\Hosts\Tusfiles::parse( $job, $this->server_addr );
                break;
            case 7:
                /**
                 * Parse Uploadboy URL and return filename/size.
                 */
                $return = \App\Hosts\Uploadboy::parse( $job, $this->server_addr );
                break;
            case 8:
                /**
                 * Parse Rapidgator URL and return filename/size.
                 */
                $return = \App\Hosts\Rapidgator::parse( $job, $this->server_addr );
                break;
            case 9:
                /**
                 * Parse Turbobit URL and return filename/size.
                 */
                $return = \App\Hosts\Turbobit::parse( $job, $this->server_addr );
                break;
            case 10:
                /**
                 * Parse Mediafire URL and return filename/size.
                 */
                $return = \App\Hosts\Mediafire::parse( $job, $this->server_addr );
                break;
        }

        /**
         * Handle error response.
         */
        if( isset( $return['error'] ) ) return $return;
        /**
         * Convert size to kilobytes.
         */
        $return['size'] = $this->convert_size( trim( $return['size'] ) );
        /**
         * Extract extension from filename.
         */
        $return['extension'] = pathinfo($return['filename'], PATHINFO_EXTENSION);
        /**
         * Return result
         */
        return $return;
    }

    private function convert_size($from)
    {
        /**
         * Remove size type to leave only numbers.
         */
        $number=trim(substr($from,0,-2));
        /**
         * Switch depending on size type.
         */
        switch(strtoupper(substr($from,-2)))
        {
            case "KB":
                return $number;
            case "MB":
                return $number * 1024;
            case "GB":
                return $number * pow(1024, 2);
            case "TB":
                return $number * pow(1024, 3);
            case "PB":
                return $number * pow(1024, 4);
            default:
                return $from;
        }
    }

    private function contains($str, array $arr)
    {
        foreach($arr as $a=>$b) {
            if (stripos($str,'.'.$a) !== false) return true;
        }
        return false;
    }

    private function parseDescription($html) {
        // Get the 'content' attribute value in a <meta name="description" ... />
        $matches = array();
        // Search for <meta name="description" content="Buy my stuff" />
        preg_match('/<meta.*?name=("|\')description("|\').*?content=("|\')(.*?)("|\')/i', $html, $matches);
        if (count($matches) > 4) {
            return trim($matches[4]);
        }
        // Order of attributes could be swapped around: <meta content="Buy my stuff" name="description" />
        preg_match('/<meta.*?content=("|\')(.*?)("|\').*?name=("|\')description("|\')/i', $html, $matches);
        if (count($matches) > 2) {
            return trim($matches[2]);
        }
        // No match
        return null;
    }

    private function parseTitle($html) {
        $res = preg_match("/<title>(.*)<\/title>/siU", $html, $title_matches);
        if (!$res)
            return null;

        // Clean up title: remove EOL's and excessive whitespace.
        $title = preg_replace('/\s+/', ' ', $title_matches[1]);
        $title = trim($title);
        return $title;
    }
}