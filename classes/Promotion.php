<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';

/**
 * Description of Promotion
 *
 * @author Rishi
 */
class Promotion {
    
    private $promotionsA;
    private $promotionsB;
    
    private $activatedPromotionA;
    private $activatedPromotionB;
    private $codePromoA;
    private $codePromoB;
    private $validfrompromoA;
    private $validfrompromoB;
    private $validtillpromoA;
    private $validtillpromoB;
    private $fineprintpromoA;
    private $fineprintpromoB;
    
    private $connectionManager;
    private $queryManager;
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
    }
    
    public function fetchPromotionAMasterDetail(){
        $flag;
        
        $sql = "SELECT * FROM promotion_a_master";
        $result = $this->queryManager->query($sql);
        
        if ($result){
           $row = $this->queryManager->fetchSingleRow($result);
           
           if ($row){
               $this->setActivatedPromotionA($row['activatedpromoA']);
               $this->setCodePromoA($row['codepromoA']);
               $this->setValidfrompromoA($row['validfrompromoA']);
               $this->setValidtillpromoA($row['validtillpromoA']);
               $this->setFineprintpromoA($row['fineprintpromoA']);
               
               $flag = TRUE;
           }
           else{
               $flag = FALSE;
           }
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function fetchPromotionBMasterDetail(){
        $flag;
        
        $sql = "SELECT * FROM promotion_b_master";
        $result = $this->queryManager->query($sql);
        
        if ($result){
           $row = $this->queryManager->fetchSingleRow($result);
           
           if ($row){
               $this->setActivatedPromotionB($row['activatedpromoB']);
               $this->setCodePromoB($row['codepromoB']);
               $this->setValidfrompromoB($row['validfrompromoB']);
               $this->setValidtillpromoB($row['validtillpromoB']);
               $this->setFineprintpromoB($row['fineprintpromoB']);
               
               $flag = TRUE;
           }
           else{
               $flag = FALSE;
           }
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function fetchPromotionADetail(){
        $flag;
        
        $sql = "SELECT * FROM promotion_a";
        $result = $this->queryManager->query($sql);
        
        if ($result){
           $promotionsA = array();
           $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $record = array(
                            'unitrangefrom' => $row['unitrangefrom'],
                            'unitrangetill' => $row['unitrangeto'],
                            'discount' => $row['discount'],
                            'activated' => $row['activated'],
                           );
                $promotionsA[$ctr] = $record;
                $ctr+=1;
            }
            $this->setPromotionsA($promotionsA);
            
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function fetchPromotionBDetail(){
        $flag;
        
        $sql = "SELECT * FROM promotion_b";
        $result = $this->queryManager->query($sql);
        
        if ($result){
           $promotionsB = array();
            $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $record = array(
                            'unitrangefrom' => $row['unitrangefrom'],
                            'unitrangetill' => $row['unitrangeto'],
                            'demofile' => $row['demofile'],
                            'activated' => $row['activated'],
                           );
                $promotionsB[$ctr] = $record;
                $ctr+=1;
            }
            $this->setPromotionsB($promotionsB);
            
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function savePromotionAMaster() {
        $flag;
        
        $sql = "DELETE FROM promotion_a_master";
        $result = $this->queryManager->query($sql);
         
        $sql = "INSERT INTO promotion_a_master(activatedpromoA, codepromoA, validfrompromoA, validtillpromoA, fineprintpromoA, createdon)  
            VALUES('$this->activatedPromotionA', '$this->codePromoA', '$this->validfrompromoA', '$this->validtillpromoA', '$this->fineprintpromoA',  NOW())";
         
        $result = $this->queryManager->queryInsertAndGetId($sql);
            
        if ($result){
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function savePromotionBMaster() {
        $flag;
        
        $sql = "DELETE FROM promotion_b_master";
        $result = $this->queryManager->query($sql);
         
        $sql = "INSERT INTO promotion_b_master(activatedpromoB, codepromoB, validfrompromoB, validtillpromoB, fineprintpromoB, createdon)  
            VALUES('$this->activatedPromotionB', '$this->codePromoB', '$this->validfrompromoB', '$this->validtillpromoB', '$this->fineprintpromoB',  NOW())";
         
        $result = $this->queryManager->queryInsertAndGetId($sql);
            
        if ($result){
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function savePromotionA() {
        
        $sql = "DELETE FROM promotion_a";
        $result = $this->queryManager->query($sql);
        
        foreach ($this->getPromotionsA() as $record) {
            $unitrangefrom = $record['unitrangefrom'];
            $unitrangetill = $record['unitrangetill'];
            $discount = $record['discount'];
            $activated = $record['activated'];
            
            $sql = "INSERT INTO promotion_a (unitrangefrom, unitrangeto, discount, activated, createdon) VALUES ($unitrangefrom, $unitrangetill, $discount, $activated, NOW())";
            $result = $this->queryManager->queryInsertAndGetId($sql);
        }
        
    }
    
    public function savePromotionB() {
        
        $sql = "DELETE FROM promotion_b";
        $result = $this->queryManager->query($sql);
        
        foreach ($this->getPromotionsB() as $record) {
            $unitrangefrom = $record['unitrangefrom'];
            $unitrangetill = $record['unitrangetill'];
            $demofile = $record['demofile'];
            $activated = $record['activated'];
            
            $sql = "INSERT INTO promotion_b (unitrangefrom, unitrangeto, demofile, activated, createdon) VALUES ('$unitrangefrom', '$unitrangetill', '$demofile', '$activated', NOW())";
            $result = $this->queryManager->queryInsertAndGetId($sql);
        }
    }
    
    public function isPromotionApplicable($noofunits){
        $promoDetail = NULL;
        $promoADetail = NULL;
        $promoBDetail = NULL;
        
        $currentdate = date('Y-M-d');
        $currentdate = strtotime($currentdate);
        
        $resultA = $this->fetchPromotionAMasterDetail();
        if ($resultA){
            $validtillpromoA = $this->getValidtillpromoA();
            $validtillpromoA = strtotime($validtillpromoA);
            
            if ($validtillpromoA > $currentdate){
                
                $isActivated = $this->getActivatedPromotionA();
                
                if ($isActivated == 1){
                    $resultA1 = $this->fetchPromotionADetail();
                    if ($resultA1){
                        $promotionsA = $this->getPromotionsA();
                        
                        foreach ($promotionsA as $record) {
                            $activated = $record['activated'];
                            if ($activated == 1){
                                $unitrangefrom = $record['unitrangefrom'];
                                $unitrangetill = $record['unitrangetill'];

                                if ($noofunits >= $unitrangefrom && $noofunits <= $unitrangetill ){
                                    $promoADetail = array(
                                                    'promocode'         =>  $this->getCodePromoA(),
                                                    'promotype'         =>  "A",
                                                    'validfrom'         =>  $this->getValidfrompromoA(),
                                                    'validtill'         =>  $this->getValidtillpromoA(),
                                                    'unitrangefrom'     =>  $unitrangefrom,
                                                    'unitrangetill'     =>  $unitrangetill,
                                                    'discount_or_demo'  =>  $record['discount'],
                                                    'fineprint'         =>  $this->getFineprintpromoA()
                                                    );

                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
               
                
        $resultB = $this->fetchPromotionBMasterDetail();
        if ($resultB){
            $validtillpromoB = $this->getValidtillpromoB();
            $validtillpromoB = strtotime($validtillpromoB);
            
            if ($validtillpromoB > $currentdate){
                
                $isActivated = $this->getActivatedPromotionB();
                
                if ($isActivated == 1){
                    $resultB1 = $this->fetchPromotionBDetail();
                     if ($resultB1){
                        $promotionsB = $this->getPromotionsB();
                        
                        foreach ($promotionsB as $record) {
                            $activated = $record['activated'];
                            if ($activated == 1){
                                $unitrangefrom = $record['unitrangefrom'];
                                $unitrangetill = $record['unitrangetill'];

                                if ($noofunits >= $unitrangefrom && $noofunits <= $unitrangetill ){
                                    $promoBDetail = array(
                                                    'promocode'         =>  $this->getCodePromoB(),
                                                    'promotype'         =>  "B",
                                                    'validfrom'         =>  $this->getValidfrompromoB(),
                                                    'validtill'         =>  $this->getValidtillpromoB(),
                                                    'unitrangefrom'     =>  $unitrangefrom,
                                                    'unitrangetill'     =>  $unitrangetill,
                                                    'discount_or_demo'  =>  $record['demofile'],
                                                    'fineprint'         =>  $this->getFineprintpromoB()
                                                    );

                                    break;
                                }
                            }
                        }
                        
                    }
                }
            }
        }
        
        if (!empty($promoADetail) || !empty($promoBDetail)){
            $promoDetail = array(
                            'promoADetail' =>  $promoADetail,
                            'promoBDetail' =>  $promoBDetail
                            );
        }
        
        return $promoDetail;
    }
    
    function __destruct() {
        $this->connectionManager->returnConnection();
    }
    
    public function getPromotionsA() {
        return $this->promotionsA;
    }

    public function setPromotionsA($promotionsA) {
        $this->promotionsA = $promotionsA;
    }

    public function getPromotionsB() {
        return $this->promotionsB;
    }

    public function setPromotionsB($promotionsB) {
        $this->promotionsB = $promotionsB;
    }

    public function getActivatedPromotionA() {
        return $this->activatedPromotionA;
    }

    public function setActivatedPromotionA($activatedPromotionA) {
        $this->activatedPromotionA = $activatedPromotionA;
    }

    public function getActivatedPromotionB() {
        return $this->activatedPromotionB;
    }

    public function setActivatedPromotionB($activatedPromotionB) {
        $this->activatedPromotionB = $activatedPromotionB;
    }

    public function getCodePromoA() {
        return $this->codePromoA;
    }

    public function setCodePromoA($codePromoA) {
        $this->codePromoA = $codePromoA;
    }

    public function getCodePromoB() {
        return $this->codePromoB;
    }

    public function setCodePromoB($codePromoB) {
        $this->codePromoB = $codePromoB;
    }

    public function getValidfrompromoA() {
        return $this->validfrompromoA;
    }

    public function setValidfrompromoA($validfrompromoA) {
        $this->validfrompromoA = $validfrompromoA;
    }

    public function getValidfrompromoB() {
        return $this->validfrompromoB;
    }

    public function setValidfrompromoB($validfrompromoB) {
        $this->validfrompromoB = $validfrompromoB;
    }

    public function getValidtillpromoA() {
        return $this->validtillpromoA;
    }

    public function setValidtillpromoA($validtillpromoA) {
        $this->validtillpromoA = $validtillpromoA;
    }

    public function getValidtillpromoB() {
        return $this->validtillpromoB;
    }

    public function setValidtillpromoB($validtillpromoB) {
        $this->validtillpromoB = $validtillpromoB;
    }

    public function getFineprintpromoA() {
        return $this->fineprintpromoA;
    }

    public function setFineprintpromoA($fineprintpromoA) {
        $this->fineprintpromoA = $fineprintpromoA;
    }

    public function getFineprintpromoB() {
        return $this->fineprintpromoB;
    }

    public function setFineprintpromoB($fineprintpromoB) {
        $this->fineprintpromoB = $fineprintpromoB;
    }

    
    public function getConnectionManager() {
        return $this->connectionManager;
    }

    public function setConnectionManager($connectionManager) {
        $this->connectionManager = $connectionManager;
    }

    public function getQueryManager() {
        return $this->queryManager;
    }

    public function setQueryManager($queryManager) {
        $this->queryManager = $queryManager;
    }


}

?>
