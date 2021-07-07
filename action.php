<?php
/**
 * Commonmark Plugin test
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

 require_once __DIR__.'/src/bootstrap.php';

 use Dokuwiki\Plugin\Commonmark\Commonmark;

 if(!defined('DOKU_INC')) die();

 class action_plugin_commonmark extends DokuWiki_Action_Plugin {
    /**
     * pass text to Commonmark parser before DW parser
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('PARSER_WIKITEXT_PREPROCESS', 'BEFORE', $this,
                                   '_commonmarkparse');
    }

    /**
     * Parse commonmark text
     * TEST: <!DOCTYPE markdown> to "PASSED!!"
     */
    public function _commonmarkparse(Doku_Event $event, $param) {
        if (preg_match('/\A<!DOCTYPE markdown>/',$event->data)) {
            $event->data = Commonmark::RendtoDW(preg_replace('/\A<!DOCTYPE markdown>/','',$event->data));
            #$event->data = "PASSED";
        }

    }

}