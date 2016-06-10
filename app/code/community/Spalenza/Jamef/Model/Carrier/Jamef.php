<?php
/**
 * Denis Spalenza
 *
 * @category   Spalenza
 * @package    Spalenza_Jamef
 * @author     Denis Spalenza <deniscsz@gmail.com>
 * @license    Open Software License ("OSL") v. 3.0 - Veja em VF_LICENSE.txt
 */

class Spalenza_Jamef_Model_Carrier_Jamef     
		extends Mage_Shipping_Model_Carrier_Abstract
		implements Mage_Shipping_Model_Carrier_Interface
	{
        protected $_code            = 'spalenza_jamef';
        protected $_fromZip         = null;
        protected $_toZip           = null;
        protected $_totalPrice      = null;
        protected $_msgErro         = null;

        /**
         * Return Helper's Extension
         * @return Spalenza_Jamef_Helper_Data
         */
        public function getHelper()
        {
            return Mage::helper('jamef');
        }

        /**
        * Collect rates for this shipping method based on information in $request 
        * 
        * @param Mage_Shipping_Model_Rate_Request $data 
        * @return Mage_Shipping_Model_Rate_Result 
        */
        public function collectRates(Mage_Shipping_Model_Rate_Request $request)
        {
            if ($this->_inicialCheck($request) === false) {
                return false;
            }

            $result = Mage::getModel('shipping/rate_result');
            $this->_fromZip = Mage::getStoreConfig('shipping/origin/postcode', $this->getStore());
            $this->_toZip = $request->getDestPostcode();
            
            $resposta = $this->getResponseWs($this->_toZip, $request);
            
            if($this->checkResposta($resposta) && $this->_totalPrice) {
                if($this->getConfigFlag('debug')) {
                    $this->getHelper()->writeLog('PRICE ESTIMATED: '.$this->_totalPrice);
                }

                $method = Mage::getModel('shipping/rate_result_method');
                $method->setCarrier($this->_code);
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethod($this->_code);
                $method->setMethodTitle($this->getConfigData('name'));
                $method->setPrice((float)$this->_totalPrice);
                $method->setCost((float)$this->_totalPrice);
                $result->append($method);

                $this->_updateFreeMethodQuote($request);
			}
            elseif($this->getConfigFlag('showerrors')) {
                if($this->getConfigData('debug')) {
                    $this->getHelper()->writeLog($this->_throwError());
                }
                $result->append($this->_throwError());
            }
			
            return $result;
        }
        
        /**
         * Return if Response of WebService is valid to show a user
         * 
         * @param string
         * @return mixed
         */
        public function checkResposta($answer)
        {
            if(!is_object($answer)) {
                $this->_msgErro = 'Is not possible connect to Web Service';
                return false;
            }

            if(is_numeric(strpos($answer->JAMW0520_03RESULT->MSGERRO,'Ok -'))) {
                $allValues = $answer->JAMW0520_03RESULT->VALFRE->AVALFRE;
                $this->_totalPrice = 0.0;

                foreach($allValues as $value) {
                    $value->COMPONENTE = trim($value->COMPONENTE);
                    if($value->COMPONENTE == 'TF-TOTAL DO FRETE') {
                        $this->_totalPrice += (float)trim($value->TOTAL);
                        return true;
                    }
                }

                return false;
            }
            else {
                $this->_msgErro = $answer->JAMW0520_03RESULT->MSGERRO;
                return false;
            }
        }

        /**
         * Get Web Response of Jamef WebService
         * 
         * @param string
         * @param Mage_Shipping_Model_Rate_Request
         * @return string
         */
        public function getResponseWs($cep, $request)
        {
            $url = $this->getUrlWebservice();

            if(!$url) {
                $this->_msgErro = 'Bad Web Service URL';
                $retorno = NULL;
            }

            $info = $this->getInformations($request);

            try {
                $parameters = new stdClass();
                $parameters->TIPTRA = $this->getConfigData('tiptra');
                $parameters->CNPJCPF = $this->getConfigData('cnpj');
                $parameters->MUNORI = $this->getConfigData('munori');
                $parameters->ESTORI = $this->getConfigData('estori');
                $parameters->SEGPROD = $this->getConfigData('segprod');
                $parameters->QTDVOL = 1;
                $parameters->VALMER = number_format($this->geraValor(),2,'.','');
                $parameters->PESO = number_format($info['weight'],2,'.','');
                $parameters->METRO3 = number_format($info['cubagem'],2,'.','');
                $parameters->FILCOT = $this->getConfigData('filcot');
                $parameters->CNPJDES = $this->getConfigData('cnpj');
                $parameters->CEPDES = str_replace('-','',$cep);

                if($this->getConfigFlag('debug')) {
                    $this->getHelper()->writeLog($parameters);
                }

                $ws = new SoapClient($url,
                    array(
                        'trace'                 => 1,
                        'exceptions'            => 1,
                        'connection_timeout'    => $this->getConfigData('timeout') ? $this->getConfigData('timeout') : 30,
                        'style'                 => SOAP_DOCUMENT,
                        'use'                   => SOAP_LITERAL,
                        'soap_version'          => SOAP_1_1,
                        'encoding'              => 'UTF-8'
                    )
                );

                $retorno = $ws->JAMW0520_03($parameters);

                if($this->getConfigFlag('debug')) {
                    $this->getHelper()->writeLog($retorno);
                }
            }
            catch( SoapFault $fault ){
                $retorno = NULL;
                Mage::logException($fault);
            }

            return $retorno;
        }

        /**
         * Return Informations of Products: Weight (Kg or Gr) and Weight "Cubado"
         *
         * @return float
         */
        protected function getInformations($request)
        {
            $items = $this->_getItems($request);
            $totalWeight = 0.0;
            $totalCubagem = 0.0;
            
            foreach($items as $item) {
                $_product = $item->getProduct();
                
                $totalCubagem += ((float)$item->getQty()) * ((float)$_product->getVolumeLargura()/100) * ((float)$_product->getVolumeAltura()/100) * ((float)$_product->getVolumeComprimento()/100);
                $totalWeight += ((float)$item->getQty()) * (float)$_product->getWeight();
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
        protected function geraValor()
        {
            $totals = Mage::getSingleton('checkout/cart')->getQuote()->getTotals();
            $subtotal = $totals["subtotal"]->getValue();
            return $subtotal;
        }

		/**
		 * Get allowed shipping methods
		 *
		 * @return array
		 */
		public function getAllowedMethods()
        {
			return array($this->_code => $this->getConfigData('name'));
		}
		
		/**
		 * Return URL of Jamef's Web Service
		 *
		 * @return string
		 */
		public function getUrlWebservice()
        {
            if($this->getConfigData('integracao')) {
                return $this->getConfigData('url_producao');
            }
            else {
		        return $this->getConfigData('url_homologacao');
            }
		}
		
		/**
		 * Retrieve all visible items from request
		 *
		 * @param Mage_Shipping_Model_Rate_Request $request Mage request
		 * @return array
		 */
		protected function _getItems($request)
		{
			$allItems = $request->getAllItems();
			$items = array();
			
			foreach ( $allItems as $item ) {
				if ( !$item->getParentItemId() ) {
					$items[] = $item;
				}
			}
			
			$items = $this->_loadBundleChildren($items);
			return $items;
		}
		
		/**
		 * Filter visible and bundle children products.
		 *
		 * @param array $items Product Items
		 * @return array
		 */
		protected function _loadBundleChildren($items)
		{
			$visibleAndBundleChildren = array();
			/* @var $item Mage_Sales_Model_Quote_Item */
			foreach ($items as $item) {
				$product = $item->getProduct();
				$isBundle = ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE);
				if ($isBundle) {
					/* @var $child Mage_Sales_Model_Quote_Item */
					foreach ($item->getChildren() as $child) {
						$visibleAndBundleChildren[] = $child;
					}
				} else {
					$visibleAndBundleChildren[] = $item;
				}
			}
			return $visibleAndBundleChildren;
		}

        /**
         * Initial Check
         * @return boolean
         */
        protected function _inicialCheck($request)
        {
            $origCountry = Mage::getStoreConfig('shipping/origin/country_id', $this->getStore());
            $destCountry = $request->getDestCountryId();
            if ($origCountry != 'BR' || $destCountry != 'BR') {
                $this->getHelper()->writeLog($this->__('Out of Area'));
                return false;
            }

            return true;
        }

        /**
         * Throw error to frontend
         *
         * @return Mage_Shipping_Model_Rate_Result_Error
         */
        protected function _throwError()
        {
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($this->_msgErro);
            return $error;
        }
    }  
