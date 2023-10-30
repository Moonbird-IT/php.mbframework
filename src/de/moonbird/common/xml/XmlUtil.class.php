<?php

abstract class XmlUtil
{
    public static function getAttribute($object, $attribute)
    {
        if (isset($object[$attribute])) {
            return (string)$object[$attribute];
        }
        return null;
    }
}