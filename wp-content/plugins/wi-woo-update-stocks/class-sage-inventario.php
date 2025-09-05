<?php 
/**
 * 
 */
class SageInventario
{
	public $ENDPOINT="https://sage.fexpro.com/sage/tempService.wso?WSDL";
	public $SSCIA = "02";
	function curl_soap($url,$xml,&$timeout=false,&$soap_error=null,&$response_out=""){
	

		$headers = array(
			"Content-type: text/xml;charset=\"utf-8\"",
			"Accept: text/xml",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"Content-length: ".strlen($xml),
			'Authorization: Basic '. base64_encode($this->api_user.":".$this->api_pass),
		);

	    // PHP cURL  for https connection with auth
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	    // converting
		$response = curl_exec($ch); 

		try{
			if (empty($response)) {
				if(curl_errno($ch)=="28"){
					$timeout = true;
					return false;
				}
				throw new SoapFault('CURL error: '.curl_error($ch),curl_errno($ch));
			}

			curl_close($ch);
		
			return $response;
			
			

		}catch(SoapFault $e){
			$soap_error=$e;
			return false;
		}
	}

	function curl_soap_fake(){


			$xml = file_get_contents(dirname(__FILE__)."\sage.xml");
			return $xml;
			
	}
	function inventario_get(&$xml_str=""){

		$xml = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
				  <soap:Body>
				    <Inventario xmlns="http://tempuri.org/">
				      <ssCia>'.$this->SSCIA.'</ssCia>
				    </Inventario>
				  </soap:Body>
				</soap:Envelope>';

		$is_timeout = false;
		$soap_error = null;
		//echo "<textarea>".$xml."</textarea>";
		$xml = $this->curl_soap($this->ENDPOINT,$xml,$is_timeout,$soap_error);
		//$xml = $this->curl_soap_fake($this->ENDPOINT,$xml,$is_timeout,$soap_error);
		$result=array();
		if($xml!="" && $xml!=false){
			$xml_str=$xml;
			$xml = simplexml_load_string($xml);
			$xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
			$xml->registerXPathNamespace('m', 'http://tempuri.org/');
			$productos =  $xml->xpath('//m:InventarioResponse/m:InventarioResult/Productos/SKU');
			if(is_array($productos) && count($productos)>0){
				foreach($productos as $i => $prod):
					$prod=(array)$prod;
					$sku = $prod[0];
					$stock = $prod["@attributes"]["BtsStock2"];//$prod["@attributes"]["BtsStock"];
					$stock_china = $prod["@attributes"]["BtsStock5"]; 
					$stock_futuro = $prod["@attributes"]["BtsFuturo"];
					$delivery_date = $prod["@attributes"]["FechaLlegada"];
					if($delivery_date!=""){
						$delivery_date = implode("/",array_reverse(explode("/",$delivery_date)));
					}
					$result[]=array(
						"sku" =>$sku,
						"stock" => $stock,
						"stock_china" => $stock_china,
						"stock_futuro" => $stock_futuro,
						"delivery_date" =>$delivery_date
					);
					
					
				endforeach;
				
			}
		}
		return $result;

	
	}
}