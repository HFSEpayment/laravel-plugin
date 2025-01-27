<?php
namespace HFSEpayment;
use Exception;

class Epayment
{
    public function gateway($payment_obj)
    {
        try {
            $this->validateArguments($payment_obj);
            $this->setDefaultValues($payment_obj);
        } catch (Exception $e) {
            // Handling the exception
            echo 'Error: ' . $e->getMessage();
            return;
        }

        $test_url = "https://testoauth.homebank.kz/epay2/oauth2/token";
        $prod_url = "https://epay-oauth.homebank.kz/oauth2/token";
	    $test_page = "https://test-epay.homebank.kz/payform/payment-api.js";
  	    $prod_page = "https://epay.homebank.kz/payform/payment-api.js";
        $token_api_url = "";

        if ($payment_obj['env'] === "test") {
            $token_api_url = $test_url;
            $pay_page = $test_page;
        } else {
            $token_api_url = $prod_url;
	        $pay_page = $prod_page;
        }
        unset($payment_obj['env']);

        $fields = [
            'grant_type'      => 'client_credentials',
            'scope'           => 'payment usermanagement',
            'client_id'       => $payment_obj['client_id'],
            'client_secret'   => $payment_obj['client_secret'],
            'secret_hash'     => $payment_obj['secret_hash'],
            'invoiceID'       => $payment_obj['invoiceId'],
            'amount'          => $payment_obj['amount'],
            'currency'        => $payment_obj['currency'],
            'terminal'        => $payment_obj['terminal'],
            'postLink'        => $payment_obj['postLink'],
            'failurePostLink' => $payment_obj['failurePostLink']
        ];
        unset($payment_obj['client_id'], $payment_obj['client_secret'], $payment_obj['secret_hash']);

        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($ch);
        $json_result = json_decode($result, true);

        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:
                    $hbp_auth = (object) $json_result;

                    $hbp_payment_object = (object) array_merge($payment_obj, ["auth" => $hbp_auth]);

                    $html = '
                    <script src="'.$pay_page.'"></script>
                    <script>
                        halyk.pay('.json_encode($hbp_payment_object).');
                    </script>';
                    break;
                default:
                    $html = 'Неожиданный код HTTP: '.$http_code."\n";
            }
        }

        echo $html;
    }

    private function validateArguments(&$args) {
        // Define the required properties and their default values
        $requiredProperties = [
            'env',
            'client_id',
            'client_secret',
            'secret_hash',
            'terminal',
            'invoiceId',
            'amount',
            'backLink',
            'failureBackLink',
            'postLink',
            'failurePostLink'
        ]; // These properties must exist in the array

        // Check for missing required properties
        foreach ($requiredProperties as $prop) {
            if (!array_key_exists($prop, $args)) {
                // If required property is missing, return an error or false
                // return "Error: Missing required property '$prop'.";
                throw new Exception("Отсутствует обязательное свойство '$prop'.");
            }
        }
    }

    private function setDefaultValues(&$args) {
        $defaultProperties = [
            'currency' => 'KZT',
            'language' => 'rus',
            'description' => '',
            'accountId' => '',
            'telephone' => '',
            'email' => ''
        ];
        // Set default values for minor properties that are not set
        foreach ($defaultProperties as $key => $defaultValue) {
            if (!array_key_exists($key, $args)) {
                $args[$key] = $defaultValue;
            }
        }
    }
}
?>
