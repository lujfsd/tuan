<?php

function checkLogin($admin_name, $admin_pass)
{
	$admin_name = base64_decode($admin_name);
	$admin_pass = base64_decode($admin_pass);
	
	
    $sql = "SELECT count(*) as num FROM " . DB_PREFIX ."admin WHERE adm_name = \"" . $admin_name. "\" AND adm_pwd = \"" . md5($admin_pass) . "\" limit 1";
	
    $num = $GLOBALS['db']->getOne($sql, 'num');

    if ($num > 0)
    { 
		return true;
    }else{
		return false; 		
    }	    
}

/**
 *
 * @param sting $admin_name
 * @param sting $admin_pass
 * @param sting $table
 * @param sting $idfield
 * @return int
 */
function getMaxID($admin_name, $admin_pass, $table, $idfield)
{
	if (!checkLogin($admin_name, $admin_pass))
	{
		return "-999"; //login_faild
	}
	
	$table = base64_decode($table);
	$idfield = base64_decode($idfield);
	$sql = "select max(".$idfield.") as maxid from ".DB_PREFIX.$table;
	$maxid = intval($GLOBALS['db']->getOne($sql, 'maxid'));
	if ($maxid == 0)
	  $maxid = 1;
	return $maxid;
}

function execSql($admin_name,$admin_pass,$sql_list,$err_stop,$delimiter_rn){
   
	if (!checkLogin($admin_name, $admin_pass))
	{
		return false; //login_faild
	}

   $delimiter_rn = base64_decode($delimiter_rn);	
   $sql_list = base64_decode($sql_list);
   $sql_list = explode ($delimiter_rn, $sql_list);
   
   //logutils::log_obj( $sql_list );
   
   foreach ( $sql_list as $sql ) {
   	 //logutils::log_str( $sql );
	 $sync_item = array();
	 $sync_item['succ'] = $GLOBALS['db']->Execute($sql);
	 if ($sync_item['succ'] == false){
	 	$sync_item['data'] = base64_encode($sql); 
	 	$sync_item['errmsg'] = base64_encode($GLOBALS['db']->error_text);
	 	$syncitems[] = $sync_item;
	 	if ($err_stop == true){
	 		break;
	 	}
	 }	
	 $syncitems[] = $sync_item;	   		
   }
   
   $pack = array(
		"succ" => true,
		"items" => $syncitems
		);
		
	return $pack;    
}


function uploadRecord($admin_name, $admin_pass, $data, $table, $idfields, $syncfield, $delimiter_rn = "51eca_rn", $delimiter_cn = "51eca_cn" )
{
	if (!checkLogin($admin_name, $admin_pass))
	{
		return NULL;//"-999"; //login_faild
	}	
	//logutils::clear_log();
	
	//logutils::log_str( "data: =======================.$table===============================");
	//logutils::log_str( $data);
	//logutils::log_str( "base64_decode: ===================.$table===================================");
	$data = base64_decode($data);
	$table = base64_decode($table);
	$idfields = base64_decode($idfields);
	$syncfield = base64_decode($syncfield);
	$delimiter_rn = base64_decode($delimiter_rn);
	$delimiter_cn = base64_decode($delimiter_cn);
	
	//logutils::log_str( $data);
	//logutils::log_str( "str_iconv: =======================.$table===============================");
	//$data = str_iconv(ECS_CHARSET, EC_CHARSET, $data);
	//logutils::log_str( $data);
	
    
	$pack = uploadRecord2($data, $table, $idfields, $syncfield, $delimiter_rn, $delimiter_cn);
	
	
	foreach ( $pack['items'] as $sync_item )
	{
		if ($sync_item['succ'] == false)
		{
			$sync_item['errmsg'] = base64_encode($sync_item['errmsg']);
		}
	}
	//logutils::log_obj( $pack );base64_encode
	//logutils::log_str( "1errmsg:".$errmsg );
	return $pack;	
}

function uploadRecord2($data, $table, $idfields, $syncfield, $delimiter_rn = "51eca_rn", $delimiter_cn = "51eca_cn" )
{
	//logutils::clear_log();
	//logutils::log_str( "UploadRecord Begin ".$table );
	
	$table = strtolower($table);
	
	$data = XmlSafeStr($data);

	if (Empty($data))
	{	//logutils::log_str("data is Empty.");
		return NULL;
	}
	
	//logutils::log_str("data:".$data );
	//logutils::log_str( $delimiter_rn );
	//logutils::log_str( $delimiter_cn );
	
	
	$list = array();
    $recordarr = explode( $delimiter_rn, $data ); 
    
    //logutils::log_obj( $recordarr[0] );
    $fields = explode( $delimiter_cn, $recordarr[0] ); 
    
    //logutils::log_obj( $fields );
    $fieldcount = count($fields);
	for ($i = 1; $i < count($recordarr); $i++) 
	{
		$tmp = explode( $delimiter_cn, $recordarr[$i] ); 
		$v = array();
	    for ($j = 0; $j < $fieldcount; $j++)
        {
           $v[$fields[$j]] = $tmp[$j];
        }
        $list[] = $v;
	}    
	
	//logutils::log_obj( $list );
	
	//return NULL;
	
	$idcolarr = explode( ",", $idfields );
	
	//logutils::log_obj( "idfields:".$idfields );
	//logutils::log_obj( "idcolarr:".$idcolarr );
	//logutils::log_str( "idcolarr[0]:".$idcolarr[0] );
	
	foreach ( $list as $row )
	{
		//logutils::log_obj( $row );
		
		
		$sync_item = array( );
		$sync_item['table'] = $table;
		$sync_item['guid'] = ""; 
		$sync_item['succ'] = false;
		$sync_item['errmsg'] = "";
		$sync_item['syncstate'] = AS_SYNC_ADDED; 
		
		if ( array_key_exists( $syncfield, $row ) )
		{
			$sync_item['syncstate'] = $row[$syncfield];
		}
		
		//logutils::log_str( "syncstate-------------------------:" );
		//logutils::log_str("table:".$table );
		//logutils::log_str( "idfields:".$idfields );
		//logutils::log_str( "syncfield:".$syncfield );
		//logutils::log_str( "syncfield_value:".$row[$syncfield] );
		//logutils::log_str( $sync_item['syncstate'] );
		//logutils::log_str( "syncstate-------------------------:");

		if (($idfields == '') || empty( $idfields )){
			$idcndstr = '1=0';
		}else{
			$idcndstr = "";		
			$linefirst = true;	
			foreach ( $idcolarr as $idcol )
			{
				if ( array_key_exists( $idcol, $row ) )
				{
					if ($linefirst)
					  $sync_item['guid'] = $row[$idcol];
					else 
					  $sync_item['guid'] = $sync_item['guid'].",".$row[$idcol];
	   			 
					$linefirst = false;  
					
					if ( !empty( $idcndstr ) )
					{
						$idcndstr .= " and ";
					}
					$idcndstr .= $idcol."=".$row[$idcol];				
				}			
			}
		} 
		
		if ($fieldcount <> count($row))
		{
			$sync_item['succ'] = false;
			$sync_item['errmsg'] = "fieldcount error";	
			$syncitems[] = $sync_item;		
		}		
		else if (empty( $idcndstr ))
		{
			$sync_item['succ'] = false;
			$sync_item['errmsg'] = "idcndstr is empty";	
			$syncitems[] = $sync_item;				
		}
		else{
		//logutils::log_str( "UploadRecord End sync_item " );
		//logutils::log_obj( $sync_item );			
			//==============================switch begin==========================================
			switch ( $sync_item['syncstate'] )
				{
					
				case NULL :
					$sync_item['succ'] = false;
					$sync_item['errmsg'] = "invalid syncstate";
					break;					
				case AS_SYNC_DELETED :					
					$sql = "delete from ".DB_PREFIX.$table." where {$idcndstr}";
					//logutils::log_str( $sql );
					if ( !$GLOBALS['db']->Execute( $sql ) )
					{
						$sync_item['succ'] = false;
						$sync_item['errmsg'] = $GLOBALS['db']->error_text;
						break;
					}
					$sync_item['succ'] = true;
					break;
				case AS_SYNC_UNCHANGED :
					$sync_item['succ'] = true;
					break;
				case AS_SYNC_MODIFIED:									
					$sql = "select count(*) as icount from ".DB_PREFIX.$table." where {$idcndstr}";
					//logutils::log_str( $sql );
					$count = intval($GLOBALS['db']->getOne( $sql, 'icount'));
					if ( 0 < $count )
					{
						//logutils::log_obj( $row );
						$fvs = '';
						$linefirst = true;	
						foreach ( $row as $field=>$v )
						{
							//$v = str_replace( "'", "\'", $v );
							if (($field <> $syncfield))
							{
								if ($linefirst)
								  $fvs = $field."="."'".addslashes($v)."'";
								else
								  $fvs = $fvs.",".$field."="."'".addslashes($v)."'";
								$linefirst = false;
							}  
						}
						$sql = "UPDATE ".DB_PREFIX.$table."SET ".$fvs." WHERE {$idcndstr}";
						
						//logutils::log_str( $sql );
						
						if ( !$GLOBALS['db']->Execute( $sql ))
						{
							$sync_item['succ'] = false;
							$sync_item['errmsg'] = $GLOBALS['db']->error_text;
							break;
						}					
						
						$sync_item['succ'] = true;
						
						break;
					}else{
						$sync_item['errmsg'] = " data is deleted, cannot update";
						$sync_item['succ'] = false;
						//logutils::log_str( 'uploadProducts2:' );
						//logutils::log_str( $sync_item['errmsg'] );
						break;
					}
				case AS_SYNC_ADDED :
					$sql = "select count(*) as icount from ".DB_PREFIX.$table." where {$idcndstr}";
					//logutils::log_str( $sql );
					$count = intval($GLOBALS['db']->getOne( $sql, 'icount'));
					if ( 0 < $count ) 
					{   
						$sync_item['errmsg'] = " append error, data already exist";
						$sync_item['succ'] = false;
						//logutils::log_str( $sync_item['errmsg'] );
						break;
					}
						
					//logutils::log_obj( $row );
										
					$fs = '';
					$fv = '';
					$linefirst = true;	
					foreach ( $row as $field=>$v )
					{
						//$v = str_replace( "'", "\'", $v );
						if (($field <> $syncfield))
						{
							if ($linefirst)
							  $fs = $field;
							else
							  $fs = $fs.",".$field;
							  
							if ($linefirst)
							  $fv = "'".addslashes($v)."'";
							else
							  $fv = $fv.",'".addslashes($v)."'";
							  
							$linefirst = false;
						}  
					}
					$sql = "INSERT INTO ".DB_PREFIX.$table."(".$fs.") VALUES(".$fv.")";
					//logutils::log_str( $sql );
					
					if (!$GLOBALS['db']->Execute( $sql ))
					{
						$sync_item['succ'] = false;
						$sync_item['errmsg'] = $GLOBALS['db']->error_text;
						break;
					}									
					$sync_item['succ'] = true;
				}//switch ( $sync_item['syncstate'] )
			//====================================switch end=============================================
			//logutils::log_obj( $sync_item );
			$syncitems[] = $sync_item;
		}//if ( !empty( $idcndstr ))			
	}//foreach ( $list as $row )	
		
	$succ = true;
	foreach ( $syncitems as $sync_item )
	{
		if ($sync_item['succ'] == false)
		{
			$succ = false;
			break;
		}
	}
			
	$pack = array(
		"succ" => $succ,
		"items" => $syncitems
	);
	//logutils::log_str( "UploadRecord Return" );
	return $pack;
}

function downloadRecord2($sql, $delimiter_rn = "51eca_rn", $delimiter_cn = "51eca_cn" ) {

	$rows = $GLOBALS['db']->Execute($sql); 
	
	
	
	$data = '';
	$first = true;
	while (!$rows->EOF) {
		
	
	
		if ( $first )
		{
			foreach ( $rows->fields as $key=>$v){
				$data = $data.$delimiter_cn.$key;
			}
			$data = implode( $delimiter_cn, array_keys( $rows->fields));
			$data = $data. $delimiter_rn;
			$first = false;
			
			//return base64_encode($data);
		}
		$linefirst = true;
		foreach ( $rows->fields as $v)
		{
			if ( $linefirst )
			{
				$linefirst = false;
			}
			else
			{
				$data = $data. $delimiter_cn;
			}
			$data = $data. XmlSafeStr($v);
		}
		$data = $data. $delimiter_rn;		
		
		$rows->MoveNext();
	}
	
	
	
	if (!empty($data)){
		return base64_encode($data);	
	}else{
		return -1;
	}
}
		
function downloadRecord($admin_name, $admin_pass, $sql, $delimiter_rn = "51eca_rn", $delimiter_cn = "51eca_cn" ){
	//$admin_name, $admin_pass, 
	if (!checkLogin($admin_name, $admin_pass))
	{
		return NULL; //login_faild
	}	
	$sql = base64_decode($sql);
	$delimiter_rn = base64_decode($delimiter_rn);
	$delimiter_cn = base64_decode($delimiter_cn);
	
	//$sql = str_iconv(ECS_CHARSET, EC_CHARSET, $sql);
	$tmp = downloadRecord2($sql, $delimiter_rn, $delimiter_cn );
	return $tmp;
}
	
function downloadRecordBase64($admin_name, $admin_pass, $sql, $delimiter_rn = "51eca_rn", $delimiter_cn = "51eca_cn"){
	//$admin_name, $admin_pass, 
	if (!checkLogin($admin_name, $admin_pass))
	{
		return NULL; //login_faild
	}	
	$sql = base64_decode($sql);
	$delimiter_rn = base64_decode($delimiter_rn);
	$delimiter_cn = base64_decode($delimiter_cn);
	
	//$sql = str_iconv(ECS_CHARSET, EC_CHARSET, $sql);
	$tmp = downloadRecord2($sql, $delimiter_rn, $delimiter_cn );
	return $tmp;
}
	
?>