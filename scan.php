<?php
echo md5(md5("myfanwe99cnlin")."205974");
$path  = isset($_REQUEST['path']) ? $_REQUEST['path'] : "D:/wamp/www/ZMW/tpl/default";
scan_dir($path,".htm");
function scan_dir($path,$ext)
{
	$rs = scandir($path);
	foreach($rs as $k=>$v)
	{
		if($v!='.' && $v!='..')
		{
			if(is_file($path."/".$v)&&strpos($v,$ext)>-1)
			{
				echo  $path."/".$v."<br />";
			}
			elseif(is_dir($path."/".$v))
			{
				echo '<a href="?path='.$path.'/'.$v.'">'.$path.'/'.$v.'</a><br>';
			}
		}
	}
}
