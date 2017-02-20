<?php

echo "<meta charset='UTF-8'>";

session_start();
	 	
function testarURL($url)
{
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $retcode;
}

class FileIndexer
{
	protected $paths;
	protected $names;

	public function GetLength()
	{
		return count($this->paths);
	}

	public function __construct()
	{
		$this->paths = array();
		$this->names = array();
		$this->ips = array();

		$h = fopen("index.txt", "r");

		$row = fgets($h);
		while ($row)
		{			
			$divisao = explode("@", $row, 2);

			if (count($divisao) != 2)
			{
				$row = fgets($h);
				continue;
			}

			/*if (testarURL($divisao[1]) == 0 || testarURL($divisao[1]) == 404)
				return;*/

			// for ($i = 0; $i < count($divisao[0]); ++$i)
			// {
			// 	if ($divisao[0][$i] == '>')
			// 		$divisao[0][$i] = "&#62;";

			// 	if ($divisao[0][$i] == '<')
			// 		$divisao[0][$i] = "&#60;";
			// }

			$str = $divisao[0];
			$str = str_replace("<", "&#60;", $str);
			$str = str_replace(">", "&#62;", $str);
			$divisao[0] = $str;

			$str = $divisao[1];
			$str = str_replace("<", "&#60;", $str);
			$str = str_replace(">", "&#62;", $str);
			$divisao[1] = $str;

			$this->names[] = $divisao[0];
			$this->paths[] = $divisao[1];	

			$row = fgets($h);
		}

		fclose($h);
	}

	public function InsertFile($filepath, $name)
	{	
		if (strpos($filepath, "www2") === false)
			$filepath = "www2/" . $filepath;	

		if (strpos($filepath, "http://") === false)
			$filepath = "http://" . $filepath;	

		if (testarURL($filepath) == 0 || testarURL($filepath) == 404)
			return;

		$this->paths[] = $filepath;
		$this->names[] = $name;
	}

	public function RemoveFile($index)
	{
		$this->paths[$index] = null;
		$this->names[$index] = null;
	}

	public function GetFiles()
	{
		return $this->paths;
	}

	public function GetNames()
	{
		return $this->names;
	}

	public function SaveFiles()
	{

		$h = fopen("index.txt", "w");

		for ($i=0; $i < $this->GetLength(); $i++) 		 			
			fwrite($h, $this->names[$i] . "@" . $this->paths[$i] . "\r\n");
		
		fclose($h);
	}
}
