<?php
// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: awfigq(67877605@qq.com)
// +----------------------------------------------------------------------

// 调查管理
class VoteModel extends MultiLangModel  {
	protected $_validate = array(
		array('title','require',VOTE_TITLE_REQUIRE), 
		array('start_time','checkDateFormat',VOTE_START_TIME_ERROR,2,'function'),
		array('end_time','checkDateFormat',VOTE_END_TIME_ERROR,2,'function'),
	);
	
	protected $_auto = array ( 		
		array('status','1'),
		array('start_time','localStrToTimeMax',3,'function'), 	   
		array('end_time','localStrToTimeMax',3,'function'),
	);
	
	public function getVote($id)
	{
		$vote = D("Vote")->getById($id);
		
		if($vote)
		{
			$voteItems = D("VoteItem")->where("status = 1 and vote_id = '$vote[id]'")->order("sort asc,id desc")->findAll();
			foreach($voteItems as $voteItem)
			{
				$sql = "select vo.*,vg.title as group_title,vg.sort as group_sort from ".C("DB_PREFIX")."vote_option as vo left join ".C("DB_PREFIX")."vote_group as vg on vg.id = vo.group_id where vo.item_id = '$voteItem[id]' group by vo.id order by vo.sort asc,vo.id desc";
				
				$voteOptions = 	M()->query($sql);
				
				foreach($voteOptions as $voteOption)
				{
					$voteOption["inputs"] =  D("VoteInput")->where("option_id = '$voteOption[id]'")->findAll();
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
		
		return $vote;
	}
}

function groupSort($a,$b)
{
	if($a['id'] == 0)
		return 1;
	elseif($b['id'] == 0 || $a['sort'] == $b['sort'])
		return 0;
		
	return ($a['sort'] < $b['sort']) ? -1 : 1;
}

function optionsSort($a,$b)
{
	if($a['sort'] == $b['sort'])
	{
		return ($a['id'] < $b['id']) ? 1 : -1;
	}
	
	return ($a['sort'] < $b['sort']) ? -1 : 1;
}
?>