<?php
/**
 * Commonmark Plugin test
 */

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
            $event->data = "===== WILL DW RENDERING WORK? =====";
        }

    }

}