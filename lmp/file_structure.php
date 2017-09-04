<?php
	function correctString($string){//returns a string without " and '
		$string = str_replace('"','',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace ("`",'',$string);
		return $string;
	}
	if (isset($_SESSION))//declare these functions only if the user is logged in.
	{
		function weekExists($week){//returns true or false if the week exists
			$file = fopen($_SESSION['trans_file'],'r');
			while (!feof($file)){
				if (trim(fgets($file)) === "WEEK_START: $week")
				{
					fclose($file);
					return true;
				}
			}
			fclose($file);
			return false;
		}
		function createWeek($week){//builds a week based off the current categories
			$file = fopen($_SESSION['trans_file'], 'a');
			$categories = getCategories();
			fwrite($file,"WEEK_START: $week\n");
			foreach ($categories as $lel)
			{
				if ($lel['type']=='SAVINGS')
					continue;
				fwrite($file,"WEEK_" . $lel['com'] . "\n");
				fwrite($file,"WEEK_" . $lel['com'] . "\n");
			}
			fwrite($file,"WEEK_END\n");
			
			fclose($file);
			setPersistantAll();
		}
		function convertToItem(&$option){//converts an item to how the computer reads it.
			$categories = getCategories();
			foreach ($categories as $key=>$data)
			{
				if ($option == $key)
				{
					
					$option="WEEK_" . $data['com'];
					break;
				}
			}
		}
		function getUnits($week, $option,$reverse=false){//returns all the transactions in a given category, and week. can be reversed.
			 convertToItem($option);
			 $file = fopen($_SESSION['trans_file'],'r');
			 $on_week=false;
			 $on_option=false;
			 $return = array();
			 while (!feof($file))
			 {
				$line = trim(fgets($file));
				if ($line == "WEEK_START: $week")
				{
					$on_week=true;
					continue;
					
				}
				if ($line == $option && $on_week && !($on_option))
				{
					$on_option = true;
					continue;
				}
				if ($line == $option && $on_option=true && $on_week==true)
				{
					break;
				}
				
				if ($on_option && $on_week)
				{
					$return[]=$line;
				}
				if ($line == 'WEEK_END' && $on_week==true)
				{
					$on_week=false;
				}
				
			 }
			 fclose($file);
			 if (empty($return))
				 return false;
			if (!$reverse)
				return $return;
			else
				return array_reverse($return);
		}
		function getPersistant(){//returns all persistant values.
			$a = [];
			$file = fopen($_SESSION['trans_file'],'r');
			$on=false;
			while (!feof($file))
			{
				$s = trim(fgets($file));
				
				if ($s=="PERSISTANT")
				{
					if (!$on)
					{
						$on = true;
						continue;
					}
					else
					{
						break;
					}
				}
				if ($on)
				{
					$key = substr($s,0,strpos($s,':'));
					$data = substr($s,strpos($s,':')+1);
					$a[$key]=$data;					
				}
			}
			fclose($file);
			if (empty($a))
			{
				$array = array_keys(getCategories());
				foreach ($array as $key)
				{
					$a[$key]=0;
				}
			}
			
			return $a;
		}
		function setPersistantAll(){//sets all persistant values.
			$week = thisWeek();
			$categories = getCategories();
			$array = [];
			$income_totals = [];
			foreach ($categories as $key=>$category)
			{			
				if ($key == "Income")
				{
					for ($i = 1; $i<$week;$i++)
					{
						$income_totals[$i] = getTotalAmount($i,"Income");
					}
				}
				else
				{
					for ($i=1; $i<$week; $i++)
					{
						$array[$key] = $income_totals[$i]*($category['data']/100) - getTotalAmount($i,$key);	
					}				
				}
			}
			$fullFile = [];
			$file = fopen($_SESSION['trans_file'],'r');
			$on_persistant=false;
			$written_persistant=false;
			while (!feof($file))
			{
				$str = fgets($file);
				if (trim($str) == "PERSISTANT"){
					if ($on_persistant)
						$on_persistant=false;
					else
						$on_persistant=true;
					if (!$written_persistant)
					{
						$fullFile[]="PERSISTANT\n";
						foreach ($array as $key=>$amount){
							$fullFile[]="$key:$amount\n";
						}
						$written_persistant=true;
						continue;
					}
					
					
				
				}
				if (!$on_persistant)
					$fullFile[] = $str;
			}
			fclose($file);
			
			$file = fopen($_SESSION['trans_file'],'w');
			foreach ($fullFile as $line)
			{
				fwrite($file,$line);
			}
			fclose($file);
			
			
			
		}
		function setPersistant($week=-1){
			if ($week==-1)
				$week = thisWeek();
			$categories = getCategories();
			$array = [];
			$income_totals = [];
			foreach ($categories as $key=>$category)
			{			
				if ($key == "Income")
				{
				
					$income_total = getTotalAmount($week,"Income");					
				}
				else
				{
					
					$array[$key] = $income_total*($category['data']/100) - getTotalAmount($week,$key);	
								
				}
			}
			file_put_contents('array.txt',var_dump($array));
			$fullFile = [];
			$file = fopen($_SESSION['trans_file'],'r');
			$on_persistant=false;
			$written_persistant=false;
			while (!feof($file))
			{
				$str = fgets($file);
				if (trim($str) == "PERSISTANT"){
					if ($on_persistant)
						$on_persistant=false;
					else
						$on_persistant=true;
					if (!$written_persistant)
					{
						$fullFile[]="PERSISTANT\n";
						foreach ($array as $key=>$amount){
							$fullFile[]="$key:$amount\n";
						}
						$written_persistant=true;
						continue;
					}
					
					
				
				}
				if (!$on_persistant)
					$fullFile[] = $str;
			}
			fclose($file);
			
			$file = fopen($_SESSION['trans_file'],'w');
			foreach ($fullFile as $line)
			{
				fwrite($file,$line);
			}
			fclose($file);
			
			
		

		}
		function insertNew($week, $option, $info){//inserts a new transaction.
		
			$f = fopen($_SESSION['trans_file'],'r');
			$on_week=false;
			$on_option = false;
			$written=false;
			$array = [];
			
			convertToItem($option);
			while (!feof($f))
			{
				$string = fgets($f);
				
				if (trim($string)=="WEEK_START: $week" && !$written){
					$on_week=true;
					$array[]=$string;
					continue;
				}
				if (trim($string)=="WEEK_END")
				{
					$on_week = false;
					$array[]=$string;
					continue;
				}
				if (trim($string)==$option && $on_week){
					if ($on_option)
					{
						$array[]=$info . "\n";
						$array[]=$string;
						$on_option=false;
						$written=true;

					}
					else
					{
						$on_option=true;
						$array[]=$string;
					}
					continue;
				}
				$array[]=$string;			
			}
			fclose($f);
			$f = fopen($_SESSION['trans_file'],'w');
			foreach($array as $line)
			{
				if (trim($line)=="")
					continue;
				fwrite($f,$line);
			}
			fclose($f);
			
			if ($written == false)
			{
				$f = fopen($_SESSION['trans_file'], 'r');
				$on_week=false;
				$on_option = false;
				$array = [];
				while (!feof($f))
				{
					$s = trim(fgets($f));
					
					if ($s == "WEEK_START: $week")
					{
						$on_week=true;
						$array[]=$s;
						continue;
					}
					if ($s == $option)
					{
						if ($on_week)
							$on_option==true;
						else
							$on_option=false;
					}
					if ($s == "WEEK_END" && $on_week)
					{
						$array[]=$option;
						$array[]=$option;
						$array[]=$s;						
						$on_week=false;
						continue;
					}
					$array[]=$s;
				}
				fclose($f);
				$f = fopen($_SESSION['trans_file'],'w');
				foreach($array as $line)
				{
					if (trim($line)=="")
						continue;
					fwrite($f,$line . "\n");
				}
				fclose($f);
				$written = insertNew($week, $option, $info);
			}
			if ($week < floor((time() - 1498708800) / 604800)+1)
				setPersistantAll();
			return $written;
		}
		function replaceOld($week, $option, $info, $oldLine){//$oldLine is an id as it would appear in getUnits. (array key)	
			$f = fopen($_SESSION['trans_file'],'r');
			$on_week=false;
			$on_option=false;
			$written=false;
			$info .= "\n";
			$array = [];
			$changed = false;
			convertToItem($option);
			$i = 0;
			while (!feof($f))
			{
				$string = fgets($f);
				if (trim($string)=="WEEK_START: $week"){
					$on_week=true;
					$array[]=$string;
					continue;
				}
				
				if (trim($string)==$option && $on_week){
	
					$on_option=true;
					$array[]=$string;
					continue;
				}

				if ($i==$oldLine && !$changed && $on_option)
				{
					$array[]=$info;
					$changed=true;
					continue;
				}
				if ($on_option)
					$i++;
				$array[]=$string;
			}
			fclose($f);
			$f = fopen($_SESSION['trans_file'],'w');
			foreach($array as $line)
			{
				if (trim($line)=="")
					continue;
				fwrite($f,$line);
			}
			fclose($f);
			return $written;
			
			
		}
		function removeOld($week, $option, $oldLine){//$oldLine is an id as it would appear in getUnits.	
			$f = fopen($_SESSION['trans_file'],'r');
			$on_week=false;
			$on_option = false;
			$written=false;
			$array = [];
			$changed = false;
			convertToItem($option);
			$i = 0;
			while (!feof($f))
			{
				$string = fgets($f);
				if (trim($string)=="WEEK_START: $week"){
					$on_week=true;
					$array[]=$string;
					continue;
					
				}
				if (trim($string)==$option && $on_week){
	
					$on_option=true;
					$array[]=$string;
					continue;
				}

				if ($on_week && $on_option && $i==$oldLine && !$changed)
				{
					$changed=true;
					continue;
				}
				if ($on_option)
					$i++;
				$array[]=$string;
			}
			fclose($f);
			$f = fopen($_SESSION['trans_file'],'w');
			foreach($array as $line)
			{
				if (trim($line)=="")
					continue;
				fwrite($f,$line);
			}
			fclose($f);
			return $written;
					
		}
		function getTotalAmount($week,$option){//gets total amount of cash of category transactions.
			convertToItem($option);
			$array = getUnits($week,$option);
			$amount = 0;
			if (empty($array))
				return 0;
			
			foreach($array as $transaction)
			{
				$s=(float)str_replace('$','',substr($transaction,0,strpos($transaction,'|')));		
				$amount += $s;
			}

			return $amount;
		}
		function toMoney($amount, $cho=false){//turns it to money format that people like
		$amount = round($amount*100)/100;
		$amount = "$" . $amount;
		if (strlen(substr($amount, strpos($amount,'.')))==2)
			$amount .= "0";
		if (!strpos($amount,'.'))
			$amount .=".00";
		
		return $amount;
	}
		function updatePersistant($recalculate=true){
			$categories = getCategories();
			$current = getPersistant();
		}
		function getCategories($week=-1){//returns 2D array of all categories affiliated with the given week. [Current week if no argument is supplied]
			if ($week == -1)
				$week = thisWeek();
			$arr = [];
			$file = fopen($_SESSION['pref_file'],'r');
			$on_week = false;
			while (!feof($file))
			{
		
				$st = trim(fgets($file));
				if ($st == "")
					continue;
				if ($st == "BUDGET_START")
				{
					$st = trim(fgets($file));
					$st = explode(',',$st);		
					if (in_array("$week",$st))
					{
						$on_week=true;
						continue;
					}			
				}
				if ($st == "BUDGET_END")
					$on_week = false;
				
				if ($on_week)
				{
					$i = strpos($st,':'); //occurance of first :
					$j = strpos($st,':',$i+1);
					$k = strpos($st,':',$j+1);
					
					$key = substr($st,0,$i);
					$data = substr($st,$i+1,$j-$i-1);
					if (substr($data,0,1)=='$')
					{
						$dataType='$';
					}
					else
					{
						$dataType='%';
					}
					$data = (float)substr($data, 1);
					$com = substr($st,$j+1,$k-$j-1);
					$type = substr($st, $k+1);
					$arr[$key]['data']=$data;
					$arr[$key]['com']=$com;
					$arr[$key]['type']=$type;
					$arr[$key]['numeralType']=$dataType;
				}
			}
			
				
			unset($arr[0]);
			fclose($file);
			return $arr;
		}

		function thisWeek(){
			$week_length=604800;
			$biweek_length=$week_length*2;
			$month_length=2629800;
			$quarter_length=7889400;
			$file = file_get_contents($_SESSION['const_file']);
			$i = strpos($file,':');
			
			$start = substr($file,0,$i);
			$interval = trim(substr($file,$i+1));
			switch($interval)
			{
				case 'WEEKLY':
					return floor((time()-$start)/$week_length)+1;
					break;
				case 'BIWEEKLY':
					return floor((time()-$start)/$biweek_length)+1;
					break;
				case 'MONTHLY':
					return floor((time()-$start)/$month_length)+1;
					break;
				case 'QUARTERLY':
					return floor((time()-$start)/$quarter_length)+1;
					break;
			}
			
		}
	}
	else
	{
		die();
	}
?>