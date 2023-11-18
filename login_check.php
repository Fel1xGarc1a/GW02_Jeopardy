<?php 
		session_start(); 
	
		if(isset($_POST['Submit'])){	
				
		$Username = isset($_POST['Username']) ? $_POST['Username'] : '';
		$Password = isset($_POST['Password']) ? $_POST['Password'] : '';
		
		$id = $_COOKIE["id"];
		$pw = $_COOKIE["pw"];
		echo $id;
		echo $pw;
		
		if ($Username == $id && $Password == $pw) {
			$_SESSION['Username']=$Username;
			$_SESSION['score'] = 0;
			header("location:intro.html");
			exit;
		} 
		else {
			$msg="<span style='color:red'>Invalid Login Details</span>";
			echo $msg;
		}

		$validUsername = 'user1';
		$validPassword = 'test1';
	
		if ($Username == $validUsername && $Password == $validPassword) {
			$_SESSION['Username']=$Username;
			$_SESSION['score'] = 0;
			header("location:intro.html");
			exit;
		} else {
			$msg="<span style='color:red'>Invalid Login Details</span>";
			echo $msg;
		}
		}
?>
