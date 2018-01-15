<?php

/**
 * Description of Constants
 *
 * @author Rishi
 */
class Constants {
    
    const ORDER_PAYMENT_STATUS_PENDING = 0;
    const ORDER_PAYMENT_STATUS_CANCELLED = 1;
    const ORDER_PAYMENT_STATUS_PAID_CANCELLED = 2;
    const ORDER_PAYMENT_STATUS_PAID = 4;
    const ORDER_PAYMENT_STATUS_PAID_DELETED = 5;
    
    const ORDER_PAYMENT_STATUS_PENDING_STRING = 'PENDING';
    const ORDER_PAYMENT_STATUS_CANCELLED_STRING = 'CANCELLED';
    const ORDER_PAYMENT_STATUS_PAID_CANCELLED_STRING = 'PAID AND CANCELLED';
    const ORDER_PAYMENT_STATUS_PAID_STRING = 'PAID';
    
    const ORDER_STATUS_NEW = "0";
    
    const ORDER_STATUS_PLACED = "1";
    const ORDER_STATUS_APPROVING = "2";
    const ORDER_STATUS_PROCESSING = "3";
    const ORDER_STATUS_READY_20DAYS = "4";
    const ORDER_STATUS_READY_10DAYS = "5";
    const ORDER_STATUS_READY_5DAYS = "6";
    const ORDER_STATUS_READY_1DAY = "7";
    
    const ORDER_STATUS_PROMOTION_B_ACCEPTED_PAYMENT_PENDING = "1B";
    const ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_20DAYS = "4A";
    const ORDER_STATUS_PROMOTION_B_ACCEPTED_PAID = "4B";
    const ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS = "5B";
    const ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS = "6B";
    const ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY = "7B";
    
}

?>