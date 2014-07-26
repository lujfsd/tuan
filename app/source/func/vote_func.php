<?php
	function getVote($item=false)
	{
		$filename=md5("getVote".C_CITY_ID.$item).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			$now = a_gmtTime();	
			$sql ="select id,`title`,`desc` from ".DB_PREFIX."vote where `status` = 1 and (`start_time` <= '{$now}' or `start_time`=0) and (`end_time` >= '{$now}' or `end_time`=0 ) and city_id = '".C_CITY_ID."' order by sort asc,id desc";
			$vote = $GLOBALS['db']->getRow($sql);
			if(!$vote)
			{
				$sql ="select  id,`title`,`desc` from ".DB_PREFIX."vote where status = 1  and (`start_time` <= '{$now}' or `start_time`=0) and (`end_time` >= '{$now}' or `end_time`=0) and city_id = 0 order by sort asc,id desc";
				$vote = $GLOBALS['db']->getRow($sql);
			}
			if($item){
				if($vote)
				{
					$voteItems = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."vote_item where status = 1 and vote_id = '{$vote['id']}'order by sort asc,id desc");
					foreach($voteItems as $voteItem)
					{
						$sql = "select vo.*,vg.title as group_title,vg.sort as group_sort from ".DB_PREFIX."vote_option as vo left join ".DB_PREFIX."vote_group as vg on vg.id = vo.group_id where vo.item_id = '{$voteItem['id']}' group by vo.id order by vo.sort asc,vo.id desc";
						
						$voteOptions = 	$GLOBALS['db']->getAll($sql);
						
						foreach($voteOptions as $voteOption)
						{
							$voteItem['groups'][$voteOption['group_id']]["id"] = $voteOption['group_id'];
							$voteItem['groups'][$voteOption['group_id']]["title"] = $voteOption['group_title'];
							$voteItem['groups'][$voteOption['group_id']]["sort"] = $voteOption['group_sort'];
							$voteItem['groups'][$voteOption['group_id']]['options'][] = $voteOption;
						}
						
						foreach($voteItem['groups'] as $key => $voteGroup)
						{
							usort($voteItem['groups'][$key]['options'],"optionsSort");
						}
						
						usort($voteItem['groups'],"groupSort");
						$vote['items'][] = $voteItem;
					}
				}
			}
			setCaches($filename,$vote,substr($filename,0,1));
			return $vote;
		}
		return getCaches($filename,substr($filename,0,1));
	}
?>