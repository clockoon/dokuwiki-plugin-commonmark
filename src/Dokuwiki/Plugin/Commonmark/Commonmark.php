<?php

namespace Dokuwiki\Plugin\Commonmark;

use League\CommonMark\Environment;
use League\CommonMark\DocParser;
use Dokuwiki\Plugin\Commonmark\Extension\CommonmarkToDokuwikiExtension;

class Commonmark {
    public static function RendtoDW($markdown): string {
        # create environment
        $environment = self::createDWEnvironment();
        
        # create parser
        $parser = new DocParser($environment);
        # create Dokuwiki Renderer
        $DWRenderer = new DWRenderer($environment);

        $document = $parser->parse($markdown);
        return $DWRenderer->renderBlock($document);
    }

    public static function createDWEnvironment(): Environment {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkToDokuWikiExtension());

        return $environment;
    }
}

?>