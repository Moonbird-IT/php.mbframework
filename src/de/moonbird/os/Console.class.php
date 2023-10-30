<?php

/**
 * Common functions to log to OS console.
 */
abstract class Console
{

    public static $disabled = FALSE;

    public static function disableOutput($disableFlag) {
        self::$disabled = $disableFlag;
    }

    /**
     * Output an error to the console.
     * @param $message
     * @return void
     */
    public static function error($message)
    {
        if (self::$disabled) return;
        self::output('================== ERROR     ==============================');
        self::output($message);
        self::output('================== END ERROR ==============================');
        self::emptyLine();
        sleep(4);
    }

    /**
     * Output a warning to the console.
     * @param $message
     * @return void
     */
    public static function warning($message)
    {
        if (self::$disabled) return;
        self::output('+++++++++++ Warning     +++++++++++++++++++++++++++++++++');
        self::output($message);
        self::output('+++++++++++ Warning end +++++++++++++++++++++++++++++++++');
        self::emptyLine();
        sleep(2);
    }

    /**
     * Output an information to the console.
     * @param $message
     * @return void
     */
    public static function info($message)
    {
        if (self::$disabled) return;
        self::output('--------- Information     -----------------------------');
        self::output($message);
        self::output('--------- Information end -----------------------------');
        self::emptyLine();

    }

    /**
     * Output a line to the console.
     * @param $message
     * @return void
     */
    public static function output($message)
    {
        if (self::$disabled) return;
        fputs(STDOUT, $message . "\n");
    }

    /**
     * Dump an array to the console.
     * @param $message
     * @return void
     */
    public static function dump($array)
    {
        if (self::$disabled) return;
        self::output(print_r($array, TRUE));
    }

    /**
     * Output an empty line to the console.
     * @return void
     */
    private static function emptyLine()
    {
        if (self::$disabled) return;
        fputs(STDOUT, "\n");
    }
}