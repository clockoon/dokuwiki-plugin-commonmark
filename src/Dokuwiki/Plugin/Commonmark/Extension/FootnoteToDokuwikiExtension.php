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

declare(strict_types=1);

namespace DokuWiki\Plugin\Commonmark\Extension;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\Footnote\Event\AnonymousFootnotesListener;
use League\CommonMark\Extension\Footnote\Event\GatherFootnotesListener;
use League\CommonMark\Extension\Footnote\Event\NumberFootnotesListener;
use League\CommonMark\Extension\Footnote\Node\Footnote;
use League\CommonMark\Extension\Footnote\Node\FootnoteBackref;
use League\CommonMark\Extension\Footnote\Node\FootnoteContainer;
use League\CommonMark\Extension\Footnote\Node\FootnoteRef;
use League\CommonMark\Extension\Footnote\Parser\AnonymousFootnoteRefParser;
use League\CommonMark\Extension\Footnote\Parser\FootnoteParser;
use League\CommonMark\Extension\Footnote\Parser\FootnoteRefParser;
use Dokuwiki\Plugin\Commonmark\Extension\Renderer\Inline\FootnoteBackrefRenderer;
use Dokuwiki\Plugin\Commonmark\Extension\Renderer\Block\FootnoteContainerRenderer;
use Dokuwiki\Plugin\Commonmark\Extension\Renderer\Inline\FootnoteRefRenderer;
use Dokuwiki\Plugin\Commonmark\Extension\Renderer\Block\FootnoteRenderer;

final class FootnotetoDokuwikiExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment->addBlockParser(new FootnoteParser(), 51);
        $environment->addInlineParser(new AnonymousFootnoteRefParser(), 35);
        $environment->addInlineParser(new FootnoteRefParser(), 51);

        $environment->addBlockRenderer(FootnoteContainer::class, new FootnoteContainerRenderer());
        $environment->addBlockRenderer(Footnote::class, new FootnoteRenderer());
        $environment->addInlineRenderer(FootnoteBackref::class, new FootnoteBackrefRenderer());
        $environment->addInlineRenderer(FootnoteRef::class, new FootnoteRefRenderer());

        $environment->addEventListener(DocumentParsedEvent::class, [new AnonymousFootnotesListener(), 'onDocumentParsed']);
        $environment->addEventListener(DocumentParsedEvent::class, [new NumberFootnotesListener(), 'onDocumentParsed']);
        $environment->addEventListener(DocumentParsedEvent::class, [new GatherFootnotesListener(), 'onDocumentParsed']);
    }
}
