<?php
/**
 * Denis Spalenza
 *
 * @category   Spalenza
 * @package    Spalenza_Jamef
 * @author     Denis Spalenza <deniscsz@gmail.com>
 * @license    Open Software License ("OSL") v. 3.0 - Veja em VF_LICENSE.txt
 */

class Spalenza_Jamef_Model_System_Config_Source_Type
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Rodoviário')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Aéreo')),
        );
    }

}
