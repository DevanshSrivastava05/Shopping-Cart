<?php
// Include the autoloader of the Authorize.Net PHP SDK.
require 'vendor/autoload.php';


use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetPayment
{

    private $APILoginId;
    private $APIKey;
    private $APIENV;
    private $refId;
    private $merchantAuthentication;
    public $responseText;

    public function __construct()
    {
        // Include configuration file        
        require_once "config_authorize.php";
        // getting APIkey details from the configuration file
        $this->APILoginId = LOGIN_ID;
        $this->APIKey = TRANSACTION_KEY;
        $this->APIENV = ENV;
        // Set the transaction's reference ID 
        $this->refId = 'ref' . time();
        $this->merchantAuthentication = $this->setMerchantAuthentication();
        $this->responseText = array("1" => "Approved", "2" => "Declined", "3" => "Error", "4" => "Held for Review");
    }

    public function setMerchantAuthentication()
    {
        // Create a merchantAuthenticationType object with authentication details 
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->APILoginId);
        $merchantAuthentication->setTransactionKey($this->APIKey);

        return $merchantAuthentication;
    }

    public function setCreditCard($cardDetails)
    {
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber(preg_replace('/\s+/', '', $cardDetails["card_number"]));
        $creditCard->setExpirationDate($cardDetails["card_exp_year"] . "-" . $cardDetails["card_exp_month"]);
        $creditCard->setCardCode($cardDetails["card_cvc"]);
        $paymentType = new AnetAPI\PaymentType();
        $paymentType->setCreditCard($creditCard);

        return $paymentType;
    }

    public function setOrder($itemName)
    {
        // Create order information 
        $order = new AnetAPI\OrderType();
        $order->setDescription($itemName);

        return $order;
    }

    public function setCustomer($email)
    {
        // Set the customer's identifying information 
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setEmail($email);

        return $customerData;
    }

    public function setTransactionRequestType($paymentType, $amount, $order, $customerData)
    {
        // Add the payment data to a paymentType object 
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentType);
        $transactionRequestType->setCustomer($customerData);

        return $transactionRequestType;
    }

    public function chargeCreditCard($customerDetails, $cardDetails, $totalAmount, $itemName, $sandbox)
    {
        $paymentType = $this->setCreditCard($cardDetails);
        $order = $this->setOrder($itemName);
        $customerData = $this->setCustomer($customerDetails['email']);
        $transactionRequestType = $this->setTransactionRequestType($paymentType, $totalAmount, $order, $customerData);

        // Create a transaction 
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setRefId($this->refId);
        $request->setTransactionRequest($transactionRequestType);

        // Use single backslash before net
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        return $response;
    }
}
