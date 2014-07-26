<?php
ini_set("display_errors",0);
error_reporting(E_ALL ^ E_NOTICE);
// Pull in the NuSOAP code
if (!defined('IS_ADMIN_FLAG'))
{
    define('IS_ADMIN_FLAG', true);
}

//lib_common: build_uri
require_once('eca_init.php'); 
require_once('lib/nusoap.php');
require_once('log_utils.php');
require_once('sync_file.php');
require_once('sync_record.php');

/*
$rows = $GLOBALS['db']->Execute("SELECT products_id, products_image FROM " . TABLE_PRODUCTS);
//print_r($rows)."<br>";

while (!$rows->EOF){
	print_r(productsImgList($rows->fields['products_id'], true));
	echo "<br><br>";
	$rows->MoveNext();
}


	if (!checkLogin(base64_encode('admin'), base64_encode('123456')))
	{
		echo "ogin_faild";
	}else{
		echo "login_ok";
	}	
*/
//
$debug = 0;

$server = new soap_server;
$server->soap_defencoding = 'utf-8';
$server->decode_utf8 = false;
$server->xml_encoding = 'utf-8';

$server->configureWSDL('ecaService', "urn:fanwe");

$server->register('hello',array("name"=>"xsd:string"),array("return"=>"xsd:string"));
// Define the method as a PHP function
function hello($name) {
	$name = base64_decode($name);
    return base64_encode('Hello, ' . $name);
}

function getParas($username, $password, $delimiter_rn = "51eca_rn", $delimiter_cn = "51eca_cn")
{

	if (!checkLogin($username, $password))
	{
		return "-999"; //login_faild
	}
	
	$delimiter_rn = base64_decode($delimiter_rn);
	$delimiter_cn = base64_decode($delimiter_cn);

	
	$params = 'PHP_SELF'.$delimiter_cn.str_replace('/'.ASSISTANT_PATH, '', dirname(PHP_SELF));	
	$params = $params.$delimiter_rn.'ASSISTANT_VERSION'.$delimiter_cn.ASSISTANT_VERSION;
	$params = $params.$delimiter_rn.'SYSTEM_TYPE'.$delimiter_cn.'fanwe';
	$params = $params.$delimiter_rn.'VERSION'.$delimiter_cn.VERSION;
	$params = $params.$delimiter_rn.'DBPREFIX'.$delimiter_cn.DB_PREFIX;
	$params = $params.$delimiter_rn.'ECS_CHARSET'.$delimiter_cn.'utf-8';
	/*
    $sql = "SELECT project_version_major, project_version_minor FROM " . TABLE_PROJECT_VERSION ." WHERE project_version_key = 'Zen-Cart Database' limit 1";
    $re = $GLOBALS['db']->Execute($sql);
    $re->Fields['project_version_major'];
	$re->Fields['project_version_minor'];
    */	
	//$params = $params.$delimiter_rn.'ADMIN_DIR'.$delimiter_cn.ADMIN_DIR;
	
	return base64_encode($params);
}

$server->wsdl->addComplexType(
    'SyncItem',
    'complexType',
    'struct',
    'all',
    '',
    array(
    	'table' => array('name'=>'table','type'=>'xsd:string'), 
        'guid' => array('name'=>'guid','type'=>'xsd:string'),                
        'syncstate' => array('name'=>'syncstate','type'=>'xsd:int'),
        'succ' => array('name'=>'succ','type'=>'xsd:boolean'),
        'errmsg' => array('name'=>'errmsg','type'=>'xsd:string'),
    	'data' => array('name'=>'data','type'=>'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'SyncItemArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:SyncItem[]')
    ),
    'tns:SyncItem'
);

$server->wsdl->addComplexType(
    'SyncPack',
    'complexType',
    'struct',
    'all',
    '',
    array(
    	'products_id' => array('name'=>'products_id','type'=>'xsd:int'),
    	'succ' => array('name'=>'succ','type'=>'xsd:boolean'),
       'items' => array('name'=>'items','type'=>'tns:SyncItemArray')
    )
);

$server->wsdl->addComplexType(
    'SyncPackArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:SyncPack[]')
    ),
    'tns:SyncPack'
);

$server->wsdl->addComplexType(
    'SyncPackList',
    'complexType',
    'struct',
    'all',
    '',
    array(
       'items' => array('name'=>'items','type'=>'tns:SyncPackArray')
    )
);

$server->register(
	'getParas',							// method name
	array("admin_name"=>"xsd:string","admin_pass"=>"xsd:string", 'delimiter_rn' => 'xsd:string', 'delimiter_cn' => 'xsd:string'),		// input parameters
	array('return' => 'xsd:string'),	// output parameters
	'urn:fanwe',							// namespace
	'urn:fanwe#getParas',			// SOAPAction
	'rpc',									// style
	'encoded'								// use
);

$server->register( 
	"getFileSize", 
	array("admin_name"=>"xsd:string","admin_pass"=>"xsd:string", "filename" => "xsd:string" ), 
	array( "return" => "xsd:int" ), 
	"urn:fanwe", 
	"urn:fanwe#getFileSize", 
	"rpc", "
	encoded", 
	"" 
);



$server->register(
	'downloadFile',							// method name
	array("admin_name"=>"xsd:string","admin_pass"=>"xsd:string", 'filename' => 'xsd:string'),		// input parameters
	array('return' => 'xsd:base64Binary'),	// output parameters
	'urn:fanwe',							// namespace
	'urn:fanwe#downloadFile',			// SOAPAction
	'rpc',									// style
	'encoded'								// use
);


$server->register(
	'downloadFile2',							// method name
	array("admin_name"=>"xsd:string","admin_pass"=>"xsd:string", 'filename' => 'xsd:string', "seq" => "xsd:int"),		// input parameters
	array('return' => 'xsd:base64Binary'),	// output parameters
	'urn:fanwe',							// namespace
	'urn:fanwe#downloadFile2',			// SOAPAction
	'rpc',									// style
	'encoded'								// use
);

$server->register(
	'uploadFile',							// method name
	array("admin_name"=>"xsd:string","admin_pass"=>"xsd:string", 'filename' => 'xsd:string', 'data' => 'xsd:base64Binary', "isFist" => "xsd:boolean"),		// input parameters
	array('return' => 'xsd:boolean'),		// output parameters
	'urn:fanwe',							// namespace
	'urn:fanwe#uploadFile',				// SOAPAction
	'rpc',									// style
	'encoded'								// use
);

$server->register( 
	"getMaxID", 
	array( "admin_name"=>"xsd:string","admin_pass"=>"xsd:string", "table" => "xsd:string", "idfield" => "xsd:string" ), 
	array( "return" => "xsd:int" ), 
	"urn:fanwe", 
	"urn:fanwe#getMaxID", 
	"rpc", 
	"encoded", 
	"" 
);

$server->register(
	"execSql", 
	array( "admin_name"=>"xsd:string","admin_pass"=>"xsd:string", "sql_list" => "xsd:string", "err_stop" => "xsd:boolean", "delimiter_rn" => "xsd:string"), 
	array( "return" => "tns:SyncPack" ), 
	"urn:fanwe", 
	"urn:fanwe#execSql", 
	"rpc", 
	"encoded", 
	"" 
);

   
$server->register(
	'downloadRecord',
	array("admin_name"=>"xsd:string","admin_pass"=>"xsd:string", "sql"=>"xsd:string", "delimiter_rn" => "xsd:string", "delimiter_cn" => "xsd:string" ),
	array("return"=>"xsd:string"),
	"urn:fanwe", 
	"urn:fanwe#downloadRecord", 
	"rpc", 
	"encoded", 
	"" 	
);		

$server->register(
	'downloadRecordBase64',
	array("admin_name"=>"xsd:string","admin_pass"=>"xsd:string", "sql"=>"xsd:string", "delimiter_rn" => "xsd:string", "delimiter_cn" => "xsd:string" ),
	array("return"=>"xsd:string"),
	"urn:fanwe", 
	"urn:fanwe#downloadRecordBase64", 
	"rpc", 
	"encoded", 
	"" 	
);

$HTTP_RAW_POST_DATA = isset( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA : "";
if ( empty( $HTTP_RAW_POST_DATA ) )
{
	$fp = fopen( "php://input", 'rb');
	while ( !feof( $fp ) )
	{
		$HTTP_RAW_POST_DATA .= fread( $fp, 4096 );
	}
	fclose( $fp );
}
$server->service( $HTTP_RAW_POST_DATA );
?>
