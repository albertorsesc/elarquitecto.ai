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

/**
 * Gets the appropriate Slack channel based on environment.
 */
function slack_channel(): ?string
{
    // In production, use the production channel
    if (app()->isProduction()) {
        return env('SLACK_BOT_USER_DEFAULT_CHANNEL');
    }

    // In non-production, use the dev channel if set, otherwise fall back to production channel
    return env('SLACK_BOT_USER_DEFAULT_CHANNEL_DEV', env('SLACK_BOT_USER_DEFAULT_CHANNEL'));
}
