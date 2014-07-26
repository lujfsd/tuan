<?php
class Tuan360Client
{/*{{{*/
    private $_key;
    private $_secret;
    private $_api;
    public function __construct($key,$secret)
    {/*{{{*/
        $this->_key = $key;
        $this->_secret = $secret;
        $this->_api = "http://tuan.360.cn/api/deal.php";
    }/*}}}*/
    public function send($qid,$order_id,$order_time,$pid,$price,$number,$total_price
        ,$goods_url,$title,$desc,$spend_close_time,$merchant_addr)
    {/*{{{*/
        $data = array();
        $data['key'] = $this->_key;
        $data['qid'] = $qid; 
        $data['order_id'] = $order_id;
        $data['order_time'] = $order_time;
        $data['pid'] = $pid;
        $data['price'] = $price;
        $data['number'] = $number;
        $data['total_price'] = $total_price;
        $data['goods_url'] = urlencode($goods_url);
        $data['title'] = urlencode($title);
        $data['desc'] = urlencode($desc);
        $data['spend_close_time'] = $spend_close_time;
        $data['merchant_addr'] = urlencode($merchant_addr);
        $data['sign'] = md5($data["key"]."|".$data["qid"]."|".$data["order_id"]."|".$data["order_time"]."|".$data["pid"]."|".$data["price"]."|".$data["number"]."|".$data["total_price"]."|".$data["goods_url"]."|".$data["title"]."|".$data["desc"]."|".$data["spend_close_time"]."|".$data["merchant_addr"]."|".$this->_secret);
        return $this->post($data);
    }/*}}}*/

    private function post($data)
    {/*{{{*/
        $url = parse_url($this->_api);
        if (!$url) { return "couldn't parse url"; }
        if (!isset($url['port']))  { $url['port'] = ""; }
        if (!isset($url['query'])) { $url['query'] = ""; }
        // Build POST string
        $encoded = "";
        foreach ($data as $k => $v) {
            $encoded .= ($encoded ? "&" : "");
            $encoded .= rawurlencode($k) . "=" . rawurlencode($v);
        }

        // Open socket on host
        $fp = @fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
        if (!$fp) { return "failed to open socket to {$url['host']}"; }

        // Send HTTP 1.0 POST request to host
        fputs($fp, sprintf("POST %s%s%s HTTP/1.0\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
        fputs($fp, "Host: {$url['host']}\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Content-length: " . strlen($encoded) . "\n");
        fputs($fp, "Connection: close\n\n");
        fputs($fp, "$encoded\n");

        // Read the first line of data, only accept if 200 OK is sent
        $line = fgets($fp, 1024);
        if (!preg_match('#^HTTP/1\\.. 200#', $line)) { return; }

        // Put everything, except the headers to $results 
        $results = ""; $inheader = TRUE;
        while(!feof($fp)) {
            $line = fgets($fp, 1024);
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = FALSE;
            }
            elseif (!$inheader) {
                $results .= $line;
            }
        }
        fclose($fp);
        // Return with data received
        return $results;
    }/*}}}*/
}/*}}}*/

/************ HOW TO USE ************
$key    = "yourKey";
$secret = "yourSecret"; //密钥都是小写

$qid              = 12345;     //360用户的ID
$order_id         = "TB0011";  //可支持字符串
$order_time       = "201103011135";
$pid              = "TB1111";
$price            = "19.20";
$number           = "2";
$total_price      = "38.40";
$goods_url        = "http://www.groupon.cn/BeiJing/-1291244391165249640.html";
$title            = "原件1116元的锅里壮";
$desc             = "仅需￥196元，即可尊享原价1116元的锅里壮食府海马戏海兔超值4人套餐：海马赛鳄鱼+海兔扒金谷+驴肚炖野兔+板栗焖牛";
$spend_close_time = "20110304093030";
$merchant_addr    = "北京市朝阳区利泽西街望京海鲜大卖场三层锅里壮食府";

$client = new Tuan360Client($key,$secret);
$r = $client->send($qid,$order_id,$order_time,$pid,$price,$number
    ,$total_price,$goods_url,$title,$desc,$spend_close_time,$merchant_addr);
echo $r;
**********************************/
