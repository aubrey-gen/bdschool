<?php
	/*
	*********************************************************************************
	* Title: db_con.php
    * Descriptions: Connect to the database of your choice
	*********************************************************************************
	*/
    
    interface DBUserDetailsInterface {
        public function set_login_detials($user, $password);
        public function get_username();
        public function get_password();
    }

    class DBuser implements DBUserDetailsInterface {
        private $DBusername;
        private $DBpassword;

        public function set_login_detials ($user, $password){
            $this->DBusername = $user;
            $this->DBpassword = $password;
        }

        public function get_username(){
            return $this->DBusername;
        }
        public function get_password(){
            return $this->DBpassword;
        }
    }

    interface DBConnectionInterface {
        public function connect ();
        public function disconnect ();
        public function getDBlink ();
    }

    class MySQLConnection implements DBConnectionInterface {
        private $C_SHOST = 'yourhost';			 	
        private $C_SDBNAME = 'yourdbname';
        private $sUsername = '';
        private $sPassword = '';
        private $dbUserDetails;	
        private $oLink;

        public function __construct(DBUserDetailsInterface $dbUserDetails) {
           $this->dbUserDetails = $dbUserDetails;            
        }

        public function connect() {
            /*  Database connection using PDO   */	
            $this->sUsername = $this->dbUserDetails->get_username();
            $this->sPassword = $this->dbUserDetails->get_password();

            $this->oLink = new PDO("mysql:host=$this->C_SHOST;dbname=$this->C_SDBNAME", $this->sUsername, $this->sPassword); 
        }

        public function disconnect() {
            unset($this->oLink);
        }
        
        public function getDBlink (){
            return $this->oLink;
        }
    }
    //Db user setup
    $DBuserInfo = new DBuser();
    $DBuserInfo->set_login_detials('yourusername', 'yourpassword');
    //Initialize MySQL connection
	$dbmysql = new MySQLConnection($DBuserInfo);	    
?>