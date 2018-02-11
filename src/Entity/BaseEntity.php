<?php

namespace App\Entity;

abstract class BaseEntity
{
    /**
     * @return mixed
     */
    public function getLangCurrent()
    {
        $locale = !empty($_SERVER['CURRENT_LOCALE']) ? $_SERVER['CURRENT_LOCALE'] : 'ua';
        if (!empty($locale) && property_exists($this, 'lang') && count($this->lang) > 0) {
            foreach ($this->lang as $lang) {
                if ($lang->getLanguage()->getIso2() === $locale) {
                    return $lang;
                }
            }
        }

        return null;
    }
}