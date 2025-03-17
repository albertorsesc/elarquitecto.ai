<?php

namespace App\Classes;

use Illuminate\Support\HtmlString;

class Markdown
{
    protected string $markdown;

    protected int $imageHeight = 400;

    protected bool $darkMode = true;

    public function __construct(string $markdown = '')
    {
        $this->markdown = $markdown;
    }

    public function setMarkdown(string $markdown): self
    {
        $this->markdown = $markdown;

        return $this;
    }

    public function setImageHeight(int $height): self
    {
        $this->imageHeight = $height;

        return $this;
    }

    public function setDarkMode(bool $enabled): self
    {
        $this->darkMode = $enabled;

        return $this;
    }

    public function render(): HtmlString
    {
        $html = class_exists('\Parsedown')
            ? (new \Parsedown)->text($this->markdown)
            : $this->basicMarkdownParse($this->markdown);
        $html = $this->processHtml($html);
        $css = $this->getAdditionalCSS();

        return new HtmlString($html.$css);
    }

    protected function getAdditionalCSS(): string
    {
        $textColor = $this->darkMode ? '#F3F4F6' : '#1F2937';
        $linkColor = $this->darkMode ? '#60A5FA' : '#2563EB';
        $linkHoverColor = $this->darkMode ? '#93C5FD' : '#1D4ED8';
        $blockquoteColor = $this->darkMode ? '#D1D5DB' : '#6B7280';
        $tableHeadBg = $this->darkMode ? '#1F2937' : '#F3F4F6';
        $tableBorderColor = $this->darkMode ? '#4B5563' : '#D1D5DB';

        return <<<CSS
<style>
    .markdown-content {
        color: {$textColor} !important;
    }
    .markdown-content a:not([class]) {
        color: {$linkColor} !important;
        transition: color 0.2s ease;
    }
    .markdown-content a:not([class]):hover {
        color: {$linkHoverColor} !important;
    }
    .markdown-content blockquote {
        color: {$blockquoteColor} !important;
    }
    .markdown-content table th {
        background-color: {$tableHeadBg} !important;
    }
    .markdown-content table td {
        border-color: {$tableBorderColor} !important;
    }
    p, ul, ol, li, h1, h2, h3, h4, h5, h6, table, th, td, blockquote {
        color: inherit;
    }
    /* Code block styles */
    .code-block pre {
        white-space: pre !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        background-color: transparent !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .code-block code {
        white-space: pre !important;
        background-color: transparent !important;
        border: none !important;
        padding: 0 !important;
    }
    /* Plain text block styles */
    .text-code-block pre {
        white-space: pre-wrap !important;
        word-wrap: break-word;
        color: inherit !important;
        background-color: transparent !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    /* Syntax colors */
    .syntax-keyword { color: #C792EA !important; }
    .syntax-string { color: #C3E88D !important; }
    .syntax-comment { color: #9CA3AF !important; }
    .syntax-type { color: #FFCB6B !important; }
    .syntax-function { color: #82AAFF !important; }
    .syntax-number { color: #F78C6C !important; }
    .syntax-variable { color: #F07178 !important; }
    .syntax-tag { color: #7C3AED !important; }
    .syntax-attr { color: #EC4899 !important; }
    .syntax-operator { color: #89DDFF !important; }
</style>
CSS;
    }

    protected function processHtml(string $html): string
    {
        // Process code blocks first - must do this before wrapping in div
        $html = preg_replace_callback(
            '/<pre><code(?:\s+class="language-([^"]+)")?>([\s\S]*?)<\/code><\/pre>/s',
            function ($matches) {
                $language = strtolower($matches[1] ?? 'text');
                // Don't decode HTML entities - we want to preserve them
                $code = $matches[2];

                return $this->formatCodeBlock($code, $language);
            },
            $html
        );

        // Text colors and other variables
        $headingBorderColor = $this->darkMode ? 'border-gray-700' : 'border-gray-200';
        $blockquoteBorder = $this->darkMode ? 'border-gray-600' : 'border-gray-300';
        $blockquoteText = $this->darkMode ? 'text-gray-300' : 'text-gray-700';
        $tableBg = $this->darkMode ? 'bg-gray-800' : 'bg-white';
        $tableBorder = $this->darkMode ? 'border-gray-700' : 'border-gray-300';
        $tableHeadBg = $this->darkMode ? 'bg-gray-900' : 'bg-gray-100';
        $hrColor = $this->darkMode ? 'border-gray-700' : 'border-gray-300';
        $inlineCodeBg = $this->darkMode ? 'bg-[#1F2937]' : 'bg-[#F3F4F6]';
        $inlineCodeText = $this->darkMode ? 'text-[#EC4899]' : 'text-[#D946EF]';
        $inlineCodeBorder = $this->darkMode ? 'border-[rgba(255,255,255,0.1)]' : 'border-[rgba(0,0,0,0.1)]';
        $linkColor = $this->darkMode ? 'text-blue-400 hover:text-blue-300' : 'text-blue-600 hover:text-blue-800';

        // Ensure default text color is applied to the whole content
        $html = '<div class="markdown-content">'.$html.'</div>';

        // Process headings
        $headingClasses = [
            'h1' => 'text-3xl font-bold mt-8 mb-4',
            'h2' => "text-2xl font-bold mt-8 mb-4 pb-2 border-b {$headingBorderColor}",
            'h3' => 'text-xl font-bold mt-6 mb-3',
            'h4' => 'text-lg font-bold mt-6 mb-3',
            'h5' => 'text-base font-bold mt-6 mb-3',
            'h6' => 'text-sm font-bold mt-6 mb-3',
        ];
        foreach ($headingClasses as $tag => $class) {
            $html = preg_replace(
                "/<{$tag}([^>]*)>/",
                "<{$tag} class=\"{$class}\"$1>",
                $html
            );
        }

        // Other elements
        $html = preg_replace('/<p([^>]*)>/i', '<p class="mb-4 leading-relaxed"$1>', $html);
        $html = preg_replace('/<ul([^>]*)>/i', '<ul class="list-disc pl-8 mb-6"$1>', $html);
        $html = preg_replace('/<ol([^>]*)>/i', '<ol class="list-decimal pl-8 mb-6"$1>', $html);
        $html = preg_replace('/<li([^>]*)>/i', '<li class="mb-1"$1>', $html);
        $html = preg_replace(
            '/<blockquote([^>]*)>/i',
            "<blockquote class=\"pl-4 border-l-4 {$blockquoteBorder} italic my-6 {$blockquoteText}\"$1>",
            $html
        );

        // Inline code
        $html = preg_replace(
            '/<code([^>]*)>/i',
            "<code class=\"px-2 py-1 font-mono text-sm rounded {$inlineCodeBg} {$inlineCodeText} border {$inlineCodeBorder} shadow-sm\" style=\"text-shadow: 0 0 5px rgba(236, 72, 153, 0.3);\"$1>",
            $html
        );

        // Links
        $html = preg_replace(
            '/<a([^>]*)>/i',
            "<a class=\"{$linkColor} underline transition-colors duration-200\"$1>",
            $html
        );

        // Images
        $html = preg_replace_callback(
            '/<img([^>]*)>/i',
            function ($matches) {
                $attributes = $matches[1];
                // Handle class attribute - either add to existing or add new
                if (strpos($attributes, 'class="') !== false) {
                    $attributes = preg_replace(
                        '/class="([^"]*)"/i',
                        'class="$1 max-w-full rounded-lg my-6"',
                        $attributes
                    );
                } else {
                    $attributes .= ' class="max-w-full rounded-lg my-6"';
                }
                // Handle style attribute similarly
                if (strpos($attributes, 'style="') !== false) {
                    $attributes = preg_replace(
                        '/style="([^"]*)"/i',
                        'style="$1; max-height: '.$this->imageHeight.'px; height: auto;"',
                        $attributes
                    );
                } else {
                    $attributes .= ' style="max-height: '.$this->imageHeight.'px; height: auto;"';
                }

                return "<img{$attributes}>";
            },
            $html
        );

        // Tables
        $html = preg_replace('/<table([^>]*)>/i', "<table class=\"w-full border-collapse {$tableBg} text-sm my-8\"$1>", $html);
        $html = preg_replace('/<th([^>]*)>/i', "<th class=\"border {$tableBorder} px-4 py-2 text-left font-medium {$tableHeadBg}\"$1>", $html);
        $html = preg_replace('/<td([^>]*)>/i', "<td class=\"border {$tableBorder} px-4 py-2\"$1>", $html);
        $html = preg_replace('/<hr([^>]*)>/i', "<hr class=\"my-8 border-t {$hrColor}\"$1>", $html);

        return $html;
    }

    protected function formatCodeBlock(string $code, string $language): string
    {
        $code = trim($code);
        if (in_array($language, ['txt', 'text', ''])) {
            return <<<HTML
<div class="text-code-block my-4 font-mono">
    <pre>{$code}</pre>
</div>
HTML;
        }

        $processedCode = $this->applySyntaxHighlighting($code, $language);
        $bgColor = $this->darkMode ? '#111827' : '#1F2937';
        $borderColor = $this->darkMode ? 'rgba(255,255,255,0.1)' : 'rgba(255,255,255,0.15)';
        $headerBgColor = $this->darkMode ? 'rgba(17,24,39,0.7)' : 'rgba(31,41,55,0.7)';

        return <<<HTML
<div class="code-block my-6 rounded-lg overflow-hidden bg-[{$bgColor}] shadow-lg border border-[{$borderColor}] relative">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute left-0 top-0 h-3 w-3 border-t border-l border-[#EC4899] opacity-80"></div>
        <div class="absolute right-0 top-0 h-3 w-3 border-t border-r border-[#EC4899] opacity-80"></div>
        <div class="absolute bottom-0 left-0 h-3 w-3 border-b border-l border-[#EC4899] opacity-80"></div>
        <div class="absolute bottom-0 right-0 h-3 w-3 border-b border-r border-[#EC4899] opacity-80"></div>
    </div>
    <div class="flex items-center px-4 py-2 bg-[{$headerBgColor}] backdrop-blur-[12px] border-b border-[{$borderColor}]">
        <div class="flex space-x-2">
            <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
            <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
            <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
        </div>
        <span class="ml-4 font-mono text-xs text-[#EC4899]" style="text-shadow: 0 0 10px rgba(236, 72, 153, 0.5);">{$this->getLanguageLabel($language)}</span>
    </div>
    <div class="absolute left-0 top-0 w-full h-[1px] bg-gradient-to-r from-transparent via-[#EC4899] to-transparent opacity-60" style="animation: neon-slide-right 6s linear infinite;"></div>
    <div class="p-4 overflow-x-auto">
        <pre><code>{$processedCode}</code></pre>
    </div>
</div>
<style>
@keyframes neon-slide-right {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
</style>
HTML;
    }

    protected function getLanguageLabel(string $language): string
    {
        $labels = [
            'php' => 'PHP',
            'python' => 'Python',
            'javascript' => 'JavaScript',
            'js' => 'JavaScript',
            'typescript' => 'TypeScript',
            'ts' => 'TypeScript',
            'html' => 'HTML',
            'css' => 'CSS',
            'java' => 'Java',
            'c' => 'C',
            'cpp' => 'C++',
            'csharp' => 'C#',
            'go' => 'Go',
            'ruby' => 'Ruby',
            'rust' => 'Rust',
            'swift' => 'Swift',
            'bash' => 'Bash',
            'sh' => 'Shell',
            'sql' => 'SQL',
            'json' => 'JSON',
            'xml' => 'XML',
            'yaml' => 'YAML',
            'txt' => 'Text',
            'text' => 'Text',
        ];

        return $labels[$language] ?? ucfirst($language ?: 'Code');
    }

    protected function applySyntaxHighlighting(string $code, string $language): string
    {
        $newlineMarker = '[[UNIQUE_NEWLINE_MARKER]]';
        $escapedCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
        $escapedCode = str_replace("\n", $newlineMarker, $escapedCode);

        switch (strtolower($language)) {
            case 'php':
                $escapedCode = $this->highlightPhpSyntax($escapedCode);
                break;
            case 'python':
                $escapedCode = $this->highlightPythonSyntax($escapedCode);
                break;
            case 'javascript':
            case 'js':
            case 'typescript':
            case 'ts':
                $escapedCode = $this->highlightJsSyntax($escapedCode);
                break;
            case 'html':
            case 'xml':
                $escapedCode = $this->highlightHtmlSyntax($escapedCode);
                break;
            case 'css':
                $escapedCode = $this->highlightCssSyntax($escapedCode);
                break;
        }

        return str_replace($newlineMarker, "\n", $escapedCode);
    }

    protected function highlightPhpSyntax(string $code): string
    {
        // Keywords
        $code = preg_replace(
            '/\b(abstract|and|array|as|break|callable|case|catch|class|clone|const|continue|declare|default|die|do|echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|endwhile|eval|exit|extends|final|finally|for|foreach|function|global|goto|if|implements|include|include_once|instanceof|insteadof|interface|isset|list|namespace|new|or|print|private|protected|public|require|require_once|return|static|switch|throw|trait|try|unset|use|var|while|xor|yield)\b/',
            '<span class="syntax-keyword">$1</span>',
            $code
        );

        // Variables
        $code = preg_replace(
            '/(\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)/',
            '<span class="syntax-variable">$1</span>',
            $code
        );

        // PHP tags
        $code = preg_replace(
            '/(<\?php|\?>)/',
            '<span class="syntax-tag">$1</span>',
            $code
        );

        // Strings
        $code = preg_replace(
            '/(&quot;.*?&quot;|\'.*?\')/',
            '<span class="syntax-string">$1</span>',
            $code
        );

        // Numbers
        $code = preg_replace(
            '/\b(\d+(?:\.\d+)?)\b/',
            '<span class="syntax-number">$1</span>',
            $code
        );

        // Comments
        $code = preg_replace(
            '/(\/\/.*|#.*?)/',
            '<span class="syntax-comment">$1</span>',
            $code
        );

        // Functions
        $code = preg_replace(
            '/\b([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\s*\(/',
            '<span class="syntax-function">$1</span>(',
            $code
        );

        return $code;
    }

    protected function highlightPythonSyntax(string $code): string
    {
        // Keywords
        $code = preg_replace(
            '/\b(and|as|assert|async|await|break|class|continue|def|del|elif|else|except|finally|for|from|global|if|import|in|is|lambda|nonlocal|not|or|pass|print|raise|return|try|while|with|yield|True|False|None)\b/',
            '<span class="syntax-keyword">$1</span>',
            $code
        );

        // Self reference
        $code = preg_replace(
            '/\b(self)\b/',
            '<span class="syntax-variable">$1</span>',
            $code
        );

        // Strings
        $code = preg_replace(
            '/(&quot;.*?&quot;|\'.*?\')/',
            '<span class="syntax-string">$1</span>',
            $code
        );

        // Numbers
        $code = preg_replace(
            '/\b(\d+(?:\.\d+)?)\b/',
            '<span class="syntax-number">$1</span>',
            $code
        );

        // Comments
        $code = preg_replace(
            '/(#.*)/',
            '<span class="syntax-comment">$1</span>',
            $code
        );

        // Function definitions
        $code = preg_replace(
            '/\b(def)\s+([a-zA-Z_][a-zA-Z0-9_]*)/',
            '<span class="syntax-keyword">$1</span> <span class="syntax-function">$2</span>',
            $code
        );

        // Function calls
        $code = preg_replace(
            '/\b([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/',
            '<span class="syntax-function">$1</span>(',
            $code
        );

        return $code;
    }

    protected function highlightJsSyntax(string $code): string
    {
        // Keywords
        $code = preg_replace(
            '/\b(abstract|arguments|await|boolean|break|byte|case|catch|char|class|const|continue|debugger|default|delete|do|double|else|enum|eval|export|extends|false|final|finally|float|for|function|goto|if|implements|import|in|instanceof|int|interface|let|long|native|new|null|package|private|protected|public|return|short|static|super|switch|synchronized|this|throw|throws|transient|true|try|typeof|var|void|volatile|while|with|yield)\b/',
            '<span class="syntax-keyword">$1</span>',
            $code
        );

        // This keyword
        $code = preg_replace(
            '/\b(this)\b/',
            '<span class="syntax-variable">$1</span>',
            $code
        );

        // Strings
        $code = preg_replace(
            '/(&quot;.*?&quot;|\'.*?\'|`.*?`)/',
            '<span class="syntax-string">$1</span>',
            $code
        );

        // Numbers
        $code = preg_replace(
            '/\b(\d+(?:\.\d+)?)\b/',
            '<span class="syntax-number">$1</span>',
            $code
        );

        // Comments
        $code = preg_replace(
            '/(\/\/.*)/',
            '<span class="syntax-comment">$1</span>',
            $code
        );

        // Function definitions
        $code = preg_replace(
            '/\b(function)\s+([a-zA-Z_$][a-zA-Z0-9_$]*)/',
            '<span class="syntax-keyword">$1</span> <span class="syntax-function">$2</span>',
            $code
        );

        // Function calls
        $code = preg_replace(
            '/\b([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(/',
            '<span class="syntax-function">$1</span>(',
            $code
        );

        return $code;
    }

    protected function highlightHtmlSyntax(string $code): string
    {
        // HTML/XML tags
        $code = preg_replace(
            '/(<\/?)([\w\-]+)/',
            '$1<span class="syntax-tag">$2</span>',
            $code
        );

        // Attributes
        $code = preg_replace(
            '/\s([\w\-:@]+)(\s*=\s*)/',
            ' <span class="syntax-attr">$1</span>$2',
            $code
        );

        // Strings in attributes
        $code = preg_replace(
            '/=\s*(&quot;.*?&quot;|\'.*?\')/',
            '= <span class="syntax-string">$1</span>',
            $code
        );

        // Comments
        $code = preg_replace(
            '/(<!--.*?-->)/',
            '<span class="syntax-comment">$1</span>',
            $code
        );

        // Closing brackets
        $code = preg_replace(
            '/(\/?>)/',
            '<span class="syntax-tag">$1</span>',
            $code
        );

        return $code;
    }

    protected function highlightCssSyntax(string $code): string
    {
        // Selectors
        $code = preg_replace(
            '/([a-zA-Z0-9_\-\.#\[\]=]+)(\s*\{)/',
            '<span class="syntax-tag">$1</span>$2',
            $code
        );

        // Properties
        $code = preg_replace(
            '/(\s*)([\w\-]+)(\s*:)/',
            '$1<span class="syntax-attr">$2</span>$3',
            $code
        );

        // Values
        $code = preg_replace(
            '/(:\s*)([^;]+)(;)/',
            '$1<span class="syntax-string">$2</span>$3',
            $code
        );

        // Comments
        $code = preg_replace(
            '/(\/\*.*?\*\/)/',
            '<span class="syntax-comment">$1</span>',
            $code
        );

        // Colors
        $code = preg_replace(
            '/(#[a-fA-F0-9]{3,6})/',
            '<span class="syntax-number">$1</span>',
            $code
        );

        return $code;
    }

    protected function basicMarkdownParse(string $markdown): string
    {
        // Convert headings
        $markdown = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $markdown);
        $markdown = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $markdown);
        $markdown = preg_replace('/^# (.*?)$/m', '<h1>$1</h1>', $markdown);

        // Convert bold and italic
        $markdown = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $markdown);
        $markdown = preg_replace('/\*(.*?)\*/s', '<em>$1</em>', $markdown);

        // Convert lists
        $markdown = preg_replace_callback('/^\s*-\s+(.*?)$/m', fn ($m) => '<li>'.$m[1].'</li>', $markdown);
        $markdown = preg_replace_callback('/^\s*\d+\.\s+(.*?)$/m', fn ($m) => '<li>'.$m[1].'</li>', $markdown);
        $markdown = preg_replace('/(<li>.*?<\/li>\s*)+/s', '<ul>$0</ul>', $markdown);

        // Convert links
        $markdown = preg_replace('/\[(.*?)\]\((.*?)\)/s', '<a href="$2">$1</a>', $markdown);

        // Convert images
        $markdown = preg_replace('/!\[(.*?)\]\((.*?)\)/s', '<img src="$2" alt="$1">', $markdown);

        // Code blocks with more flexible regex
        $markdown = preg_replace_callback(
            '/^```([\w-]*)?\s*\n([\s\S]*?)^```\s*$/m',
            function ($m) {
                $language = trim($m[1] ?? 'text');

                return "<pre><code class=\"language-{$language}\">{$m[2]}</code></pre>";
            },
            $markdown
        );

        // Convert inline code
        $markdown = preg_replace('/`(.*?)`/s', '<code>$1</code>', $markdown);

        // Convert blockquotes
        $markdown = preg_replace('/^>\s+(.*?)$/m', '<blockquote>$1</blockquote>', $markdown);

        // Convert horizontal rules
        $markdown = preg_replace('/^---$/m', '<hr>', $markdown);

        // Wrap paragraphs (anything that's not already wrapped)
        $markdown = preg_replace('/^(?!<[a-z]).+/m', '<p>$0</p>', $markdown);

        return $markdown;
    }

    public function __toString(): string
    {
        return (string) $this->render();
    }
}
