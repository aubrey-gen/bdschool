<?php
	/*
	*********************************************************************************
	* Title: class_gen_util.php
	* Descriptions: Class with frequently used functions
	*********************************************************************************
	*/
        
    final class gen_util {        

        static function is_ajax(){
            return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        }

        static function is_valid_date($date, $format='d/m/Y G:i'){
            try{
                $f = DateTime::createFromFormat($format, $date);
                $valid = DateTime::getLastErrors();
                return ($valid['warning_count']==0 and $valid['error_count']==0 and $f !== false);
            } catch (Exception $e) {
                return FALSE;
            }		
        }
    }	
?>