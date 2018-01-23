<?php

namespace App\Entity;

abstract class BaseEntity
{
    /**
     * @return mixed
     */
    public function getLangCurrent()
    {
        $locale = $_SERVER['CURRENT_LOCALE'];

        if (!empty($locale) && property_exists($this, 'lang')) {
            foreach ($this->lang as $lang) {
                if ($lang->getLanguage()->getIso2() === $locale) return $lang;
            }
        }

        return null;
    }
}