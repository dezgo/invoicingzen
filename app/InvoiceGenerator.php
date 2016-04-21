<?php

namespace App;

use App\Factories\SettingsFactory;

class InvoiceGenerator
{
    const TOKEN_START = "$*";
    const TOKEN_END = "*$";

    private $invoice;
    private $template;
    private $settings;

    public function __construct(Invoice $invoice, $template)
    {
        $this->template = $template;
        if (!$this->checkTemplate()) {
            throw new \RuntimeException(trans('invoice.token-mismatch'));
        }

        $this->invoice = $invoice;
        $this->settings = SettingsFactory::create();
    }

    public function output()
    {
        $position = 0;
        $output = $this->template;
        $start_position = $this->findStartToken($position);
        while ($start_position !== false) {
            $end_position = $this->findEndToken($start_position) + strlen(self::TOKEN_END);
            $length = $end_position - $start_position;
            $foundWithTokens = substr($this->template, $start_position, $length);
            $output = $this->replaceToken($foundWithTokens, $output);
            $start_position = $this->findStartToken($end_position);
        }
        return $output;
    }

    private function checkTemplate()
    {
        if (substr_count($this->template, self::TOKEN_START) !=
            substr_count($this->template, self::TOKEN_END)) {
                return false;
        }
        return true;
    }

    private function findStartToken($offset)
    {
        return $this->findToken(self::TOKEN_START, $offset);
    }

    private function findEndToken($offset)
    {
        return $this->findToken(self::TOKEN_END, $offset);
    }

    private function findToken($token, $offset)
    {
        return strpos($this->template, $token, $offset);
    }

    private function replaceToken($foundWithTokens, $output)
    {
        $foundWithoutTokens = $this->trimTokens($foundWithTokens);
        $fields = $this->invoice->toArray();
        if (array_key_exists($foundWithoutTokens, $fields)) {
            $output = str_ireplace($foundWithTokens, $fields[$foundWithoutTokens], $output);
        }
        else {
            $output = str_ireplace($foundWithTokens, $this->customReplacement($foundWithoutTokens), $output);
        }
        return $output;
    }

    private function trimTokens($foundWithTokens)
    {
        $start = strlen(self::TOKEN_START);
        $length = strlen($foundWithTokens) - strlen(self::TOKEN_START) - strlen(self::TOKEN_END);
        $return = substr($foundWithTokens, $start, $length);
        return $return;
    }

    private function customReplacement($field)
    {
        switch ($field) {
            case 'logo':
                if (Auth::user()->logo_filename != '') {
                    return "<img class='left-block' src='".secure_url('/images/'.Auth::user()->logo_filename)."' />";
                }
                else {
                    return "";
                }

            case 'business_name':
                return Auth::user()->business_name;
        }
    }

}
