<?php //Items regarding user accounts
	/*
		Every script shall haver only one connections instance, if it needs one. It will hold the connections for
		all the required bases. It accepts an array or string as its argument containing strings as to which databases should be opened.
		It also automatically closes these connections.
		The string option as an argument is only acceptable if there is one argument.
		So far, the only option(s) are:
		USER : access the lm_users database. $inst -> USER
	*/

	class connections{
		private $all = [];
		function __CONSTRUCT($all){ //accepts array or string with all databases you wish a connection for.
		if (!is_array($all))
			$all = [$all];
			$this -> all = $all;
			foreach ($all as $db)
			{
				switch ($db)
				{
					case 'USER': //it'll be $USER, put into $this, which is accessed by $instance -> USER.
						$this -> $db = mysqli_connect('localhost','lm_users','3ed%%kslidk.,skfh,','lm_users');
						
						break;
				}
			}			
		}
		function __DESTRUCT(){
			foreach ($this->all as $db)
			{
				$this->$db -> close(); //automatically close every connection that was opened by this instance.
			}
		}
	}
	function login($username, $password){
		
		$con = new connections('USER');
		$query = mysqli_query($con->USER, "SELECT * FROM users WHERE username='$username'");
		if (mysqli_num_rows($query) == 0)
		{
			return false;
		}
		else
		{
			$o = mysqli_fetch_object($query);
			if (md5($password)==$o->password)
			{
				$_SESSION['username'] = $username;
				$_SESSION['trans_file']= dirname(ROOT) . "/lmp/user_transactions/$username/transactions.txt";
				$_SESSION['pref_file'] = dirname(ROOT) . "/lmp/user_transactions/$username/options.txt";
				$_SESSION['const_file'] = dirname(ROOT) . "/lmp/user_transactions/$username/constants.txt";
				$_SESSION['directory'] = dirname(ROOT) . "/lmp/user_transactions/$username";
				return true;
			}
			else
				return false;
		}
		
	}

?>