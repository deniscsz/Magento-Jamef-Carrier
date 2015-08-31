<?php
/**
 * Denis Spalenza
 *
 * @category   Spalenza
 * @package    Spalenza_Jamef
 * @author     Denis Spalenza <deniscsz@gmail.com>
 * @license    Open Software License ("OSL") v. 3.0 - Veja em VF_LICENSE.txt
 */

class Spalenza_Jamef_Model_System_Config_Source_Products
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '000010', 'label'=>Mage::helper('adminhtml')->__('Alimentos Industrializados')),
            array('value' => '000014', 'label'=>Mage::helper('adminhtml')->__('Calçado')),
            array('value' => '000008', 'label'=>Mage::helper('adminhtml')->__('Confecções')),
            array('value' => '000004', 'label'=>Mage::helper('adminhtml')->__('Conforme Nota Fiscal')),
            array('value' => '000011', 'label'=>Mage::helper('adminhtml')->__('Cosméticos/Material Cirurgico')),
            array('value' => '000006', 'label'=>Mage::helper('adminhtml')->__('Jornais/Revistas')),
            array('value' => '000005', 'label'=>Mage::helper('adminhtml')->__('Livros')),
            array('value' => '000013', 'label'=>Mage::helper('adminhtml')->__('Material Escolar')),
        );
    }

}
