<?php
/**
 * Denis Spalenza
 *
 * @category   Spalenza
 * @package    Spalenza_Jamef
 * @author     Denis Spalenza <deniscsz@gmail.com>
 * @license    Open Software License ("OSL") v. 3.0 - Veja em VF_LICENSE.txt
 */
class Spalenza_Jamef_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function enabledDebug()
    {
        return Mage::getStoreConfigFlag('carriers/jamef/debug');
    }

    public function writeLog($obj)
    {
        if ($this->enabledDebug()) {
            if(is_string($obj)){
                Mage::log($obj, Zend_Log::DEBUG, 'spalenza_jamef.log', true);
            }else{
                Mage::log(var_export($obj, true), Zend_Log::DEBUG, 'spalenza_jamef.log', true);
            }
        }
    }
}