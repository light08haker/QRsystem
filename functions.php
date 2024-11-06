
<?php

	function clean($data) {
		$data = trim($data);
		$data = stripslashes($data);

		return $data;
	}

	function showPrompt() {
		echo "<div class='yes'>".$_SESSION['prompt']."</div>";
	}

	function showError() {
		echo "<div class='no'>".$_SESSION['errprompt']."</div>";
	}
?>
