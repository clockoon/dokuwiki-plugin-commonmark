<?php
/*
 * This file is part of the clockoon/dokuwiki-commonmark-plugin package.
 *
 * (c) Sungbin Jeon <clockoon@gmail.com>
 *
 * Original code based on the followings:
 * - CommonMark JS reference parser (https://bitly.com/commonmark-js) (c) John MacFarlane
 * - league/commonmark (https://github.com/thephpleague/commonmark) (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 require_once __DIR__.'/src/bootstrap.php';

 use Dokuwiki\Plugin\Commonmark\Commonmark;
 use Nette\Utils\Strings;

 if(!defined('DOKU_INC')) die();

 class action_plugin_commonmark extends DokuWiki_Action_Plugin {
    // array for heading positions
    // [hid] => {[depth], [startline], [endline]}
    public $headingInfo = [];
    // positions of newline
    public $linePosition = [];
    // flag for checking first run only
    public $firstRun = true;

    /**
     * pass text to Commonmark parser before DW parser
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('PARSER_WIKITEXT_PREPROCESS', 'BEFORE', $this,
                                    '_commonmarkparse');
        $controller->register_hook('HTML_SECEDIT_BUTTON', 'BEFORE', $this, 
                                    '_editbutton');
    }

    public function _editbutton(Doku_Event $event, $param) {
        global $conf;

        // get hid
        $hid = $event->data['hid'];
        // fetch range on original md
        // check hid match
        $keys = array_keys($this->headingInfo);
        if (array_key_exists($hid,$keys)) {
            // get max section editing level config
            $maxsec = $conf['maxseclevel'];
            // set start position
            // first, check whether first heading
            if ($hid == $keys[0]) {
                $start = 1;
            } else {
                $lineStart = $this->headingInfo[$hid]['startline'];
                // since CommonMark library finds heading marks, we have to declare 
                $start = $this->linePosition[$lineStart];
            }
            // find end key & location; proceed while max level or less arrived
            $endlevel = 52;
            $index = array_search($hid,$keys);
            $end = 0; // 0 means end of document
            $stop = false;
            while($stop == false) {
                if (isset($keys[$index+1])) { // check for non-last element
                    $endlevel = $this->headingInfo[$keys[$index+1]]['level'];
                    $lineEnd = $this->headingInfo[$keys[$index+1]]['startline'] - 1; // go one line up
                    $end = $this->linePosition[$lineEnd]; 
                    if($maxsec<=$endlevel) { $stop = true; }
                } else {
                    $end = 0;
                    $stop = true;
                }
                $index = $index + 1;
            }
            if($end == 0) { 
                $event->data['range'] = (string)$start.'-'; 
            } else {
                $event->data['range'] = (string)$start.'-'.$end;
            }
            
        }
        // example: $event->data['range'] = '1-2';
    }

    public function _commonmarkparse(Doku_Event $event, $param) {
        $markdown = $event->data;
        // check force_commonmark option; if 1, ignore doctype
        if ($this->getConf('force_commonmark')) {
            $markdown = ltrim($markdown);
            $result = Commonmark::RendtoDW($markdown, $this->getConf('frontmatter_tag'));
        }
        elseif (preg_match('/\A<!DOCTYPE markdown>/',$markdown)) {
            $markdown = preg_replace('/\A<!DOCTYPE markdown>\n/','',$markdown);
            $markdown = ltrim($markdown);
            $result = Commonmark::RendtoDW($markdown, $this->getConf('frontmatter_tag'));
            $event->data = $result['text'];
        }
        $event->data = $result['text'];
        if ($this->firstRun == true) {
            // get position of each line
            $lastPos = 0;
            while(($lastPos = strpos($markdown,'\n',$lastPos)) !== false){
                $linePosition[] = $lastPos;
                $lastPos = $lastPos + strlen('\n');
            }
            $this->headingInfo = $this->CleanHeadingInfo($result['heading']);
            $this->firstRun = false;
        }
    }

    public function CleanHeadingInfo(array $input): array {
        $keys = array_keys($input);
        foreach($keys as $key) {
            $new_key = sectionId($key, false);
            if($new_key != $key) {
                $input[$new_key] = $input[$key];
                unset($input[$key]);
            }
        }
        return $input;
    }
}
