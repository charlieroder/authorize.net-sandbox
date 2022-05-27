<!-- method charges a credit card given the amount charged, credit card number
and credit card expiration date. the method returns weather the transation
was accepted or failed. The transaction infromation can be added to a database 
using DBController and the infromation in lines 37 through 54. -->

<?php

require_once "config.php";

$type = "";
$message = "";
if (!empty($_POST["pay_now"])) {
    require_once 'AuthorizeNetPayment.php';
    $authorizeNetPayment = new AuthorizeNetPayment();
    
    $response = $authorizeNetPayment->chargeCreditCard($_POST);
    
    if ($response != null)
    {
        $tresponse = $response->getTransactionResponse();
        
        if (($tresponse != null) && ($tresponse->getResponseCode()=="1"))
        {
            $authCode = $tresponse->getAuthCode();
            $paymentResponse = $tresponse->getMessages()[0]->getDescription();
            $reponseType = "success";
            $message = "This transaction has been approved. <br/> Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() .  " <br/>Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
        }
        else
        {
            $authCode = "";
            $paymentResponse = $tresponse->getErrors()[0]->getErrorText();
            $reponseType = "error";
            $message = "Charge Credit Card ERROR :  Invalid response\n";
        }
        
        /* 
        Database not yet set up...
        
        $transactionId = $tresponse->getTransId();
        $responseCode = $tresponse->getResponseCode();
        $paymentStatus = $authorizeNetPayment->responseText[$tresponse->getResponseCode()];
        require_once "DBController.php";
        $dbController = new DBController();
        
        $param_type = 'sssdss';
        $param_value_array = array(
            $transactionId,
            $authCode,
            $responseCode,
            $_POST["amount"],
            $paymentStatus,
            $paymentResponse
        );
        $query = "INSERT INTO tbl_authorizenet_payment (transaction_id, auth_code, response_code, amount, payment_status, payment_response) values (?, ?, ?, ?, ?, ?)";
        $id = $dbController->insert($query, $param_type, $param_value_array); 
        */
    }
    else
    {
        $reponseType = "error";
        $message= "Charge Credit Card Null response returned";
    }
}
?>