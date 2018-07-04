<?php

	/** API de Informações de Entrega - Exemplo de requisição em PHP **/

	//Capturando informações para requisição

	$Reference = '100000048'; //Número do Pedido (obrigatório*)
	$EventDatetime = ''; //Data e hora que o produto foi encaminhado
	$EventStatus = '' ; //Status de envio do pedido
	$ShippingNumber = ''; //Número da remessa
	$InvoiceKey = ''; //Chave da NF-e da mercadoria
	$InvoiceValue = 0; //Valor da NF-e da mercadoria
	$CarrierCompany = 'Correios'; //Nome da transportadora (obrigatório*)
	$TrackingCode = 'ABCDE12345BR'; //Código de rastreio do pedido (obrigatório*)
	$AdditionalInfo = 'Problemas de logistíca poderão atrasar a entrega'; //Informações adicionais

	/*Montando o objeto de requisição */
	
	$transmitObject = array(
	'Reference' => $Reference, 
	'EventDatetime' => $EventDatetime,
	'EventStatus' => $EventStatus,
	'ShippingNumber' => $ShippingNumber,
	'InvoiceKey' => $InvoiceKey,
	'InvoiceValue' => $InvoiceValue,
	'CarrierCompany' => $CarrierCompany,
	'TrackingCode' => $TrackingCode,
	'AdditionalInfo' => $AdditionalInfo
	);

	/*Montando o objeto JSON a partir do objeto de requisição criado($transmitObject)*/
	
	error_reporting( E_ALL );
	ini_set('display_errors', true);
	$jsonObject = json_encode($transmitObject);
	echo json_last_error();

	//Definindo a URL de requisição para o ambiente de teste	
	//$url = "https://api-rest.koin.com.br/Transaction/delivery";
	$url = "http://api-rest.qa.koin.in/Transaction/delivery";

	//Chaves de autenticação - Testes
	$consumerKey = "1BFCF567A63E4B6FB38F6A22FFA21FFE"; 
	$secretKey = "50856FDA556747A7860C3295C25FEA26";

	//convertendo o formato do timezone para UTC 
	date_default_timezone_set("UTC");
	
	//Obtendo a hora do servidor
	$time = time();
    
	//Criando o hash de autenticação
	$binaryHash = hash_hmac('sha512', $url.$time, $secretKey, true);
	
	//Convertendo para Base64
	$hash = base64_encode($binaryHash);
	
	//Enviando a requisição
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonObject);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json; 
	charset=utf-8", "Content-Length:".strlen($jsonObject), 
	"Authorization: {$consumerKey},{$hash},{$time}"));

	//Recebendo resposta
	try {
		$response = curl_exec($ch);
		curl_close ($ch);
		//echo $response;
		var_dump($response);
	    } 
	catch (Exception $e) {echo "Exceção: ",  $e->getMessage(), "\n";}
 	
	print $jsonObject;
	
?>