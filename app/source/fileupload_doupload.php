<?php
// 文件上传
	if ($_REQUEST ['m'] == 'FileUpload' && $_REQUEST ['a'] == 'doUpload') {
	{
		header("Content-Type:text/html; charset=utf-8");
		if(intval($_SESSION['user_id'])==0)
		{
			echo json_encode(array('error' => 1, 'message' => a_L("HC_PLEASE_REG_OR_REGISTER"))); exit;
		}
		require (ROOT_PATH . 'app/source/class/file.php');
		$list = uploadFile(0,'kind');
		if(is_array($list))
		{
			$file_url = ".".$list[0]['recpath'].$list[0]['savename'];
			echo json_encode(array('error' => 0, 'url' => $file_url));
			exit;
		}
		else
		{
			echo json_encode(array('error' => 1, 'message' => a_L("UPLOAD_FAILED")));
			exit;
		}
	}
}
?>