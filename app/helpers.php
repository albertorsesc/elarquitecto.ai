<?php

if (! function_exists('md_to_html')) {
    /**
     * Converts Markdown to a safe HTML string.
     */
    function md_to_html(string $markdown, bool $nofollow = true): string
    {
        return app(App\Markdown\Converter::class, ['nofollow' => $nofollow])->toHtml($markdown);
    }
}
