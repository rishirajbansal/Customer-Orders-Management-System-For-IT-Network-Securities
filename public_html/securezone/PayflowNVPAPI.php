<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';

if (!function_exists('pre')) { 
    function pre($arr, $heading = NULL) {
        if (!empty($heading)) {
            echo "<p><b>$heading</b></p>";
        }
        echo "<pre><code>\n" . print_r($arr,true) . "\n</pre></code>";
     }
}

if (!function_exists('script_url')) { 
    function script_url() {
        return (@$_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    }
}

// Set this to "live" for the live server, "pilot" for the test server
global $environment;
if (!isset($environment)) 
    $environment = Config::$environment;

function parse_payflow_string($str) {
    $workstr = $str;
    $out = array();
    
    while(strlen($workstr) > 0) {
        $loc = strpos($workstr, '=');
        if($loc === FALSE) {
            // Truncate the rest of the string, it's not valid
            $workstr = "";
            continue;
        }
        
        $substr = substr($workstr, 0, $loc);
        $workstr = substr($workstr, $loc + 1); // "+1" because we need to get rid of the "="
        
        if(preg_match('/^(\w+)\[(\d+)]$/', $substr, $matches)) {
            // This one has a length tag with it.  Read the number of characters
            // specified by $matches[2].
            $count = intval($matches[2]);
            
            $out[$matches[1]] = substr($workstr, 0, $count);
            $workstr = substr($workstr, $count + 1); // "+1" because we need to get rid of the "&"
        } else {
            // Read up to the next "&"
            $count = strpos($workstr, '&');
            if($count === FALSE) { // No more "&"'s, read up to the end of the string
                $out[$substr] = $workstr;
                $workstr = "";
            } else {
                $out[$substr] = substr($workstr, 0, $count);
                $workstr = substr($workstr, $count + 1); // "+1" because we need to get rid of the "&"
            }
        }
    }
    
    return $out;
}

function run_payflow_call($params) {
    global $environment;
    
    $paramList = array();
    foreach($params as $index => $value) {
        $paramList[] = $index . "[" . strlen($value) . "]=" . $value;
    }
    
    $apiStr = implode("&", $paramList);
    
    //Endpoint selection
    if ($environment == "pilot" || $environment == "sandbox")
        $endpoint = "https://pilot-payflowpro.paypal.com/";
    else 
        $endpoint = "https://payflowpro.paypal.com";
    
    $len = strlen($apiStr);
    $headers[] = "Content-Type: text/namevalue";
    $headers[] = "Content-Length: " . $len;
    $headers[] = "Connection: close";
    $headers[] = "X-VPS-CLIENT-TIMEOUT: 45";
    $headers[] = "X-VPS-REQUEST-ID:" . generateGUID();

    // set the host header
    if ($environment == "pilot" || $environment == "sandbox")
        $headers[] = "Host: pilot-payflowpro.paypal.com";
    else
        $headers[] = "Host: payflowpro.paypal.com";

    //setting the curl parameters.
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL,$endpoint);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);

    //turning off the server and peer verification(TrustManager Concept).
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_TIMEOUT, 90); 		// times out after 90 secs
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $apiStr);
    
    $result = curl_exec($curl);
    
    $out = array();
    
    if ($result === FALSE) {
        if (curl_errno($curl)) {
            $curlErr = array(
                    'curl_error_no' => curl_errno($curl),
                    'curl_error_msg' => curl_error($curl)
                    );
        }
        $out['curl'] = $curlErr;
    }
    else {
        $out['paypal'] = parse_payflow_string($result);
    }
    
    curl_close($curl);
    
    return $out;
}

function generateCharacter () {
    
    $possible = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    return $char;
}

function generateGUID () {
    $GUID = generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
    $GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
    $GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
    $GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
    $GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter();
    return $GUID;
}

function guid(){ 
    //hash out a timestamp and some chars to get a 25 char token to pass in 
    $str = date('l jS \of F Y h:i:s A'); 
    $str = trim($str); 
    $str = md5($str);
    return $str; 

}

?>
