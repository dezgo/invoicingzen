<?php

namespace App\Services\CustomInvoice;

use App\Services\CustomInvoice\TemplateField\TemplateFieldFactory;
use App\Services\CustomInvoice\FieldWriter\FieldWriterFactory;

class InvoiceGenerator
{
    const TOKEN_START = "$*";
    const TOKEN_END = "*$";
    const TOKEN_DELIMITER = ".";

    private $template;

    public function __construct($template)
    {
        $this->template = $template;
        if (!$this->checkTemplate()) {
            throw new \Exception(trans('invoice.token-mismatch'));
        }
    }

    public function output($invoice)
    {
        $position = 0;
        $output = $this->template;
        $start_position = $this->findStartToken($position);
        while ($start_position !== false) {
            $end_position = $this->findEndToken($start_position) + strlen(self::TOKEN_END);
            $length = $end_position - $start_position;
            $foundWithTokens = substr($this->template, $start_position, $length);
            $output = $this->replaceToken($foundWithTokens, $output, $invoice);
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

    private function replaceToken($foundWithTokens, $output, $invoice)
    {
        $foundWithoutTokens = $this->trimTokens($foundWithTokens);
        $templateField = TemplateFieldFactory::getTemplateField($foundWithoutTokens[0], $invoice);
        $fieldWriter = FieldWriterFactory::getFieldWriter($foundWithoutTokens[0], $foundWithoutTokens[1]);
        $replacementContent = $fieldWriter->write($templateField);
        return str_ireplace($foundWithTokens, $replacementContent, $output);
    }

    // removve tokens from found field and separate by delimiter
    // returns array
    private function trimTokens($foundWithTokens)
    {
        $start = strlen(self::TOKEN_START);
        $length = strlen($foundWithTokens) - strlen(self::TOKEN_START) - strlen(self::TOKEN_END);
        return explode(self::TOKEN_DELIMITER, substr($foundWithTokens, $start, $length));
    }
}
