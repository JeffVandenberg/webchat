<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/23/2015
 * Time: 11:37 PM
 */

namespace JeffVandenberg\Message;


class MessageParser
{
    private $urlRegex = '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si';

    public function parse($message)
    {
        $message = $this->scrubHtml($message);
        $message = $this->parseLinks($message);
        return $message;
    }

    public function parseLinks($message)
    {
        return preg_replace_callback($this->urlRegex, function($match) {
            return '<a href="'.$match[0].'" target="_blank">'.$match[0].'</a>';
        }, $message);
    }

    public function scrubHtml($message)
    {
        return htmlspecialchars($message);
    }
}