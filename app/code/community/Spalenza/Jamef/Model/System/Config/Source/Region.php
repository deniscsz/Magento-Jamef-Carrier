<?php

/**
 * Denis Spalenza
 *
 * @category   Spalenza
 * @package    Spalenza_Jamef
 * @author     Denis Spalenza <deniscsz@gmail.com>
 * @license    Open Software License ("OSL") v. 3.0 - Veja em VF_LICENSE.txt
 */

class Spalenza_Jamef_Model_System_Config_Source_Region
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 31, 'label'=>Mage::helper('adminhtml')->__('Aracaju - SE (AJU)')),
            array('value' => 19, 'label'=>Mage::helper('adminhtml')->__('Barueri - SP (BAR)')),
            array('value' => 16, 'label'=>Mage::helper('adminhtml')->__('Bauru - SP (BAU)')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Belo Horizonte - MG (BHZ)')),
            array('value' => 9, 'label'=>Mage::helper('adminhtml')->__('Blumenau - SC (BNU)')),
            array('value' => 28, 'label'=>Mage::helper('adminhtml')->__('Brasília - DF (BSB)')),
            array('value' => 26, 'label'=>Mage::helper('adminhtml')->__('Criciúma - SC (CCM)')),
            array('value' => 3, 'label'=>Mage::helper('adminhtml')->__('Campinas - SP (CPQ)')),
            array('value' => 22, 'label'=>Mage::helper('adminhtml')->__('Caxias do Sul - RS (CXJ)')),
            array('value' => 4, 'label'=>Mage::helper('adminhtml')->__('Curitiba - PR (CWB)')),
            array('value' => 38, 'label'=>Mage::helper('adminhtml')->__('Divinópolis - MG (DIV)')),
            array('value' => 34, 'label'=>Mage::helper('adminhtml')->__('Feira de Santana - BA (FES)')),
            array('value' => 11, 'label'=>Mage::helper('adminhtml')->__('Florianópolis - SC (FLN)')),
            array('value' => 32, 'label'=>Mage::helper('adminhtml')->__('Fortaleza - CE (FOR)')),
            array('value' => 24, 'label'=>Mage::helper('adminhtml')->__('Goiânia - GO (GYN)')),
            array('value' => 36, 'label'=>Mage::helper('adminhtml')->__('João Pessoa - PB (JPA)')),
            array('value' => 8, 'label'=>Mage::helper('adminhtml')->__('Joinville - SC (JOI)')),
            array('value' => 23, 'label'=>Mage::helper('adminhtml')->__('Juiz de Fora - MG (JDF)')),
            array('value' => 10, 'label'=>Mage::helper('adminhtml')->__('Londrina - PR (LDB)')),
            array('value' => 25, 'label'=>Mage::helper('adminhtml')->__('Manaus - AM (MAO)')),
            array('value' => 33, 'label'=>Mage::helper('adminhtml')->__('Maceió - AL (MCZ)')),
            array('value' => 12, 'label'=>Mage::helper('adminhtml')->__('Maringá - PR (MGF)')),
            array('value' => 5, 'label'=>Mage::helper('adminhtml')->__('Porto Alegre - RS (POA)')),
            array('value' => 27, 'label'=>Mage::helper('adminhtml')->__('Pouso Alegre - MG (PSA)')),
            array('value' => 18, 'label'=>Mage::helper('adminhtml')->__('Ribeirão Preto - SP (RAO)')),
            array('value' => 30, 'label'=>Mage::helper('adminhtml')->__('Recife - PE (REC)')),
            array('value' => 6, 'label'=>Mage::helper('adminhtml')->__('Rio de Janeiro - RJ (RIO)')),
            array('value' => 7, 'label'=>Mage::helper('adminhtml')->__('São Paulo - SP (SAO)')),
            array('value' => 21, 'label'=>Mage::helper('adminhtml')->__('São José dos Campos - SP (SJK)')),
            array('value' => 20, 'label'=>Mage::helper('adminhtml')->__('São José do Rio Preto - SP (SJP)')),
            array('value' => 29, 'label'=>Mage::helper('adminhtml')->__('Salvador - BA (SSA)')),
            array('value' => 17, 'label'=>Mage::helper('adminhtml')->__('Uberlândia - MG (UDI)')),
            array('value' => 39, 'label'=>Mage::helper('adminhtml')->__('Vitória da Conquista - BA (VDC)')),
            array('value' => 14, 'label'=>Mage::helper('adminhtml')->__('Vitória - ES (VIX)')),
        );
    }
}
