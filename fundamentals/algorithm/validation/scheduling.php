<?php 

function organizeTimetable($weekdayperiods){
	//array[weekday][periods][start_time] = end_time
	foreach ($weekdayperiods as $weekday => $periods) {
		//delete from array
		unset($weekdayperiods[$weekday]);
		//add unified start-end-time of weekday
		$periods = mergePeriods($periods, $periods);
		$weekdayperiods[$weekday] = $periods;
		//sort by key, to keep start-time in asc order
		ksort($weekdayperiods[$weekday]);
	}

	return $weekdayperiods;
}

function mergePeriods($periods1, $periods2){
	$auxPeriodVerified = array();
	$isUnified = false;
	
	foreach ($periods1 as $period1 => $startEndTime1) {
		foreach ($periods2 as $period2 => $startEndTime2) {
			//check if period is not yourself and now verified
			if($period1 != $period2 && empty($auxPeriodVerified[$period][$period2])){
				
				$result = mergePeriod($startEndTime1, $startEndTime2);
				
				
				if ($result === false){
					//var_dump("false");
				}
				else{
					//debug($result);
					//decho($period1, $period2);
					//check for duplicated
					foreach ($periods1 as $period_aux => $startEndTime_aux) {
						if ($result == $startEndTime_aux)
							unset($periods1[$period_aux]);
					}
					
					unset($periods1[$period1]);
					unset($periods1[$period2]);

					$uniqid = $result["start_time"]."_".$result["end_time"];
					$periods1[$uniqid]["start_time"] = $result["start_time"];
					$periods1[$uniqid]["end_time"] = $result["end_time"];

					$isUnified = true;
				}
				
				$auxPeriodVerified[$period1][$period2] = 1;
				//debug($periods1);
			}
		}
	}
	if ($isUnified){
		$periods1 = mergeTimetable($periods1, $periods1);
	}
	
	return $periods1;
}

function mergePeriod($startEndTime1, $startEndTime2){
	$hi1 = $startEndTime1["start_time"];
	$hf1 = $startEndTime1["end_time"];
	$hi2 = $startEndTime2["start_time"];
	$hf2 = $startEndTime2["end_time"];

	//echo 'A: '.$hi1.' - '.$hf1.' ______ '.$hi2.' - '.$hf2;

	$arrayTemp = array($hi1,$hf1,$hi2,$hf2);
	/*
		 _______							
		|_______|							
			 _______						
			|_______|						
		 ____
		|____|
			 ___
			|___|
	 _______________
	|_______________|

	*/
	if (($hi2 >= $hi1 && $hi2 <= $hf1) || ($hf2 >= $hi1 && $hf2 <= $hf1) || ($hi1 >= $hi2 && $hi1 <= $hf2) || ($hf1 >= $hi2 && $hf1 <= $hf2) || ($hi2 == $hi1 && $hf2 == $hf1) ){
		$array["start_time"] = min($arrayTemp);
		$array["end_time"] = max($arrayTemp);
		return $array;
	}
	else
		return false;
}

/*
	array[codperiodo] array ("start_time" => "", "end_time"=> "")
*/
function occupyPeriod($availablePeriod, $busyPeriod){
	/*debug($availablePeriod);
	debug($busyPeriod);
	die;*/
	$i = 0;
	foreach ($busyPeriod as $weekday_busy => $periods) {
		foreach ($periods as $period_busy => $startEndTime) {
			$isSplited = true;
			while($isSplited){
				$isSplited = false;
				foreach ($availablePeriod[$weekday_busy] as $period_available => $startEndTimeAvailable) {
					$i++;
					$result = getAvailablePeriod($startEndTimeAvailable, $startEndTime);
					//decho ($i, $startEndTimeAvailable["start_time"], $startEndTimeAvailable["end_time"], $startEndTime["start_time"], $startEndTime["end_time"]);
					//var_dump($result);
					if ($result === false){
						unset($availablePeriod[$weekday_busy][$period_available]);
						$hi = $startEndTimeAvailable["start_time"];
						$hf = $startEndTimeAvailable["end_time"];
						$uniqid = $hi."_".$hf;
						$availablePeriod[$weekday_busy][$uniqid] = $startEndTimeAvailable;
					}
					else{
						unset($availablePeriod[$weekday_busy][$period_available]);
						/*debug($startEndTimeAvailable, $startEndTime);
						die;*/
						foreach ($result as $key => $value) {
							$availablePeriod[$weekday_busy][$key] = $value;
						}
						//$temp = $availablePeriod[$weekday_busy];
						//unset($availablePeriod[$weekday_busy]);
						//$availablePeriod[$weekday_busy] = array_merge($temp, $result);
						
						if (count($result)){
							$isSplited = true;
						}

					}
					//debug($availablePeriod[$weekday_busy]);
				}
			}
		}
	}
	//echo $i;
	krsort($availablePeriod);

	return $availablePeriod;
}

function getAvailablePeriod($startEndTimeAvailable, $startEndTimeBusy){
	$hi1 = $startEndTimeAvailable["start_time"];
	$hf1 = $startEndTimeAvailable["end_time"];
	$hi2 = $startEndTimeBusy["start_time"];
	$hf2 = $startEndTimeBusy["end_time"];

	//echo 'A: '.$hi1.' - '.$hf1.' ______ '.$hi2.' - '.$hf2.'<br>';

	$arrayTempI = array($hi1,$hi2);
	$arrayTempF = array($hf1,$hf2);
	/*
		 _______							
		|_______|							
			 _______						
			|_______|	X
		 ____
		|____|			$hi2 >= $hi1 && $hi2 <= $hf1 && $hf2 >= $hi1 && $hf2 <= $hf1	HF2 -> HF1 CASE 1
			 ___
			|___|		$hi2 >= $hi1 && $hi2 <= $hf1 && $hf2 >= $hi1 && $hf2 <= $hf1	HI1 -> HI2 CASE 2
			__
		   |__|			$hi2 >= $hi1 && $hi2 <= $hf1 && $hf2 >= $hi1 && $hf2 <= $hf1	HI1 -> HI2    HF2 -> HF1 CASE 3
		 _______
		|_______|		$hi2 >= $hi1 && $hi2 <= $hf1 && $hf2 >= $hi1 && $hf2 <= $hf1	DELETE CASE 4


	*/
	if (($hi2 >= $hi1 && $hi2 <= $hf1 && $hf2 >= $hi1 && $hf2 <= $hf1)  || ($hi2 == $hi1 && $hf2 == $hf1) ){
		if ($hi1 == $hi2 && $hf2 < $hf1){//CASE 1
			$hi = $hf2;
			$hf = $hf1;
			$uniqid = $hi."_".$hf;
			$array[$uniqid]["start_time"] = $hi;
			$array[$uniqid]["end_time"] = $hf;
		}
		else if ($hi2 > $hi1 && $hf2 == $hf1){//CASE 2
			$hi = $hi1;
			$hf = $hi2;
			$uniqid = $hi."_".$hf;
			$array[$uniqid]["start_time"] = $hi;
			$array[$uniqid]["end_time"] = $hf;
		}
		else if($hi2 > $hi1 && $hf2 < $hf1){//CASE 3
			$hi = $hi1;
			$hf = $hi2;
			$uniqid = $hi."_".$hf;
			$array[$uniqid]["start_time"] = $hi;
			$array[$uniqid]["end_time"] = $hf;

			$hi = $hf2;
			$hf = $hf1;
			$uniqid = $hi."_".$hf;
			$array[$uniqid]["start_time"] = $hi;
			$array[$uniqid]["end_time"] = $hf;
		}
		else if($hi2 == $hi2 && $hf2 == $hf1){//CASE 4
			$array = array();
		}

		return $array;
	}
	else
		return false;
}



?>