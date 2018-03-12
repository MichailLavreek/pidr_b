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
        if (!empty($locale) && method_exists($this, 'getLang') && count($this->getLang()) > 0) {
            foreach ($this->getLang() as $lang) {
                if ($lang->getLanguage()->getIso2() === $locale) {
                    return $lang;
                }
            }
        }

        return null;
    }
}