<?php

namespace App\Services\CustomInvoice;

use App\Services\CustomInvoice\TemplateField\TemplateFieldFactory;
use App\Services\CustomInvoice\FieldWriter\FieldWriterFactory;

class InvoiceGenerator
{
    const TOKEN_START = "$*";
    const TOKEN_END = "*$";
    const TOKEN_DELIMITER = ".";
    const INVOICE_LOOP_START_FIELD = 'StartInvoiceItems';
    const INVOICE_LOOP_END_FIELD = 'EndInvoiceItems';

    public function output($template, $invoice)
    {
        if (!$this->checkTemplate($template)) {
            throw new \Exception(trans('invoice.token-mismatch'));
        }

        $output = "";
        $end_position = 0;
        $start_position = $this->findStartToken($template, 0);
        while ($start_position !== false) {
            $previous_end_position = $end_position;
            $end_position = $this->findEndToken($template, $start_position) + strlen(self::TOKEN_END);
            $length = $end_position - $start_position;
            $foundWithTokens = substr($template, $start_position, $length);
            $foundWithoutTokens = $this->trimTokens($foundWithTokens);
            $output = $output.substr($template, $previous_end_position, $start_position - $previous_end_position);
            if ($foundWithoutTokens[0] == self::INVOICE_LOOP_START_FIELD) {
                $invoice_items_block = $this->getInvoiceItemsBlock($template, $end_position);
                foreach ($invoice->invoice_items as $invoice_item) {
                    $output = $output.$this->output($invoice_items_block, $invoice_item);
                }
                $end_position = $start_position +
                    strlen(self::TOKEN_START) +
                    strlen(self::INVOICE_LOOP_START_FIELD) +
                    strlen(self::TOKEN_END) +
                    strlen($invoice_items_block) +
                    strlen(self::TOKEN_START) +
                    strlen(self::INVOICE_LOOP_END_FIELD) +
                    strlen(self::TOKEN_END);
            }
            else {
                $output = $output.$this->replaceFieldWithValue($foundWithoutTokens, $invoice);
            }
            $start_position = $this->findStartToken($template, $end_position);
        }
        $output = $output.substr($template, $end_position);

        return $output;
    }

    private function getInvoiceItemsBlock($template, $start_position)
    {
        $length = strpos($template,
            self::TOKEN_START.'EndInvoiceItems'.self::TOKEN_END,
            $start_position) - $start_position;
        return substr($template, $start_position, $length);
    }

    private function checkTemplate($template)
    {
        if (substr_count($template, self::TOKEN_START) !=
            substr_count($template, self::TOKEN_END)) {
                return false;
        }
        return true;
    }

    private function findStartToken($template, $offset)
    {
        return $this->findToken($template, self::TOKEN_START, $offset);
    }

    private function findEndToken($template, $offset)
    {
        return $this->findToken($template, self::TOKEN_END, $offset);
    }

    private function findToken($template, $token, $offset)
    {
        return strpos($template, $token, $offset);
    }

    private function replaceFieldWithValue($foundWithoutTokens, $invoice)
    {
        $templateField = TemplateFieldFactory::getTemplateField($foundWithoutTokens[0], $invoice);
        $fieldWriter = FieldWriterFactory::getFieldWriter($foundWithoutTokens[0], $foundWithoutTokens[1]);
        return $fieldWriter->write($templateField);
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
