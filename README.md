# bbcodes

[![Build Status](https://travis-ci.org/derokorian/bbcodes.svg?branch=master)](https://travis-ci.org/derokorian/bbcodes) 
[![Code Climate](https://codeclimate.com/github/derokorian/bbcodes/badges/gpa.svg)](https://codeclimate.com/github/derokorian/bbcodes) 
[![Test Coverage](https://codeclimate.com/github/derokorian/bbcodes/badges/coverage.svg)](https://codeclimate.com/github/derokorian/bbcodes/coverage)

Simple class to parse BBCodes

## Install via composer

    composer require derokorian/bbcodes

## Use the class

    use Dero\BBCodes\BBCodes;
    
    echo (new BBCodes)->fullClean($string);
    
    // alternately, call clean and provide the options to use
    echo (new BBCodes)->clean($string, BBCodes::OPT_LIST | BBCodes::OPT_BASIC);
    
    // The following options exist
    BBCodes::OPT_URLS
    BBCodes::OPT_BASE (pre, strike, b, i, u)
    BBCodes::OPT_CODE
    BBCodes::OPT_LIST
    BBCodes::OPT_QUOTE
    
    BBCodes::OPT_BASIC = OPT_URLS | OPT_BASE | OPT_CODE | OPT_LIST | OPT_QUOTE
    
    BBCodes::OPT_TITLE
    BBCodes::OPT_IMG
    BBCodes::OPT_NOPARSE
    
    BBCodes::OPT_FULL = OPT_BASIC | OPT_TITLE | OPT_IMG | OPT_NOPARSE

## Tags
The following tags are available, depending on the options used. Tags are not case-sensitive.

    [b]bold[/b]
    [i]italic[/i]
    [u]underline[/u]
    [strike]struck through text[/strike]
    
    [title]some title text[/title]
    [subtitle]a smallter title text[/subtitle]
    
    [list]
        [item]list item[/item]
        [item]list item[/item]
    [/list]
    
    [url]http://example.com[/url]
    [url=http://example.com]some text[/url]
    [urls]block of text containing many raw urls[/urls]
    
    [img]http://url.to/image.jpg[/img] 
    [img=http://url.to/image.jpg]alt/title[/img] 
    
    [noparse]text not to be parsed for other BBCodes[/noparse]
    [quote]something attributed to someone else[/quote] 
    [quote=author]something attributed to someone else[/quote] 
    
    [pre]preformatted text[/pre]
    [code]some code that needs formatting maintained[/code]
    
### Code Tag upgrade
By default the code tag only preserves formatting, however if you require the suggested 
GenSynth package - then code may be modified to specify the language

    composer require derokorian/gen-synth
    
    [code=language]code to be highlighted as the specific language[/code]
  