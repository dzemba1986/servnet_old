<?php
class IpAddress
{
	private $address;
	private $netmask;
	public static function decToHR($decval)
	{
		$wynik = array();
		$tmp = $decval;
		for($i=3; $i >= 0; $i--)
		{
			$wynik[$i]= $tmp % 256;
			$tmp = $tmp/256.0;
		}
		return $wynik[0].".".$wynik[1].".".$wynik[2].".".$wynik[3];
	}
	public function getAddress()
	{
		return IpAddress::decToHR($this->address);
	}
	public function getNetmask()
	{
		return IpAddress::decToHR($this->netmask);
	}
	public function getNetworkAddress()
	{
		return $this->netmask & $this->address;
	}
	public function getHrNetworkAddress()
	{
		return $this->decToHR($this->netmask & $this->address);
	}
	public function getDecNetmask()
	{
		return $this->netmask;
	}
	public function getFirst()
	{
		return $this->getNetworkAddress()+1;
	}
	public function getHrFirst()
	{
		return $this->decToHr($this->getNetworkAddress()+1);
	}
	public function getLast()
	{
		$rev_mask = bindec(substr(decbin(~$this->netmask),-32, 32));
		$last = $rev_mask | $this->address;
		return $last -1;
	}
	public static function hrToDec($ip)
	{
		$address = preg_split('/\./', $ip, -1, PREG_SPLIT_NO_EMPTY);
		$tmp = null;
		foreach($address as $key=>$oktet)
		{
			$tmp = $tmp + $oktet * pow(256.0, 3 - $key);
		}
		return $tmp;
	}
	public function __construct($str_ip, $dec_mask)
	{
		//najpierw sprawdzamy poprawność budowy adresu i maski

		if(Daddy::sprawdz_ip($str_ip) && $dec_mask >0 && $dec_mask <=32)
		{
			$this->address = $this->hrToDec($str_ip);
			$this->netmask = (pow(2, $dec_mask)-1) << 32-$dec_mask;
			//$this->netmask_bin[0] = 
		}
		else
			die("nieprawidłowy adres lub maska");
	}

	public function generujPodsiec()
	{
		
		$first = $this->getFirst();
		$last = $this->getLast();
		$tmp = $first;
		$wynik = array();
		while($tmp <= $last)
		{
			$wynik[] = IpAddress::decToHR($tmp);
			$tmp++;
		}
		return $wynik;

	}
	public function czyIpNalezy($ip)
	{
		$dec_ip = $this->hrToDec($ip);
		$network = $this->getNetworkAddress();
	//	echo "<br> ".$ip;
	//	echo " dec: ".$dec_ip;
	//	echo " addr: ".$network;
	//	echo " iloczyn: ".($dec_ip & $network);
		if((($dec_ip & $this->netmask) == $network) && ($dec_ip > $network))
			return true;
                else
                {
                //  echo $this->decToHR($dec_ip)." ".$this->decToHR($network)."/".$this->decToHR($this->netmask)."<br>"; 
                }
		return false;
	}	
	public function shift($dec)
	{
		$dec = intval($dec);
		$this->address += $dec;
	}

}
?>
