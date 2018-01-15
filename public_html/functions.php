<?php
function show_errors($action){

    $error = false;

    if(!empty($action['result'])){
        $error = "<ul class=\"style999 $action[result]\">"."\n";
        
        if(is_array($action['text'])){
            foreach($action['text'] as $text){
                $error .= "<li><p>$text</p></li>"."\n";
             }	
         }
         else{
             $error .= "<li><p>$action[text]</p></li>";
         }
         
         $error .= "</ul>"."\n";
    }

    return $error;

}
?>
<style type="text/css">
<!--
.style999 {
font-family: Arial, Helvetica, sans-serif; 
font-size: 18px; 
margin-right: 20px; 
margin-left: 20px; color: #FFFFFF;
}
-->
</style>