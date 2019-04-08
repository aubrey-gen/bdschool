<?php
/**
 * System functions
 *
 * This file contains a list of all the functions used by the application, handling user login validation.
 * @package Main
 * @subpackage System
 * @author Abrie Lintvelt <abrie@mediaps.co.za>
 * @copyright Copyright (c) 2012, Media Positioning Solutions
 */
  /*
  The function list is as follows:
  //////////////////////////////////////////////////Admin Rights related functions ///////////////////////////////////////
  checklogin() - Used to determine if a user is authenticated
  checkRights() - Used to ensure the user has the rights to view the page.
  getRights() - Returns a bit encoded value for the user rights
  generateAdminMenu() - Name should be self explanitory

  ///////////////////////////////////////// File Upload Related Functions /////////////////////////////////////////
  randomfilename($key=,$iSeed=) - Should be self descriptive in the name
  uploadFile($arFile, $uploadDir) - upload file to $uploadDir. Returns the uploaded file's name.
  resizeImage($sFileName, $sSourceDir, $sTargetDir, $iWidth) - Resizes $sFileName from $sSourceDir to $iWidth and save the resulting image in $TargetDir
  checkPath($sPath, $bCreate=true) - Checks if the $sPath exists, and returns the realpath to it if it does.  If it does not exist and $bCreated=true then create the path and return the realpath, otherwise return false.
  uploadImage($arFile, $uploadDir, $iWidth) - Combination of uploadfile and resizeImage

  ///////////////////////////////////////// Password Related Functions ////////////////////////////////////////////
  getGUID() - Used to generate a GUID
  encPass(sPassword, sSalt, sPepper) - used to encrypt the specified password using both the salt and pepper to add entropy.
  generateCode($characters=8) - generate a code that is $characters long

  ///////////////////////////////////////////////Usage Tracking related functions ///////////////////////////////////////
  logHits() - Copy of logHits from RSA site.  Might be adjusted slightly to fit the new project. Logs views/hits for pages

  /////////////////////////////////////////////// String Functions //////////////////////////////////////////////////////
  monthName($iMonth)  - Returns the month's name
  LeadingZero($sInput, $sLength=2) - Alias of lz
  lz ($sInput, $sLength=2) - append leading zeroes to $sInput untill it's $sLength long
  left ($sString, $iLength=1) - Return the $iLength characters from the left of $sString (eg left('abcdef',3) return abc
  right ($sString, $iLength=1) - Return the $iLength characters from the right of $sString (eg right('abcdef',3) return def
  */

  //////////////////////////////////////////////////Admin Rights related functions ///////////////////////////////////////
  /**
   * Used to determine if a user is authenticated
   *
   * Use session values as stored during the login procedure to determine if a user's session is still current. If the user's
   * session is still current, update the $_SESSION["LAST_ACTIVITY"] var to the current timestamp.
   * Also check if the sessionid is more than 30 mintues old.  If so:  Create a new session ID
   * If the user's details are not current, destroy the session and return a false value.
   *
   * @see login.php
   * @return bool
   */
  function checkLogin($bKillSession=true) {
    //this function checks if the user's login is a) current and b) ensures that the session_id gets rotated after a certain amount of time
    $sessionTimeout=30 * 60; //(30 * 60 seconds)
    $bReturn=false;

    if (isset($_SESSION["USER_ID"]) && isset($_SESSION["LAST_ACTIVITY"]) && isset($_SESSION["CREATED"])) {
      if (time() - $_SESSION["LAST_ACTIVITY"] < $sessionTimeout) {
        //check for timeouts
        $_SESSION["LAST_ACTIVITY"]=time();
        if (time() - $_SESSION["CREATED"] >= 1800) {
          //get a new session id.
          session_regenerate_id(true);
          $_SESSION["CREATED"]=time();
        }
        $bReturn=true;
      } else {
        //Timed out.  kill the session and send the user packing.
        $bReturn=false;
        if ($bKillSession) {
          session_destroy();
          session_unset();
        }
      }
      //last activity value found and created found., next step:
    } else { //the user is not even logged in!  not authenticated.
        $bReturn=false;
        if ($bKillSession) {
          session_destroy();
          session_unset();
        }
    }
    return $bReturn;
  }

  /**
   * Used to determine if a user's access rights
   *
   * Check if the user has viewing rights on the specified module.  Viewing rights is the minimum rights required to access
   * a module.  If the user does not have the rights, return a false.
   *
   * @return bool
   */
  function checkRights() {
    global $module,$con;
    if (checkLogin()) {
      $rightQry =   "";
      $rightQry .=  "select bView from \n";
      $rightQry .=  "tbl_modules m inner join tbl_group_module_link gml \n";
      $rightQry .=  "on m.ipkModuleID=gml.ifkModuleID \n";
      $rightQry .=  "inner join tbl_user_group_link ugl on ugl.ifkGroupID=gml.ifkGroupID \n";
      $rightQry .=  "where m.sBackFile=:sBackFile and ugl.ifkUserID=:ifkUserID";
      $stmt=$con->prepare($rightQry);
      $stmt->bindparam(":sBackFile",$module,PDO::PARAM_STR,50);
      $stmt->bindparam(":ifkUserID",$_SESSION["USER_ID"],PDO::PARAM_INT);
      $stmt->execute();
      $bView=$stmt->fetchColumn();
      if ($bView===true) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /**
   * Returns a bit encoded rights value
   *
   * Based on checkRights() this function returns a bit encoded integer value that specifies the user rights.
   * Current rights are:
   * 1=view
   * 2=edit
   * 4=delete
   * 8=add
   *
   * @see checkRights()
   * @return int
   */
  function getRights() {
    //returns a bit encoded rights value.
    //1=view
    //2=edit
    //4=delete
    //8=add
    global $module,$con;
    $iReturn=0;
    if (checklogin()) {
      $rightQry =   "";
      $rightQry .=  "select bView, bEdit, bDelete, bAdd from \n";
      $rightQry .=  "tbl_modules m inner join tbl_group_module_link gml \n";
      $rightQry .=  "on m.ipkModuleID=gml.ifkModuleID \n";
      $rightQry .=  "inner join tbl_user_group_link ugl on ugl.ifkGroupID=gml.ifkGroupID \n";
      $rightQry .=  "where m.sBackFile=:sBackFile and ugl.ifkUserID=:ifkUserID";
      $stmt=$con->prepare($rightQry);
      $stmt->bindparam(":sBackFile",$module,PDO::PARAM_STR,50);
      $stmt->bindparam(":ifkUserID",$_SESSION["USER_ID"],PDO::PARAM_INT);
      if (!$stmt->execute()) {
        //error handler
        var_dump($stmt->errorInfo());
        die();
      }
      if (list($bView, $bEdit, $bDelete, $bAdd)=$stmt->fetch()) {
        if ($bView===true) {
          $iReturn+=1;
        }
        if ($bEdit===true) {
          $iReturn+=2;
        }
        if ($bDelete===true) {
          $iReturn+=4;
        }
        if ($bAdd===true) {
          $iReturn+=8;
        }
      } else {
        $iReturn=0;
      }
    }
    return $iReturn;
  }

  /**
   * Generate the menu based in the user rights.
   *
   *
   *
   * @see checkRights()
   * @return string
   */
  function generateAdminMenu($sType="List") {
    //used to generate the admin menus.
    global $con, $sSelf;
    $sReturn="";
    $ifkInstitutionID=isset($_SESSION["INST_ID"])?$_SESSION["INST_ID"]:"";
    if (isset($_SESSION["USER_ID"])) {
      //get the groups the user is part of and the modules affected by the different groups.
      $menuQuery="";
      $menuQuery.="select m.sName, m.sBackFile, bReqInst \n";
      $menuQuery.="from tbl_modules m \n";
      $menuQuery.="inner join tbl_group_module_link gml on m.ipkModuleID=gml.ifkModuleID \n";
      $menuQuery.="inner join tbl_user_group_link ugl on ugl.ifkGroupID=gml.ifkGroupID \n";
      $menuQuery.="where m.bActive=true and gml.bView=true and ugl.ifkUserID=:ifkUserID order by m.ipkModuleID";
      $mnuStmt=$con->prepare($menuQuery);
      $mnuStmt->bindparam(":ifkUserID",$_SESSION["USER_ID"],PDO::PARAM_INT);
      if (!$mnuStmt->execute()) {
        //TODO: ADD ERROR HANDLING HERE
        echo "Oh oh!";
        var_dump($mnuStmt->errorInfo());
        die ("prepared statement Failed");
      }
//      $mnuStmt->store_result();
      require_once ("includes/classes/menu.class.php");
      $oMenu=new menu;
      $oMenu->addItem(new menuItem("/index.php/welcome","Home"));
      $oMenu->addItem(new menuItem("/index.php/mydetails","Change my details"));
      if ($mnuStmt->rowCount()>0) {
        //$mnuStmt->bind_result($sMenuName, $sMenuModule, $bReqInst);
        while (list($sMenuName, $sMenuModule, $bReqInst)=$mnuStmt->fetch()) {
          if ($bReqInst==0 || isset ($_SESSION["INST_ID"])) { //this should technically only show menu items that require an institution if an institution has been selected.
            $sMenuModule=str_replace(".php","",$sMenuModule);
            $oMenu->addItem(new menuItem("/index.php/$sMenuModule",$sMenuName));
          }
        }
      }
      $oMenu->addItem(new menuItem("/index.php/logout","Logout"));
      //disabled until such a time as Dawie and I can run throught this script and get the damned select box inline with the rest.
      $sReturn=$oMenu->exportMenu($sType);
    }
    return $sReturn;
  }

  ///////////////////////////////////////// File Upload Related Functions /////////////////////////////////////////
  /**
   * Generate a random text string to use as a file name
   *
   * Function: randomfilename
   *
   * Purpose: Generate a random text string to use as a file name.
   *
   * Arguments: key(string) (optional), iSeed(numeric) (optional)
   *
   * Method: Generate a raw MD5 of $key and convert it to an array.  Extend the array to a length of 18 bytes
   * and convert the raw data to numerical values. use the last 12 bytes to modify the the first 6 through
   * various bitwise operations.
   * Next, truncate the array to only 6 bytes and convert the numerical values back to their textual
   * representations.  Lastly base64 encode the items and replace any invalid characters to get a string
   * of 8 characters long that we can use for a file name.
   * tbh it's a bit of a crappy hashing system using bitwise operators.  Data cannot be retrieved at all
   * even if you have the seed. DO NOT USE FOR ENCRYPTION/HASHING OF DATA.  There's NO guarantee that the
   * hash will be unique.
   *
   * @return string
   * @param string $key optional
   * @param int iSeed optional
   *
   */
  function randomfilename($key="",$iSeed="")
  {
    if ($iSeed!="") mt_srand($iSeed);
    $input=$input==""?microtime():$input;
    $sName=md5($input,true);
    $sName=str_split($sName);
    for ($x=0;$x<16;$x++)
    {
      $sName[$x]=ord($sName[$x]);
    }
    //extend it to 18 characters
    $sName[]=mt_rand(0,255);
    $sName[]=mt_rand(0,255);
    //now for the tricky bit.  or not.  we run to 6 and do 2 tasks before looping back :)
    //easiest is to just extend the possibilites a bit. should now go to 6 i think.
    //*binary math fail!  should've just seen this as a bit system and read it like that.
    for ($x=0;$x<8;$x++)
    {
      $iRand=mt_rand(1,9);
      switch ($iRand)
      {
      case 1:
        $sName[$x]=$sName[$x]&$sName[$x+6]&$sName[$x+12]; //double and
        break;
      case 2:
        $sName[$x]=$sName[$x]&$sName[$x+6]|$sName[$x+12]; //and or
        break;
      case 3:
        $sName[$x]=$sName[$x]&$sName[$x+6]^$sName[$x+12]; //and xor
        break;
      case 4:
        $sName[$x]=$sName[$x]|$sName[$x+6]|$sName[$x+12]; //double or
        break;
      case 5:
        $sName[$x]=$sName[$x]|$sName[$x+6]^$sName[$x+12]; //or xor
        break;
      case 7:
        $sName[$x]=$sName[$x]^$sName[$x+6]^$sName[$x+12]; //double xor
        break;
      }
    }
    while (count($sName)>6)
    {
      array_pop($sName);
    }
    for ($x=0;$x<6;$x++)
    {
      $sName[$x]=chr($sName[$x]);
    }
    $sName=implode("",$sName);
    //should be back to a string now.
    $sName=base64_encode($sName);
    $sName=str_replace("+","_",$sName);
    $sName=str_replace("=","-",$sName);
    $sName=str_replace("/","~",$sName);
    return $sName;
  }

  /**
   * File uploader handler
   * @return string
   * @param string[] $arFile
   * @param string $uploadDir
   */
  function uploadFile($arFile, $uploadDir)
  {
    $uploadfilename="";
    $sFilename=$arFile["name"];
    $arFileNameParts=explode(".",$sFilename);
    $fileext=$arFileNameParts[1];
    $filename = date("YmdU") . ".$fileext";
    $uploadfile = $uploadDir . $filename;
    $tmpVar="";
    if (file_exists($uploadDir . '/' . $filename))
    {
      $tmpVar = 1;
      while(file_exists($uploadDir . '(' . $tmpVar . ')' . $filename ))
      {
       $tmpVar++;
      }
      $uploadfile= $uploadDir . '(' . $tmpVar . ')' . $filename;
      $filename = '(' . $tmpVar . ')' . $filename;
    }
    if (!move_uploaded_file($arFile['tmp_name'], $uploadfile))
    {
      die ("ERROR - Unable to move uploaded file");
    }
    $uploadfilename=($uploadfilename=="")?$filename:$uploadfilename;
    return ($uploadfilename);
  }

  /**
   * Image resizer function
   *
   * @return void
   * @param string $sFileName
   * @param string $sSourceDir
   * @param string $sTargetDir
   * @param int $iWidth
   */
  function resizeImage($sFileName, $sSourceDir, $sTargetDir, $iWidth)
  {
    $arFileNameParts=explode(".",$sFileName);
    $fileext=$arFileNameParts[1];
    $fn=$sSourceDir.$sFileName;
    if(false !== (list($ws,$hs) = @getimagesize($fn)))
    {
      if(isset($iWidth) && ("" != $iWidth))
      {
        $ratio = ((float)$iWidth) / $ws;
      }
      if(isset($ratio) && $ratio<1)
      {
        $wt = $ws * $ratio;
        $ht = $hs * $ratio;
        $thumb = imagecreatetruecolor($wt,$ht);
        $source=false;
        switch (strToLower($fileext))
        {
          case "png":
            $source = imagecreatefrompng($fn);
            break;
          case "jpg":
          case "jpeg":
            $source = imagecreatefromjpeg($fn);
            break;
          case "gif":
            $source = imagecreatefromgif($fn);
            break;
        }
        if ($source!==false)
        {
          imagecopyresampled($thumb,$source,0,0,0,0,$wt,$ht,$ws,$hs);
          switch (strToLower($fileext))
          {
            case "png":
              imagepng($thumb,$sTargetDir . $sFileName );
              break;
            case "jpg":
            case "jpeg":
              imagejpeg($thumb,$sTargetDir . $sFileName );
              break;
            case "gif":
              imagegif($thumb,$sTargetDir . $sFileName );
              break;
          }
          imagedestroy($thumb);
        }
      }
    }
  }

  /**
   * Check if a path exists and possibly create it if not
   *
   * @param string $sPath
   * @param bool $bCreate default true
   * @param string $jail default empty but will be converted to $_SERVER["DOCUMENT_ROOT"] if empty
   * @return string|bool
   */
  function checkPath($sPath, $bCreate=true, $jail="") {
    //return value depends on $bCreated and if the path exists;
    //if the path exists, it will return the realpath for $sPath
    //this will also be the case if $bCreate is set to true (default)
    //otherwise it will return false.\

    //first determine the document root.  The file is NEVER allowed to go past this.
    $jail=($jail=="")?$_SERVER["DOCUMENT_ROOT"]:$jail;
    $jail.="/";


    $sPath=str_replace("\\","/",$sPath);
    $jail=str_replace("\\","/",$jail);

    $fullPath=dirman($jail,$sPath,$jail);
    //refine $sPath to $fullPath minus $jail
    $sPath=str_replace($jail,"",$fullPath);
    $tempPath=$jail;
    $arPathData=explode("/",$sPath);
    foreach ($arPathData as $sStep) {
      if ($sStep!="." && $sStep!="") {
        if (file_exists("$tempPath/$sStep")) {
          if (!is_dir("$tempPath/$sStep")) {
            return false;
          }
        } else {
          if ($bCreate) {
            mkdir(str_replace("//","/","$tempPath/$sStep"));
          } else {
            return false;
          }
        }
      }
      $tempPath.="$sStep/";
    }
    $tempPath=str_replace("//","/",$tempPath);
    return realpath($tempPath);
  }

  /**
   * Check if a path exists and possibly create it if not
   *
   * @param string $sPath The path of the calling script.  Used only in case of relative $mod
   * @param string $mod the modifications to $path.  Can be relative or absolute.  Respects ../, / and ./
   * @param string $jail default empty but will be converted to $_SERVER["DOCUMENT_ROOT"] if empty
   * @return string
   */
  function dirman($path,$mod,$jail="") {
    //jail is the folder level at which point ..==.
    //technically deal with /, ../ and ./
    // starting / will mean $jail/$mod
    // ../ anywhere means parent directory
    // ./ means do nothing (Technically);

    $jail=($jail=="")?$_SERVER["DOCUMENT_ROOT"]:$jail;

    $path=str_replace("\\","/",$path);
    $mod=str_replace("\\","/",$mod);
    $jail=str_replace("\\","/",$jail);

    if (strpos($path,$jail)===false) {
      //if the path specified is not in the jail then this is kinda pointless.
      return false;
    }
    //first things first.  test for an absolute path.
    if (substr($mod,0,1)=="/" || substr($mod,0,1)=="\\") {
      //absolute path.
      $path=$jail;
      $mod=substr($mod,1);
    }
    $armod=explode("/",$mod);
    $tempPath=$path;
    foreach ($armod as $step) {
      switch ($step) {
      case "..":
        //move one folder up.
        if ($tempPath!==$jail) {
          //Good to go up.
          $tempPath=explode("/",$tempPath);
          array_pop($tempPath);
          $tempPath=implode("/",$tempPath);
        }
        break;
      case ".":
        //stay where we are
        break;
      default:
        //not one of the control structures, so we're good.
        $tempPath=explode("/",$tempPath);
        $tempPath[]=$step;
        $tempPath=implode("/",$tempPath);
      }
    }
    $tempPath=str_replace("//","/",$tempPath);
    return $tempPath;
  }


  /**
   * Combination of file uploader and image resizer.
   *
   * @param string[] $arFile
   * @param string $uploadDir
   * @param int $iWidth default 300
   */
  function uploadImage($arFile, $uploadDir, $iWidth=300, $iHeight=0, $filter="")
  {
    //returns the uploaded file's name
    $uploadDir="$uploadDir/";
    $uploadDir=str_replace("//","/",$uploadDir);
    $uploadfilename="";
    $sFilename=$arFile["name"];
    $arFileNameParts=explode(".",$sFilename);
    $fileext=$arFileNameParts[1];
    $filename = date("YmdU") . ".$fileext";
    $uploadfile = $uploadDir . $filename;
    $tmpVar="";
    if (file_exists($uploadDir . '/' . $filename))
    {
      $tmpVar = 1;
      while(file_exists($uploadDir . '(' . $tmpVar . ')' . $filename ))
      {
       $tmpVar++;
      }
      $uploadfile= $uploadDir . '(' . $tmpVar . ')' . $filename;
      $filename = '(' . $tmpVar . ')' . $filename;
    }
    if (!move_uploaded_file($arFile['tmp_name'], $uploadfile))
    {
      die ("ERROR - Unable to move uploade file");
    }
    $uploadfilename=($uploadfilename=="")?$filename:$uploadfilename;

    $fn = $uploadDir.$uploadfilename;
    if(false !== (list($ws,$hs) = @getimagesize($fn)))
    {
      if(isset($iWidth) && ("" != $iWidth))
      {
        $ratio = ((float)$iWidth) / $ws;
      }
      if ($iHeight>0) {
        //got the height check.  Ratio was already determined by the width.  Test to see if the height will work.  if not, re-adjust
        if ($hs*$ratio>$iHeight) {
          $ratio = ((float)$iHeight) / $hs;
        }
      }
      if(isset($ratio) && $ratio<1)
      {
        $wt = $ws * $ratio;
        $ht = $hs * $ratio;
        $thumb = imagecreatetruecolor($wt,$ht);
        $source=false;
        switch (strToLower($fileext))
        {
          case "png":
            $source = imagecreatefrompng($fn);
            break;
          case "jpg":
          case "jpeg":
            $source = imagecreatefromjpeg($fn);
            break;
          case "gif":
            $source = imagecreatefromgif($fn);
            break;
        }
        if ($source!==false)
        {
          imagecopyresampled($thumb,$source,0,0,0,0,$wt,$ht,$ws,$hs);
          if ($filter!="") {
            imagefilter($source,$filter);
          }
          switch (strToLower($fileext))
          {
            case "png":
              imagepng($thumb,$uploadDir . $uploadfilename );
              break;
            case "jpg":
            case "jpeg":
              imagejpeg($thumb,$uploadDir . $uploadfilename );
              break;
            case "gif":
              imagegif($thumb,$uploadDir . $uploadfilename );
              break;
          }
          imagedestroy($thumb);
        }
      }
    }
  return $uploadfilename;
  }

  ///////////////////////////////////////// Password Related Functions ////////////////////////////////////////////
  /**
   * Generate GUID
   *
   * @return string
   */
  function getGUID() {
    if (function_exists('com_create_guid')) {
      return com_create_guid();
    } else {
      mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
      $charid = strtoupper(md5(uniqid(mt_rand(), true)));
      $hyphen = chr(45);// "-"
      $uuid = chr(123)// "{"
          .substr($charid, 0, 8).$hyphen
          .substr($charid, 8, 4).$hyphen
          .substr($charid,12, 4).$hyphen
          .substr($charid,16, 4).$hyphen
          .substr($charid,20,12)
          .chr(125);// "}"
      return $uuid;
    }
  }

  /**
   * Password encryptor script
   *
   * @param string $sPassword
   * @param string $sSalt
   * @param string $sPepper
   * @return string
   */
  function encPass($sPassword, $sSalt, $sPepper) {
    //this function will "spice" and hash a password
    //The spices needs to "purified" (GUID incoming, strip the {} and the -'s
    $sSalt=preg_replace("/[{}-]/","",$sSalt);
    $sPepper=preg_replace("/[{}-]/","",$sPepper);
    //break up the salt and pepper into 32bit chunks
    $s1=substr($sSalt,0,8);
    $s2=substr($sSalt,8,8);
    $s3=substr($sSalt,16,8);
    $s4=substr($sSalt,24,8);
    $p1=substr($sPepper,0,8);
    $p2=substr($sPepper,8,8);
    $p3=substr($sPepper,16,8);
    $p4=substr($sPepper,24,8);
    //convert the hex values to decimal values
    $s1=hexdec($s1);
    $s2=hexdec($s2);
    $s3=hexdec($s3);
    $s4=hexdec($s4);
    $p1=hexdec($p1);
    $p2=hexdec($p2);
    $p3=hexdec($p3);
    $p4=hexdec($p4);
    //now that we have the decimal values, create an interleave as an added extra step for complexity
    //interleave is just a simple xor of the two. (can easily be changed to something else)
    $i1=$s1^$p1;
    $i2=$s2^$p2;
    $i3=$s3^$p3;
    $i4=$s4^$p4;
    //next we create the "output" spice that we will actually spice the password with.  Once again it can easily be modified to enhance complexity
    $o1=$s1&$p2^$i3;
    $o2=$s2|$p3&$i4;
    $o3=$s3^$p4^$i1;
    $o4=$s4&$p1&$i2;
    //lastly we spice the password with the output value. Once again rather easy to change.
    $sPassword=dechex($o1).dechex($o3).$sPassword.dechex($o2).dechex($o4);
    $sPassHash=hash("sha256",$sPassword);
    return $sPassHash;
  }

  /**
   * Generate code
   *
   * @param int $characters default 8
   * @return string
   */
  function generateCode($characters=8)
  {
    /* list all possible characters, similar looking characters and vowels have been removed */
    $possible = '23456789abcdfghjkmnpqrstvwxyzABCDEFHJKLMNPQRSTUVWXYZ';
    $code = '';
    $i = 0;
    while ($i < $characters) {
      $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
      $i++;
    }
    return $code;
  }
  ///////////////////////////////////////////////Authenticator related functions ////////////////////////////////////////
  /**
   * Base-32 to binary decoder
   *
   * This function converts a base-32 encoded value into it's binary value.
   * @param string $b32
   * @return string
   */
  function base32_decode($b32) {
    $lut = array("A" => 0,       "B" => 1,
                 "C" => 2,       "D" => 3,
                 "E" => 4,       "F" => 5,
                 "G" => 6,       "H" => 7,
                 "I" => 8,       "J" => 9,
                 "K" => 10,      "L" => 11,
                 "M" => 12,      "N" => 13,
                 "O" => 14,      "P" => 15,
                 "Q" => 16,      "R" => 17,
                 "S" => 18,      "T" => 19,
                 "U" => 20,      "V" => 21,
                 "W" => 22,      "X" => 23,
                 "Y" => 24,      "Z" => 25,
                 "2" => 26,      "3" => 27,
                 "4" => 28,      "5" => 29,
                 "6" => 30,      "7" => 31
    );

    $b32    = strtoupper($b32);
    $b32    = str_replace("=","",$b32);
    $l      = strlen($b32);
    $binary = "";

    $arTemp="";
    for ($i = 0; $i < $l; $i++) {
      $arTemp[]=lz(base_convert($lut[$b32[$i]],10,2),5);
    }
    $temp=implode('',$arTemp);
    $arTemp=str_split($temp,8);
    if (base_convert($arTemp[count($arTemp)-1],2,10)==0) {
        //drop it!
        array_pop($arTemp);
    } else {
      //pad it up to 8 with trailing zeros
      $arTemp[count($arTemp)-1]=tz($arTemp[count($arTemp)-1],8);
    }
    for ($x=0;$x<count($arTemp);$x++) {
      $temp=tz($arTemp[$x],8);
      echo $temp."<br />";
      $binary.=chr(base_convert($temp,2,10));
    }
    return $binary;
  }

  /**
   * Base-32 to binary encoder
   *
   * This function encodes a input string (binary safe) into a base-32 equivalent.
   * @param string $sString
   * @return string
   */
  function base32_encode($sString) {
    $return="";
    //init the encoding array
    $lut = array(0 => "A",       1 => "B",
                 2 => "C",       3 => "D",
                 4 => "E",       5 => "F",
                 6 => "G",       7 => "H",
                 8 => "I",       9 => "J",
                 10 => "K",      11 => "L",
                 12 => "M",      13 => "N",
                 14 => "O",      15 => "P",
                 16 => "Q",      17 => "R",
                 18 => "S",      19 => "T",
                 20 => "U",      21 => "V",
                 22 => "W",      23 => "X",
                 24 => "Y",      25 => "Z",
                 26 => "2",      27 => "3",
                 28 => "4",      29 => "5",
                 30 => "6",      31 => "7"
    );
    //next break the message into 5 byte chunks, seeing as 5 bytes = 40 bits = 8x5-bit pieces = 8xBase32 chars
    $arSource=str_split($sString,5);
    foreach ($arSource as $sPiece) {
      $arTemp="";
      $i=0;
      $iNum=0;
      while ($i<strlen($sPiece)) {
        $arTemp[]=lz(base_convert(ord($sPiece[$i]),10,2),8);
        //echo ord($sPiece[$i])."<br />";
        $i++;
      }
      $temp=implode('',$arTemp);
      if ($i==5) {
        //full conversion
        $arTemp=str_split(lz($temp,40),5);
        for($i=0;$i<count($arTemp);$i++) {
          $return.=$lut[base_convert($arTemp[$i],2,10)];
        }
      } else {
        switch (strlen($temp)%40) {
        case 8:
          //ok, extend it to 10 chars.
          $temp=tz($temp,10);
          $pad=str_pad('',6,'=');
          break;
        case 16:
          $temp=tz($temp,20);
          $pad=str_pad('',4,'=');
          break;
        case 24:
          $temp=tz($temp,25);
          $pad=str_pad('',3,'=');
          break;
        case 32:
          $temp=tz($temp,35);
          $pad=str_pad('',1,'=');
          break;
        }
        $arTemp=str_split($temp,5);
        for($i=0;$i<count($arTemp);$i++) {
          $return.=$lut[base_convert($arTemp[$i],2,10)];
        }
        $return.=$pad;
      }
    }
    return $return;
  }

  /**
   * Counter generator for TOTP generation.
   *
   * Returns the number of 30 second periods passed since Unix Epoch.
   * @return int
   */
  function get_timestamp($div=30) {
    return floor(microtime(true)/$div);
  }

  /**
   * OTP Generator
   *
   * Generates an OTP using the secret key and a counter.
   * @param string $sKey The key to use when creating the hash
   * @param number $counter The counter value to hash
   * @return string
   */

  function genOTP($sKey, $counter) {
    $binary_timestamp = pack('N*',0).pack('N*',$counter);
    $binary_key=base32_decode($sKey);
    $hash = hash_hmac('sha1', $binary_timestamp,$binary_key,true);
    $offset = ord($hash[19]) & 0xf;
    $OTP = (
       ((ord($hash[$offset+0]) & 0x7f) << 24 ) |
        ((ord($hash[$offset+1]) & 0xff) << 16 ) |
        ((ord($hash[$offset+2]) & 0xff) << 8 ) |
        (ord($hash[$offset+3]) & 0xff)
       ) % pow(10, 6);
  return $OTP;
  }

  /**
   * Key Generator
   *
   * Generates a cryptographically secure secret key to use for OTP generation.
   * @return string Base-32 encoded code, for use in the QR Code generator and for easy entry into applications.
   * @todo Consider adding more functionality to this function, such as passing user details through, storing key in user details, etc.
   */
  function genKey() {
    $strong=false;
    while (!$strong) {
      $binary_key=openssl_random_pseudo_bytes(10,$strong);
    }
    $sTextKey=base32_encode($binary_key);
    return $sTextKey;
  }

  function genTOTP($sKey) {
    return genOTP($sKey,get_timestamp());
  }

  ///////////////////////////////////////////////Usage Tracking related functions ///////////////////////////////////////
  /**
   * Hit logging script
   *
   * @todo Re-evaluate the use of this script.
   * @param int $ifkCategoryID default 0
   * @param int $ifkInstitutionID default 0
   * @param int $ifkItemID default 0
   * @param int $iType default 0
   * @return void
   */
  function logHits($ifkCategoryID=0, $ifkInstitutionID=0, $ifkItemID=0, $iType=0)
  {
    global $con,$databasename;
    $now=getdate();
    $iMonth=$now["mon"];
    $iYear=$now["year"];
    $QueryString="select iHits from hits where ifkcategoryID=$ifkCategoryID and ifkInstitutionID=$ifkInstitutionID and ifkItemID=$ifkItemID and iType=$iType and iMonth=$iMonth and iYear=$iYear";
    $rs=$con->createResultSet($QueryString,$databasename);
    if ($rs->getError()!="") {die ($rs->getError() . " > " . $QueryString);}
    $iHits=$rs->getColumn();
    $rs=null;
    if ($iHits==null)
    {
      $iHits=1;
      $QueryString="insert into hits (ifkCategoryID, ifkInstitutionID, ifkItemID, iType, iMonth, iYear, iHits) values ($ifkCategoryID, $ifkInstitutionID, $ifkItemID, $iType, $iMonth, $iYear, $iHits)";
      $con->doQuery($QueryString,$databasename);
      if ($con->getError()!="")
      {
        die ($con->getError());
      }
    }
    else
    {
      $iHits++;
      $QueryString="update hits set iHits=$iHits where ifkcategoryID=$ifkCategoryID and ifkInstitutionID=$ifkInstitutionID and ifkItemID=$ifkItemID and iType=$iType and iMonth=$iMonth and iYear=$iYear";
      $con->doQuery($QueryString,$databasename);
      if ($con->getError()!="")
      {
        die ($con->getError());
      }
    }
  }

  /////////////////////////////////////////////// String Functions //////////////////////////////////////////////////////
  /**
   * Easy switch between month number and name
   *
   * @param int @iMonth
   * @return string
   */
  function monthName($iMonth)
  {
    $sMonth=date("F",mktime(0,0,0,$iMonth));
    return $sMonth;
  }

  /**
   * Wrapper for the lz function
   *
   * @param string|int $sInput number (either as string or int) to pad with 0
   * @param int @length default 2
   * @return string
   */
  function LeadingZero($sInput,$length=2) {
    /****************************************************************************************************************
    * Function: LeadingZero
    * Purpose: Alias of lz
    * Arguments: Input(string), length(numeric) default 2
    * Method: call and return value of lz
    ****************************************************************************************************************/
    return lz($sInput,$length);
  }

  /**
   * Add Leading Zero's to the front of a number.  Uses str_pad with STR_PAD_LEFT to achieve this.
   *
   * @param string|int $sInput number (either as string or int) to pad with 0
   * @param int @length default 2
   * @return string
   */
  function lz($sInput,$length=2) {
    /****************************************************************************************************************
    * Function: lz (Leading Zero)
    * Purpose: Add leading zeroes to a string value.
    * Arguments: Input(string), length(numeric) default 2
    * Method: use str_pad to extend it to $length
    ****************************************************************************************************************/
    if ($sInput=="" || $length==0) {
      return $sInput;
    }
    $sInput=(string) $sInput;
    $sInput=str_pad($sInput,$length,"0",STR_PAD_LEFT);
    return $sInput;
  }

  /**
   * Wrapper for the tz function
   *
   * @param string|int $sInput number (either as string or int) to pad with 0
   * @param int @length default 2
   * @return string
   */
  function TrailingZero($sInput,$length=2) {
    /****************************************************************************************************************
    * Function: TrailingZero
    * Purpose: Alias of tz
    * Arguments: Input(string), length(numeric) default 2
    * Method: call and return value of tz
    ****************************************************************************************************************/
    return tz($sInput,$length);
  }

  /**
   * Add trailing zero's to the end of a number.  Uses str_pad with STR_PAD_RIGHT to achieve this.
   *
   * @param string|int $sInput number (either as string or int) to pad with 0
   * @param int @length default 2
   * @return string
   */
  function tz ($sInput,$length=2) {
    /****************************************************************************************************************
    * Function: tz (Leading Zero)
    * Purpose: Add trailing zeroes to a string value.
    * Arguments: Input(string), length(numeric) default 2
    * Method: use str_pad to extend it to $length
    ****************************************************************************************************************/
    if ($sInput=="" || $length==0) {
      return $sInput;
    }
    $sInput=(string) $sInput;
    $sInput=str_pad($sInput,$length,"0",STR_PAD_RIGHT);
    return $sInput;
  }


  /**
   * Return the left $iLength number of characters from $sString
   *
   * @param string $sString
   * @param int @iLength default 1
   * @return string
   */
  function left($sString, $iLength=1)
  {
     return substr($sString,0,$iLength);
  }
  /**
   * Return the right $iLength number of characters from $sString
   *
   * @param string $sString
   * @param int $iLength default 1
   * @return string
   */
  function right($sString, $iLength=1)
  {
    return substr($sString,strlen($sString)-$iLength,$iLength);
  }

  ///////////////////////////////////////////// Mapping Functions ////////////////////////////////////////////////////
  /**
   * Return the institutionID for the specified subdomain
   *
   * @param int $ipkInstitutionID by reference.  Default null
   * @param string $sSubDomain by reference.  default null.
   * @return bool Returns a value to indicate if an institution was found.
   */
  function getInstitutionFromDomain(&$ipkInstitutionID, &$sSubDomain, &$bAltLang, &$sLanguageName, &$sAltInstName) {

    //function returns either an ID or boolean false.
  //  global $con,$smarty;
    global  $smarty;
    $con = Helper::getConnection();
    //find the domain/subdomain.
    $sHost=$_SERVER["HTTP_HOST"];
    $sName=$_SERVER["SERVER_NAME"];
    //first we need to sanitize these two.  All that's allowed would be a-z, 0-9, ., _, - and POSSIBLY :
    $sHost=preg_replace("[^A-Za-z0-9?!\.\-_]","",$sHost);
    $sName=preg_replace("[^A-Za-z0-9?!\.\-_]","",$sName);
    $sSubDomain=str_replace(strtolower($sName),"",strtolower($sHost));
    $sSubDomain=str_replace(".","",$sSubDomain);

    $return=false;
    $InstQueryString="select ipkinstitutionid, bAltLanguage, sLanguageName, sAltLanguageName from tbl_institutions where lower(ssubdomain)=:subdomain";
    $oInstStmt=$con->prepare($InstQueryString);
    $oInstStmt->bindparam(":subdomain",$sSubDomain,PDO::PARAM_STR,50);
    if (!$oInstStmt->execute()) {
      $errInf=$oInstStmt->errorInfo();
      $smarty->assign("BDEBUG",true);
      $smarty->assign("ERRORMESSAGE","Error getting institutions!");
      $smarty->assign("DRIVER_ERROR_MESSAGE",$errInf[2]);
      $smarty->display("error.tpl");
      die();
    }
    if ($oInstStmt->rowCount()>0) {
      list ($ipkInstitutionID, $bAltLang, $sLanguageName, $sAltInstName)=$oInstStmt->fetch();
      return true;
    } else {
      return false;
    }
  }
  
  //tempoary functions to be added to new class;
  
  
  //get Faculty List
  function getFacultyArrays(&$arFacultyIDs, &$arFacultyNames) {
    global $con, $ipkInstitutionID;
    $sQryFaculty="select ipkFacultyID, sName from tbl_faculties where ifkInstitutionID=:ifkInstitutionID and bdeleted=false order by sname asc;";
    $stmtFaculty=$con->prepare($sQryFaculty);
    $stmtFaculty->bindParam(":ifkInstitutionID",$ipkInstitutionID,PDO::PARAM_INT);
    if (!$stmtFaculty->execute()) {
      $errInf=$stmtFaculty->errorInfo();
      $smarty->assign("BDEBUG",true);
      $smarty->assign("ERRORMESSAGE","Error getting faculties!");
      $smarty->assign("DRIVER_ERROR_MESSAGE",$errInf[2]);
      $smarty->display("error.tpl");
      die ();
    }
    $arFacultyIDs=array();
    $arFacultyNames=array();
    if ($stmtFaculty->rowCount()>0) {
      while (list($ipkFacultyID, $sFacultyName)=$stmtFaculty->fetch()) {
        $arFacultyIDs[]=$ipkFacultyID;
        $arFacultyNames[]="$sFacultyName";
      }
    }
    $stmtFaculty->closeCursor();
  }

  //get initial department List
  function getDepartmentArrays(&$arDepartmentIDs, &$arDepartmentNames) {
    global $con, $ipkInstitutionID;
    $sQryDept  = "select ipkDepartmentID, d.sName \n";
    $sQryDept .= "from tbl_departments d  \n";
    $sQryDept .= "inner join tbl_faculties f on f.ipkFacultyID=d.ifkFacultyID  \n";
    $sQryDept .= "where f.ifkInstitutionID=:ifkInstitutionID and d.bdeleted=false and f.bDeleted=false \n";
    $sQryDept .= "order by d.sname asc;";
    $stmtDept=$con->prepare($sQryDept);
    $stmtDept->bindParam(":ifkInstitutionID",$ipkInstitutionID,PDO::PARAM_INT);
    if (!$stmtDept->execute()) {
      $errInf=$stmtDept->errorInfo();
      $smarty->assign("BDEBUG",true);
      $smarty->assign("ERRORMESSAGE","Error getting Departments!");
      $smarty->assign("DRIVER_ERROR_MESSAGE",$errInf[2]);
      $smarty->display("error.tpl");
      die ();
    }
    $arDepartmentIDs=array();
    $arDepartmentNames=array();
    if ($stmtDept->rowCount()>0) {
      while (list($ipkDepartmentID, $sDeptName)=$stmtDept->fetch()) {
        $arDepartmentIDs[]=$ipkDepartmentID;
        $arDepartmentNames[]="$sDeptName";
      }
    }
    $stmtDept->closeCursor();
  }
  
  //get initial units List
  function getUnitArrays(&$arUnitIDs, &$arUnitNames) {
    global $con, $ipkInstitutionID;
    $sQryUnit  = "select ipkUnitID, u.sName \n";
    $sQryUnit .= "from tbl_units u  \n";
    $sQryUnit .= "inner join tbl_faculties f on f.ipkFacultyID=u.ifkFacultyID  \n";
    $sQryUnit .= "where f.ifkInstitutionID=:ifkInstitutionID and u.bdeleted=false and f.bDeleted=false \n";
    $sQryUnit .= "order by u.sname asc;";
    $stmtUnit=$con->prepare($sQryUnit);
    $stmtUnit->bindParam(":ifkInstitutionID",$ipkInstitutionID,PDO::PARAM_INT);
    if (!$stmtUnit->execute()) {
      $errInf=$stmtUnit->errorInfo();
      $smarty->assign("BDEBUG",true);
      $smarty->assign("ERRORMESSAGE","Error getting Units!");
      $smarty->assign("DRIVER_ERROR_MESSAGE",$errInf[2]);
      $smarty->display("error.tpl");
      die ();
    }
    $arUnitIDs=array();
    $arUnitNames=array();
    if ($stmtUnit->rowCount()>0) {
      while (list($ipkUnitID, $sUnitName)=$stmtUnit->fetch()) {
        $arUnitIDs[]=$ipkUnitID;
        $arUnitNames[]="$sUnitName";
      }
    }
    $stmtUnit->closeCursor();
  }
  
  //get Faculty List
  function getSupportServiceArrays(&$arSupportServiceIDs, &$arSupportServiceNames) {
    global $con, $ipkInstitutionID;
    $sQrySupportService="select ipkSupportServiceID, sName from tbl_support_services where ifkInstitutionID=:ifkInstitutionID and bdeleted=false order by sname asc;";
    $stmtSupportService=$con->prepare($sQrySupportService);
    $stmtSupportService->bindParam(":ifkInstitutionID",$ipkInstitutionID,PDO::PARAM_INT);
    if (!$stmtSupportService->execute()) {
      $errInf=$stmtSupportService->errorInfo();
      $smarty->assign("BDEBUG",true);
      $smarty->assign("ERRORMESSAGE","Error getting support services!");
      $smarty->assign("DRIVER_ERROR_MESSAGE",$errInf[2]);
      $smarty->display("error.tpl");
      die ();
    }
    $arSupportServiceIDs=array();
    $arSupportServiceNames=array();
    if ($stmtSupportService->rowCount()>0) {
      while (list($ipkSupportServiceID, $sSupportServiceName)=$stmtSupportService->fetch()) {
        $arSupportServiceIDs[]=$ipkSupportServiceID;
        $arSupportServiceNames[]="$sSupportServiceName";
      }
    }
    $stmtSupportService->closeCursor();
  }  
  
  function getCampusArrays(&$arCampusIDs, &$arCampusNames) {
    global $con, $ipkInstitutionID;
    $sQryCampus="select ipkCampusID, sName from tbl_campuses where ifkInstitutionID=:ifkInstitutionID and bdeleted=false order by sname asc;";
    $stmtCampus=$con->prepare($sQryCampus);
    $stmtCampus->bindParam(":ifkInstitutionID",$ipkInstitutionID,PDO::PARAM_INT);
    if (!$stmtCampus->execute()) {
      $errInf=$stmtCampus->errorInfo();
      $smarty->assign("BDEBUG",true);
      $smarty->assign("ERRORMESSAGE","Error getting campuses!");
      $smarty->assign("DRIVER_ERROR_MESSAGE",$errInf[2]);
      $smarty->display("error.tpl");
      die ();
    }
    $arCampusIDs=array();
    $arCampusNames=array();
    if ($stmtCampus->rowCount()>0) {
      while (list($ipkCampusID, $sCampusName)=$stmtCampus->fetch()) {
        $arCampusIDs[]=$ipkCampusID;
        $arCampusNames[]="$sCampusName";
      }
    }
    $stmtCampus->closeCursor();
  }
  
?>

