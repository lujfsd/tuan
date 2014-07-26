<?php
	$ma = $_REQUEST['m']."_". strtolower ( $_REQUEST ['a'] );
	$ma();
	
	function Vote_index()
	{
		$navs = array('name'=>a_L("HC_VOTE"),'url'=>a_u("Vote/index"));
		
		$data = array(
			'navs' => array(
				$navs,
			),
			'keyword'=>	"",
			'content'=>	"",
		);
		assignSeo($data);
		require ROOT_PATH.'app/source/func/vote_func.php';
		$vote = getVote(true);
		if($vote)
		{
			$GLOBALS['tpl']->assign("vote",$vote);
		}
		else
			a_error(a_L("HC_NOT_VOTE"));
		
		
		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
		$GLOBALS['tpl']->display("Page/vote_index.moban");
	}
	
	function Vote_add()
	{
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		require ROOT_PATH.'app/source/func/vote_func.php';
		$navs = array('name'=>a_L("HC_VOTE"),'url'=>a_u("Vote/index"));
		
		$data = array(
			'navs' => array(
				$navs,
			),
			'keyword'=>	"",
			'content'=>	"",
		);
		assignSeo($data);
		if(check_ip_operation($_SESSION['CLIENT_IP'],"Vote",a_fanweC("MESSAGE_INTEVAL")))
    	{
    		foreach($_POST['items'] as $voteItem)
			{
				$id = "0,";
				foreach($voteItem as $key)
				{
					$id .= $key.",";
				}
				$where = "where id in (".substr($id,0,strlen($id)-1).")";
				
				$GLOBALS['db']->query("update ".DB_PREFIX."vote_option set vote_count=vote_count+1 $where");
				
				foreach($voteItem as $option)
				{
					$userInputs = strip_tags(trim($_POST['options'][$option]['text']));
					
					if(!empty($userInputs))
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."vote_option set vote_count=vote_count-1 $where");
						$voteOption = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."vote_option where id ={$option}");
						
						if($voteOption['separator'] != '')
						{
							$userInputs = explode($voteOption['separator'],$userInputs);
							foreach($userInputs as $input)
							{
								if(!empty($input))
								{
									if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."vote_input where value = '$input'") > 0)
										$GLOBALS['db']->query("update ".DB_PREFIX."vote_input set vote_count=vote_count+1 where `value` = '$input'");
									else
									{
										$voteInput['option_id'] = $option;
										$voteInput['value'] = $input;
										$voteInput['vote_count'] = 1;
										$GLOBALS['db']->autoExecute(DB_PREFIX.'vote_input',$voteInput);
									}
									
									$GLOBALS['db']->query("update ".DB_PREFIX."vote_option set vote_count=vote_count+1 where id = $option");
								}
							}
						}
						else
						{
							if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."vote_input where value = '$userInputs'") > 0)
								$GLOBALS['db']->query("update ".DB_PREFIX."vote_input set vote_count=vote_count+1 where `value` = '$userInputs'");
							else
							{
								$voteInput['option_id'] = $option;
								$voteInput['value'] = $userInputs;
								$voteInput['vote_count'] = 1;
								$GLOBALS['db']->autoExecute(DB_PREFIX.'vote_input',$voteInput);
							}
							$GLOBALS['db']->query("update ".DB_PREFIX."vote_option set vote_count=vote_count+1 where id = $option");
						}
					}
				}
			}
			
			$vote_id = intval($_POST['vote_id']);
			//$vote_items = D("VoteItem")->where("vote_id = '$vote_id'")->findAll();
			$vote_items = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."vote_item where vote_id = '{$vote_id}'");
			foreach($vote_items as $vote_item)
			{
				$optionCount =$GLOBALS['db']->getOne("select sum(vote_count) from ".DB_PREFIX."vote_option where item_id = '{$vote_item['id']}'");
				//D("VoteItem")->where("id = '$vote_item[id]'")->setField("vote_count",$optionCount);
				$GLOBALS['db']->autoExecute(DB_PREFIX.'vote_item',array('vote_count'=>$optionCount),"update","id = {$vote_item['id']}");
			}
			
			//$itemCount =D("VoteItem")->where("vote_id = '$vote_id'")->sum('vote_count');
			$itemCount = $GLOBALS['db']->getOne("select sum(vote_count) from ".DB_PREFIX."vote_item where vote_id = '{$vote_id}'");
			//D("Vote")->where("id = '$vote_id'")->setField("vote_count",$itemCount);
			$GLOBALS['db']->autoExecute(DB_PREFIX.'vote',array('vote_count'=>$itemCount),"update","id = {$vote_id}");
			success(a_L("SUBMIT_SUCCESS"));
    	}
    	else
    	{
    		a_error(a_L("HC_SUBMIT_TOO_FAST"));
    	}
	}
?>