<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExceptionCode
 *
 * @author XDE11069
 */
class ExceptionCode {
    // db exceptions
    const DB_INVALIDURL=1;
    const DB_SEQEXCEPTION=2;
    const DB_SQLEXCEPTION=3;

    // io exceptions
    const IO_FILEOPEN=10001;
    const IO_WRITEERROR=10002;
    const IO_FILENOTFOUND=10003;
    
    // illegal arguments exceptions
    const IA_MISSINGARGUMENT=20001;

    const RE_MISSINGSETUP=30001;

    // not implemented exceptions
    const NI_EXCEPTION= 40001;
}
?>
