<?php

namespace Dero\BBCodes;

use Dero\GenSynth\GenSynth;

/**
 * BBCodes class, used for replacing supported BBCodes with the proper HTML. Also turns URLs into hyperlinks
 * Supported tags:
 * [b][u][i][url=<URI>][urls][img=<ALT/TITLE>][noparse]
 * [quote=<SPEAKER>][pre][code=<LANGUAGE>][title][subtitle][list][item]
 *
 * @author    Ryan Pallas <ryan@derokorian.com>
 * @copyright Copyright (c) 2011-2015, Ryan Pallas
 */
class BBCodes
{
    const PATTERN_PHP = '#\[PHP\](.*?)\[\/PHP\]#is';
    const PATTERN_CODE = '#\[CODE(=([a-z]+))?\](.*?)\[\/CODE\]#is';
    const PATTERN_URL_BLOCK = '#\[URLS\](.*?)\[\/URLS\]#is';
    const PATTERN_URL = '#\[URL(=(.*?))?\](.*?)\[\/URL\]#i';
    const PATTERN_IMAGE = '#\[IMG(=(.*?))?\](.*?)\[\/IMG\]#i';
    const PATTERN_QUOTE = '#\[QUOTE(=(.*?))?\](.*?)\[\/QUOTE\]#i';
    const PATTERN_NO_PARSE = '#\[NOPARSE\](.*?)\[\/NOPARSE\]#is';
    const PATTERN_TITLE = '@\s*\[((SUB)?TITLE)\](.*)\[/\1\]\s*@i';
    const PATTERN_BASE = '@\[(B|I|U|PRE|STRIKE)\](.*)\[/\1\]@i';
    const PATTERN_ITEM = '@\s*\[ITEM\](.*?)\[/ITEM\]\s*@i';
    const PATTERN_LIST = '@\s*\[LIST\](.*?)\[/LIST\]\s*@is';
    const PATTERN_LINK = '#((http|ftp)s?://)?(([a-z][a-z0-9-]*\.)+)?[a-z][a-z0-9-]*\.([a-z]{2,6})(/[^\s]*)?#is';
    const OPT_URLS = 1;
    const OPT_BASE = 2;
    const OPT_CODE = 4;

    // Clean Options
    const OPT_LIST = 8;
    const OPT_QUOTE = 16;
    const OPT_BASIC = 31;
    const OPT_TITLE = 32;
    const OPT_IMG = 64;
    const OPT_NOPARSE = 128; // 31 = 1|2|4|8|16
    const OPT_FULL = 255;
    private $codeReplacements = [];
    private $noParseReplacements = [];
    private $str; // 255 = 1|2|4|8|16|32|64|128

    /**
     * Same as calling clean($input, BBCodes::OPT_FULL)
     *
     * @param string $input
     *
     * @return string
     */
    public function fullClean($input)
    {
        return $this->clean($input, self::OPT_FULL);
    }

    /**
     * @param     $input
     * @param int $opt
     *
     * @return string
     */
    public function clean($input, $opt = self::OPT_BASIC)
    {
        $this->str = $input;

        $this->handlePrefinds($opt);
        $this->handleBasics($opt);

        if (self::OPT_TITLE & $opt) {
            $this->parseTitle();
        }
        if (self::OPT_URLS & $opt) {
            $this->parseURL();
        }
        if (self::OPT_IMG & $opt) {
            $this->parseImage();
        }

        if (self::OPT_URLS & $opt) {
            $this->parseURLBlock();
        }

        $this->str = nl2br($this->str);

        $this->handlePostfinds($opt);

        return $this->str;
    }

    private function handleBasics($opt)
    {
        if (self::OPT_LIST & $opt) {
            $this->parseList();
        }
        if (self::OPT_BASE & $opt) {
            $this->parseBase();
        }
        if (self::OPT_QUOTE & $opt) {
            $this->parseQuote();
        }
    }

    private function handlePrefinds($opt)
    {
        if (self::OPT_NOPARSE & $opt) {
            $this->findNoParse();
        }
        if (self::OPT_CODE & $opt) {
            $this->findCode();
        }
    }

    private function findNoParse()
    {
        if (preg_match_all(self::PATTERN_NO_PARSE, $this->str, $matches)) {
            $this->noParseReplacements = $matches[1];
        }
    }

    private function findCode()
    {
        $this->codeReplacements = [];
        if (preg_match_all(self::PATTERN_CODE, $this->str, $matches)) {
            foreach ($matches[3] as $k => $v) {
                $lang = empty($matches[2][$k]) ? '' : ($matches[2][$k] . ' ');
                $code = empty($matches[2][$k]) || class_exists(GenSynth::class)
                    ? "<pre>$v</pre>"
                    : GenSynth::highlight_string($v, strtolower($matches[2][$k]), true, GenSynth::OPT_HEADER_DIV & GenSynth::OPT_LINE_NUMBERS_FANCY & GenSynth::OPT_CAPS_NO_CHANGE);

                $this->codeReplacements[$k] = '<div class="bbcodes_code_wrapper"><span class="bbcodes_code_language">' . $lang . 'Code:</span>' . "\n"
                                              . '<div class="bbcodes_code">' . $code . '</div></div>';
            }
        }
    }

    private function parseCode()
    {
        if (preg_match_all(self::PATTERN_CODE, $this->str, $matches)) {
            $this->str = str_replace($matches[0], $this->codeReplacements, $this->str);
        }
    }

    private function parseList()
    {
        while (preg_match(self::PATTERN_LIST, $this->str)) {
            $this->str = preg_replace_callback(self::PATTERN_LIST, function ($match) {
                if (preg_match(BBCodes::PATTERN_ITEM, $match[1])) {
                    return '<ul>' . preg_replace(BBCodes::PATTERN_ITEM, '<li>\\1</li>', $match[1]) . '</ul>';
                }

                return $match[1];
            }, $this->str);
        }
    }

    private function parseBase()
    {
        while (preg_match(self::PATTERN_BASE, $this->str)) {
            $this->str = preg_replace_callback(self::PATTERN_BASE, function ($match) {
                return strtolower($match[1]) == 'b'
                    ? "<strong>$match[2]</strong>"
                    : "<$match[1]>$match[2]</$match[1]>";
            }, $this->str);
        }
    }

    private function parseTitle()
    {
        while (preg_match(self::PATTERN_TITLE, $this->str)) {
            $this->str = preg_replace_callback(self::PATTERN_TITLE, function ($match) {
                switch (strtolower($match[1])) {
                    case 'title':
                        return "<h2>$match[3]</h2>";
                    case 'subtitle':
                        return "<h3>$match[3]</h3>";
                    default:
                        return $match[3];
                }
            }, $this->str);
        }
    }

    private function parseURL()
    {
        if (preg_match_all(self::PATTERN_URL, $this->str, $matches)) {
            $urlReplacements = [];
            foreach ($matches[0] as $k => $v) {
                $display = empty($matches[2][$k]) ? static::shortenURL($matches[3][$k]) : $matches[3][$k];
                $url = empty($matches[2][$k]) ? $matches[3][$k] : $matches[2][$k];
                $urlReplacements[$v] = '<a href="' . $url . '" target="_blank">' . $display . '</a>';
            }
            $this->str = strtr($this->str, $urlReplacements);
        }
    }

    public static function shortenURL($input)
    {
        $output = strtolower($input);
        $output = preg_replace("#^(http|ftp)s?://#", "", $output);
        if (strlen($output) > 50) {
            $output = substr($output, 0, strpos($output, "/") + 5) . '...';
        }

        return $output;
    }

    private function parseImage()
    {
        if (preg_match_all(self::PATTERN_IMAGE, $this->str, $matches)) {
            $imgReplacements = [];
            foreach ($matches[0] as $k => $v) {
                $url = empty($matches[2][$k]) ? $matches[3][$k] : $matches[2][$k];
                $imgReplacements[$v] = '<img src="' . $url . '" alt="' . ($url != $matches[3][$k] ? $matches[3][$k] : '') . '" title="' . ($url != $matches[3][$k] ? $matches[3][$k] : '') . '" />';
            }
            $this->str = strtr($this->str, $imgReplacements);
        }
    }

    private function parseQuote()
    {
        if (preg_match_all(self::PATTERN_QUOTE, $this->str, $matches)) {
            $quoteReplacements = [];
            foreach ($matches[0] as $k => $v) {
                $by = empty($matches[2][$k]) ? '' : ('<span class="bbcodes_quote_author">Quote by: <strong>' . $matches[2][$k] . '</strong></span>' . "\n");
                $body = $matches[3][$k];
                $quoteReplacements[$v] = '<div class="bbcodes_quote">' . $by . $body . '</div>';
            }
            $this->str = strtr($this->str, $quoteReplacements);
        }
    }

    private function parseURLBlock()
    {
        if (preg_match_all(self::PATTERN_URL_BLOCK, $this->str, $matches)) {
            $replace = [];
            foreach ($matches[1] as $k => $v) {
                $replace[$matches[0][$k]] = $this->findURLs($v);
            }
            $this->str = strtr($this->str, $replace);
        }
    }

    private function findURLs($str)
    {
        return preg_replace_callback(self::PATTERN_LINK, function ($match) {
            return '<a href="' . $match[0] . '">' . static::shortenURL($match[0]) . '</a>';
        }, $str);
    }

    private function parseNoParse()
    {
        if (preg_match_all(self::PATTERN_NO_PARSE, $this->str, $matches)) {
            $this->str = str_replace($matches[0], $this->noParseReplacements, $this->str);
        }
    }

    /**
     * @param $opt
     */
    private function handlePostfinds($opt)
    {
        if (self::OPT_CODE & $opt) {
            $this->parseCode();
        }
        if (self::OPT_NOPARSE & $opt) {
            $this->parseNoParse();
        }
    }
}