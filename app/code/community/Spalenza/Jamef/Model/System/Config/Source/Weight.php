<?php
/**
 * Denis Spalenza
 *
 * @category   Spalenza
 * @package    Spalenza_Jamef
 * @author     Denis Spalenza <deniscsz@gmail.com>
 * @license    Open Software License ("OSL") v. 3.0 - Veja em VF_LICENSE.txt
 */

class Spalenza_Jamef_Model_System_Config_Source_Weight
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
		
            array('value' => 'kg', 'label'=>Mage::helper('adminhtml')->__('Quilogramas (Kg)')),
            array('value' => 'gr', 'label'=>Mage::helper('adminhtml')->__('Gramas (g)')),
        );
    }

}
