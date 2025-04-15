<?php

namespace App\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter as LeagueMarkdownConverter;

class Converter
{
    protected LeagueMarkdownConverter $converter;

    protected bool $nofollowLinks;

    public function __construct(bool $nofollow = true)
    {
        $this->nofollowLinks = $nofollow;

        // Basic configuration
        $config = [
            'html_input' => 'strip', // Basic sanitization
            'allow_unsafe_links' => false,
        ];

        // Add config for ExternalLinkExtension
        $config['external_link'] = [
            'internal_hosts' => preg_replace('/https?:\/\//', '', config('app.url')), // Domain without protocol
            'open_in_new_window' => true,
            'html_class' => 'external-link',
            'nofollow' => $this->nofollowLinks ? 'external' : '', // Use 'external' to add nofollow to external links only
            'noopener' => 'external', // Add noopener to external links only
            'noreferrer' => 'external', // Add noreferrer to external links only
        ];

        $environment = new Environment($config);

        // Add core extensions
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);

        // Add optional extensions
        $environment->addExtension(new AttributesExtension);
        $environment->addExtension(new ExternalLinkExtension);

        // Instantiate the converter
        $this->converter = new LeagueMarkdownConverter($environment);
    }

    /**
     * Converts Markdown to HTML.
     */
    public function toHtml(string $markdown): string
    {
        $html = $this->converter->convert($markdown)->getContent();

        return $html;
    }
}
