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

// 轮盘抽奖
class RouletteLottery
{
	public $result = array();
	public $itemCount = 11;
	
    public function __construct()
    {
		
    }
	
	public function Get($id = 0)
	{
		$lottery = array();
		
		if($id == 0)
		{
			$lottery['id'] = $id;
			
			$lottery['background'] = "";
			
			$i = 1;
			for($i;$i <= $this->itemCount;$i++)
			{
				$item = array();
				$item['index'] = $i;
				$item['name'] = "";
				$item['type'] = 0;
				$item['val'] = 0;
				$item['total_num'] = -1;
				$item['day_num'] = 0;
				$item['begin_time'] = "";
				$item['probability'] = 0;
				$item['status'] = 0;
				$lottery["items"][] = $item;
			}
		}
		else
		{
			$lottery['id'] = $id;
			
			$lottery['background'] = D("LotterySettings")->where("id = $id and name = 'background'")->getField("val");
			
			$sql = "SELECT li. *,count(DISTINCT lu.id) as wcount from ".C("DB_PREFIX")."lottery_items as li ".
				"LEFT JOIN ".C("DB_PREFIX")."lottery_user AS lu ON lu.lottery_id = li.id AND lu.lottery_item_index = li.`index` AND lu.id IS NOT NULL ".
				"WHERE li.id = $id GROUP BY li.`index` ORDER BY li.`index` ASC";
			
			$lottery["items"] = M()->query($sql);
			
			$tBTime = mktime(0, 0, 0, date("m")  , date("d"), date("Y")) - date('Z');
			
			foreach($lottery["items"] as $key=>$item)
			{
				$sql = "SELECT count(DISTINCT id) as dcount from ".C("DB_PREFIX")."lottery_user where lottery_id = $id AND lottery_item_index = ".$item['index']." AND id IS NOT NULL AND create_time >= $tBTime";
				$dcount = M()->query($sql);
				$lottery["items"][$key]["dcount"] = intval($dcount[0]["dcount"]);
			}
			//$lottery["items"] = D("LotteryItems")->where("id = $id")->order("`index` asc")->findAll();
		}
		
		return $lottery;
	}
	
	public function Insert($id,$lottery,$uplist)
	{
		$background = "";

		if($uplist)
		{
			$up = current($uplist);
			$background = $up['recpath'].$up['savename'];
		}
			
		if(!empty($background))
		{
			$setting['id'] = $id;
			$setting['name'] = "background";
			$setting['val'] = $background;
			D("LotterySettings")->add($setting);
		}
		
		$i = 1;
		for($i;$i <= $this->itemCount;$i++)
		{
			$item = $_POST["index$i"];
			$item['id'] = $id;
			$item['index'] = $i;
			$item['name'] = trim($item['name']);
			$item['type'] = intval($item['type']);
			$item['val'] = intval($item['val']);
			$item['total_num'] = intval($item['total_num']);
			$item['every_num'] = intval($item['every_num']);
			$item['begin_time'] = localStrToTimeMax($item['begin_time']);
			$item['probability'] = intval($item['probability']);
			$item['status'] = intval($item['status']);
			D("LotteryItems ")->add($item);
		}
	}
	
	public function Update($id,$lottery,$uplist)
	{
		$background = "";
		
		if($uplist)
		{
			$up = current($uplist);
			$background = $up['recpath'].$up['savename'];
		}
			
		if(!empty($background))
		{
			$old = D("LotterySettings")->where("id = $id and name = 'background'")->find();
			
			$setting['id'] = $id;
			$setting['name'] = "background";
			$setting['val'] = $background;
			if($old)
			{
				unlink($lottery->getRoot().$old['val']);
				D("LotterySettings")->save($setting);
			}
			else
			{
				D("LotterySettings")->add($setting);
			}
		}
		
		$i = 1;
		for($i;$i <= $this->itemCount;$i++)
		{
			$item = $_POST["index$i"];
			$item['id'] = $id;
			$item['index'] = $i;
			$item['name'] = trim($item['name']);
			$item['type'] = intval($item['type']);
			$item['val'] = intval($item['val']);
			$item['total_num'] = intval($item['total_num']);
			$item['every_num'] = intval($item['every_num']);
			$item['begin_time'] = localStrToTimeMax($item['begin_time']);
			$item['probability'] = intval($item['probability']);
			$item['status'] = intval($item['status']);
			D("LotteryItems ")->where("id = $id and `index`=$i")->save($item);
		}
	}
	
	public function Delete($id,$lottery)
	{
		$background = D("LotterySettings")->where("id = $id and name = 'background'")->getField("val");
		unlink($lottery->getRoot().$background);
		
		D("LotterySettings")->where("id = $id")->delete();
		D("LotteryItems")->where("id = $id")->delete();
		D("LotteryGoods ")->where("lottery_id = $id")->delete();
		D("LotteryUserGroup  ")->where("lottery_id = $id")->delete();
		D("LotteryUser")->where("lottery_id = $id")->delete();
	}
}
?>