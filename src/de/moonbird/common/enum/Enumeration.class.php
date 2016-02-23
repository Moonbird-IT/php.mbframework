<?php
/**
 * @author: Sascha Meyer, Moonbird IT
 * @Date: 04.04.12
 */
abstract class Enumeration
{
// will be initialized in init rountine
    private $constants = array();

    public function get()
    {
        $reflect = new ReflectionObject($this);
        $this->constants= $reflect->getConstants();
        unset($reflect);
        return $this->constants;
    }
}

?>