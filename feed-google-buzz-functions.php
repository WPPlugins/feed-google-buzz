<?
class FeedGoogleBuzzDownload{
	function descargar($URL)
	{
		$data=$this->descargar_por_socket($URL);
		if(!$data)
			$data=$this->descargar_por_curl($URL);
		if(!$data)
			$data=$this->descargar_normal($URL);
		if(!$data || strlen($data)<5){
			return false;
		}else{
			
			$data=str_replace("><", ">\n<", $data);
			$array_data=array(
				'data'=>array(),
				'entry'=>array()
			);
			if(preg_match_all("/<entry>(.*?)<\/entry>/is", $data, $res, PREG_SET_ORDER)){
				
				foreach ($res as $r){
					//var_dump($r[1]);
					$tmp=array();
					if(preg_match_all("/<(?:title|content type='html'|summary type='text'|published|updated|uri|name)>(.*?)<\/(title|content|summary|published|updated|uri|name)>/is", $r[1], $r2, PREG_SET_ORDER)){
						foreach ($r2 as $rr){
							if(!array_key_exists($rr[2], $tmp)){
								if($rr[2]=='content'){
									$rr[1]=htmlspecialchars_decode($rr[1]);
								}
								$tmp[$rr[2]]=$rr[1];
								if($rr[2]=='name' || $rr[2]=='uri'){
									if(!array_key_exists($rr[2], $array_data['data'])){
										$array_data['data'][$rr[2]]=$rr[1];
									}
								}
							}
						}
					}
					
					if (preg_match("/<link rel='alternate' type='text\/html' href='(.*?)'\/>/is", $r[1], $r2)){
						$tmp['link']=$r2[1];
					}
					if(count($tmp)>0){
						$array_data['entry'][]=$tmp;
					}
					unset($tmp);
				}
			}
			unset($data);
			return ($array_data);
		}
	}
	private function descargar_por_curl($URL) 
	{
	
		$ch = curl_init ($URL);
		
	   curl_setopt ($ch, CURLOPT_URL, $URL);
	   curl_setopt ($ch, CURLOPT_HEADER, 0);
	   curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	   	
		return curl_exec ($ch);
		curl_close ($ch);
	
	}
	private function descargar_normal($URL){
		return file_get_contents($URL);
	}
	private function descargar_por_socket($URL)
	{
		$regs=parse_url($URL);		
		$da = @fsockopen($regs['host'], 80, $errno, $errstr, 30);
		if (!$da) 
		{
		    return false;
		} 
		else 
		{
		    $salida = "GET {$regs['path']} HTTP/1.0\r\n";
		    $salida .= "Host: {$regs['host']}\r\n";
		    $salida .= "Connection: Close\r\n\r\n";
			$ch='';
		    fwrite($da, $salida);
		    while (!feof($da)) {
		        $ch.=fgets($da, 128);
		    }
		    fclose($da);
		    list($header, $content)=explode("\r\n\r\n", $ch, 2);
		    if(!empty($content)){
		    	return $content;
		    }
		    return false;
		}
	}

}

function fgb_txt_time_diff($from, $to=false){
	$to=$to?$to:time();
	$df = $to - $from;
	if ($df < 60) return sprintf(__("hace %d seguntos",'feed-google-buzz'), $df);
	elseif ($df < 60*60) return sprintf(__("hace %d minutos",'feed-google-buzz'), (int)($df/60));
	elseif ($df < 60*60*24) return sprintf(__("hace %d horas",'feed-google-buzz'), (int)($df/3600));
	else return date('g:i A M d\, Y',$from);
}

function feed_google_buzz_init(){
	
	load_textdomain('feed-google-buzz', dirname(__FILE__) . '/languages/' . get_locale() . '.mo');
	//load_plugin_textdomain('feed-google-buzz', 'wp-content/plugins/feed-google-buzz/languages' );
}
add_action('init', 'feed_google_buzz_init');
?>