<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Middleware;

class TransformArabicToEasternNumbers extends BaseNumbersMiddleware
{
    protected $from = 'arabic';

    /**
     * {@inheritdoc}
     */
    public function getExcept(): array
    {
        return array_merge(parent::getExcept(), $this->getOption('except_from_arabic_to_eastern', []));
    }
}
