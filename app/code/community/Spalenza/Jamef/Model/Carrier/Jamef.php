<?php
/**
 * Denis Spalenza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the New BSD License.
 * It is also available through the world-wide-web at this URL:
 * http://www.pteixeira.com.br/new-bsd-license/
 *
 * @category   Spalenza
 * @package    Spalenza_Jamef
 * @copyright  Copyright (c) 2014 Pedro Teixeira (http://denisspalenza.com/)
 * @author     Denis Spalenza <deniscsz@gmail.com>
 * @license    http://denisspalenza.com/new-bsd-license/ New BSD License
 */

class Spalenza_Jamef_Model_Carrier_Jamef     
		extends Mage_Shipping_Model_Carrier_Abstract
		implements Mage_Shipping_Model_Carrier_Interface
	{  
        protected $_code            = 'jamef';
        protected $_fromZip         = null;
        protected $_toZip           = null;
        
        /** 
        * Collect rates for this shipping method based on information in $request 
        * 
        * @param Mage_Shipping_Model_Rate_Request $data 
        * @return Mage_Shipping_Model_Rate_Result 
        */  
        public function collectRates(Mage_Shipping_Model_Rate_Request $request){  
            $result = Mage::getModel('shipping/rate_result');
            
            $this->_fromZip = Mage::getStoreConfig('shipping/origin/postcode', $this->getStore());
            $this->_toZip = $request->getDestPostcode();
            
            $resposta = $this->getResponseWs($this->_toZip);
            
            if($this->checkResposta($resposta)) {
                $method = Mage::getModel('shipping/rate_result_method');
                $method->setCarrier($this->_code);
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethod($this->_code);
                $method->setMethodTitle($this->getConfigData('name'));
                $method->setPrice($this->checkResposta($resposta));
                $method->setCost($this->checkResposta($resposta));
                $result->append($method);
			}
			
            return $result;
        }
        
        /**
         * Return if Response of WebService is valid to show a user
         * 
         * @param string
         * @return mixed
         */
        public function checkResposta($answer) {
            $array = explode(' - ',$answer);
            $result = end($array);
            
            if($result == 'Rota nao atendida pela transportadora') {
                return false;
            }
            else {
                return str_replace(',','.',substr($result, 0, -4));
            }
        }
        
        /**
         * Get Web Response of Jamef WebService
         */
        public function getResponseWs($_cep) {
            $url = $this->getUrlWebservice();
            
            $client = new Zend_Http_Client($url);
            $client->setConfig(array(
                'timeout' => $this->getConfigData('ws_timeout')
            ));
            
            $_info = $this->getInformations();
            
            $client->setParameterGet('P_CIC_NEGC',$this->getConfigData('cnpj'));
            $client->setParameterGet('P_CEP',$this->_toZip);
            $client->setParameterGet('P_VLR_CARG',str_replace('.',',',$this->geraValor()));
            $client->setParameterGet('P_PESO_KG',str_replace('.',',',$_info['weight']));
            $client->setParameterGet('P_CUBG',str_replace('.',',',$_info['cubagem']));
            $client->setParameterGet('P_COD_REGN',str_replace('.',',',$this->getConfigData('cod_regn')));
            $client->setParameterGet('P_UF',$this->getUfbyRegn());
            
            return $client->request()->getBody();
        }
        
        /**
         * Return UF of Cod Region
         * 
         * @return string
         */
        protected function getUfbyRegn() {
            switch($this->getConfigData('cod_regn')) {
                case "122": return 'SP';
                case "111": return 'SP';
                case "1": return 'MG';
                case "13": return 'SC';
                case "12": return 'SP';
                case "10": return 'PR';
                case "11": return 'SC';
                case "148": return 'GO';
                case "109": return 'SC';
                case "149": return 'AM';
                case "14": return 'RS';
                case "121": return 'SP';
                case "3": return 'RJ';
                case "2": return 'SP';
                case "125": return 'SP';
                case "124": return 'SP';
                case "108": return 'MG';
                case "7": return 'ES';
                default: return 'SP';
            }
        }
        
        /**
         * Return Informations of Products: Weight (Kg or Gr) and Weight "Cubado"
         *
         * @return float
         */
        protected function getInformations() {
            $cartItems = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
            $totalWeight = 0.0;
            $totalCubagem = 0.0;
            
            foreach($cartItems as $item) {
                $_product = $item->getProduct();
                
                $totalCubagem += ((float)$item->getQty()) * ((float)$_product->getVolumeLargura()/100) * ((float)$_product->getVolumeAltura()/100) * ((float)$_product->getVolumeComprimento()/100);
                $totalWeight += (float)$_product->getWeight();
            }
            
            if($this->getConfigData('weight_format') == 'gr') {
                $totalWeight = (float)$totalWeight * 1000.00; 
            }
            
            $retorno = array(
            	'weight' => $totalWeight,
                'cubagem' => $totalCubagem
            );
            
            return $retorno;
        }
        
        /**
         * Get Subtotal of Quote Cart
         * 
         * @return string
         */
        protected function geraValor() {
            $totals = Mage::getSingleton('checkout/cart')->getQuote()->getTotals();
            $subtotal = $totals["subtotal"]->getValue();
            return $subtotal;
        }

		/**
		 * Get allowed shipping methods
		 *
		 * @return array
		 */
		public function getAllowedMethods() {
			return array($this->_code=>$this->getConfigData('name'));
		}
		
		/**
		 * Return URL of Jamef's Web Service
		 *
		 * @return string
		 */
		public function getUrlWebservice() {
		    return $this->getConfigData('url');
		}
    }  
