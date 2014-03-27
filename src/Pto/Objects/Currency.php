<?php

namespace Pto\Objects;

/**
 * Currency
 *
 * @author Petr Poupě
 */
class Currency extends Object
{

    /** @var string */
    private $code;

    /** @var float */
    private $rate;

    /** @var mixed[] */
    private $other = array();

    public function __construct($code, $rate)
    {
        $this->code = self::code($code);
        $this->setRate($rate);
    }

    public function __set($name, $value)
    {
        if (!isset($this->{$name})) {
            $this->other[$name] = $value;
            return $value;
        }
        return parent::__set($name, $value);
    }

    public function &__get($name)
    {
        if (array_key_exists($name, $this->other)) {
            return $this->other[$name];
        }
        return parent::__get($name);
    }

    public function __isset($name)
    {
        return isset($this->other[$name]);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setRate($rate)
    {
        $value = (float) $rate;
        $this->rate = $value;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function isRate($compare)
    {
        return (bool) ($this->getRate() === (float) $compare);
    }

    public function setProfil($options)
    {
        if ($options instanceof NumberFormat) {
            $this->other["profil"] = $options;
        } else {
            $profil = new NumberFormat;
            if (is_array($options) && $options !== array()) {
                foreach ($options as $key => $value) {
                    $f = 'set' . ucfirst($key);
                    $profil->$f($value);
                }
            } else {
                $profil->setSymbol($this->code);
            }
            $this->other["profil"] = $profil;
        }
        return $this;
    }

    public static function code($code)
    {
        return \Nette\Utils\Strings::webalize($code);
    }

    public function __toString()
    {
        return $this->getCode();
    }

}
