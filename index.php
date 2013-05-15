<?php
	include("inc/load_teams.inc.php");
	include("inc/mod_xml.inc.php");
	$xml = load_squad();
	session_start();

	if (isset($_SESSION['logged_in'])) {
		if ($_SESSION['logged_in'] == true) {
			foreach ($_REQUEST as $key => $value) {
				$_REQUEST[$key] = htmlspecialchars(htmlentities($value));
			}
			if (isset($_REQUEST['new_member'])) {
				write_xml($_REQUEST['id'], $_REQUEST['nick'], $_REQUEST['displaynick'], $_REQUEST['email'], $_REQUEST['icq'], $_REQUEST['title'], $_REQUEST['team']);
			}
			if (isset($_GET['del'])) {
				del_member($_GET['del']);
			}

			if (isset($_REQUEST['edit_submit'])) {
				edit_member($_REQUEST['old_id'], $_REQUEST['Id'], $_REQUEST['Rang'], $_REQUEST['Email'], $_REQUEST['ICQ'], $_REQUEST['team']);
			}
		}
	}
?>
<html>
	<head>
		<title>Squad.xml - <?php echo $xml->name; ?></title>
		<link rel="stylesheet" type="text/css" href="style.css"></link>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>

	<body>
		<div id="container">
			<div id="header">
				<?php
					echo '<div id="topic">';
					echo $xml->name . " [" . $xml->title . "]";
					echo '</div>';
					echo '<div id="web">';
					echo '<a href="' . $xml->web . '">' . $xml->web . '</a>';
					echo '</div>';
				?>
				<div id="squadxml">
					squad.xml - Viewer by Stuffy
				</div>
			</div>
			<div id="content">
				<?php
					$teams = load_teams();
					foreach ($teams as $key => $value) {
						echo '<div class="team">';
							echo '<div class="team_topic">';
								echo '<div class="team_topic_bg">';
								echo $key . ' <span class="member_count">(' . count($value) . ')</span> ' . '<a href="#" class="spoiler_plus">+</a>';
								echo '</div>';
								if (isset($_SESSION['logged_in'])) {
									echo '<div class="team_topic_new">';
									echo '<a href="#" class="new_member">Neuer Member +</a>';
									echo '</div>';
								}
							echo '</div>';
							echo '<div class="spoiler_newmember">';
								if (isset($_SESSION['logged_in'])) {
									echo '<form name="new_member" method="POST" action="index.php">';
										echo 'ID: <input title="N/A" type="text" name="id" class="input_new defaultText" /><br>';
										echo 'Nick: <input title="Ingame Name" type="text" name="nick" class="input_new defaultText" /><br>';
										echo 'Anzeige-Name: <input title="Anzeige-Name" type="text" name="displaynick" class="input_new defaultText" /><br>';
										echo 'E-Mail: <input title="N/A" type="text" name="email" class="input_new defaultText" /><br>';
										echo 'ICQ: <input title="N/A" type="text" name="icq" class="input_new defaultText" /><br>';
										echo 'Rang: <input title="N/A" type="text" name="title" class="input_new defaultText" /><br>';
										echo 'Team: <select name="team" size="1">';
										foreach ($teams as $key2 => $value2) {
											if ($key2 == $key) {
												echo '<option value="' . $key2 . '" selected>' . $key2 . '</option>';
											} else {
												echo '<option value="' . $key2 . '">' . $key2 . '</option>';
											}
										}
										echo '</select><br>';
										echo '<input type="submit" name="new_member" value="Erstellen" />';
									echo '</form>';
								}
							echo '</div>';
							echo '<div class="spoiler">';
							$i = 0;
							foreach ($value as $playername => $member) {
								echo '<div class="member">';
									if ($i % 2 == 0) {
										echo '<div class="member_topic_two">';
									}
									else {
										echo '<div class="member_topic_one">';
									}
										echo '<b>' . $playername. '</b><a href="#" class="spoiler_plus">+</a>';
										if (isset($_SESSION['logged_in'])) {
											echo '<a href="index.php?del=' . $member['Id'] . '" class="spoiler_delete">L&ouml;schen</a>';
										}
									echo '</div>';
									$i++;
								echo '<div class="spoiler">';
								if (isset($_SESSION['logged_in'])) {
									echo '<form name="edit_member_form" action="index.php" method="POST">';
								}
								foreach ($member as $name => $c) {
									echo '<div class="property">';
									echo '<div class="property_topic">';
										if (!isset($_SESSION['logged_in'])) {
											echo "<b>" . $name . "</b> : <span>" . $c . "</span><br>";
										}
										else {
											if ($name == 'Id') {
												echo '<input class="defaultText" type="hidden" name="old_id" value="' . $c . '">';
											}
											if ($name == 'Remark') {
												echo 'Team: <select name="team" size="1">';
												foreach ($teams as $key2 => $value2) {
													if ($key2 == $key) {
														echo '<option value="' . $key2 . '" selected>' . $key2 . '</option>';
													} else {
														echo '<option value="' . $key2 . '">' . $key2 . '</option>';
													}
												}
												echo '</select><br>';
											}
											else {
												echo '<b>' . $name . '</b> : <input class="defaultText" type="text" name="' . $name . '" value="' . $c . '" /><br>';
											}
										}
									echo '</div>';
									echo '</div>';
								}
								if (isset($_SESSION['logged_in'])) {
									echo '<input type="submit" value="Bearbeiten" name="edit_submit" class="edit_submit" />';
									echo '</form>';
								}
								echo '</div>';
							echo '</div>';
						}
						echo '</div>';
						echo '</div>';
					}
				?>
			</div>

			<div id="footer">

				<?php if (!isset($_SESSION['logged_in'])) { ?>
					<form name="login" action="auth/login.php">
						User: <input class="defaultText" type="text" name="user" />
						Passwort: <input class="defaultText" type="password" name="password" />
						<input class="defaultText" type="submit" value="Login" />
					</form>
				<?php } else { ?>
					<form name="logout" action="auth/logout.php">
						<input class="defaultText" type="submit" value="Logout" />
					</form>
				<?php
					}
				?>
			</div>
		</div>
		
		<script>
			$('a.spoiler_plus').click(function (event) {
				var $target = $(event.target);
				if ($target.parent().attr("class") == "team_topic_bg") {
					$target.parent().parent().siblings('.spoiler').toggle();
				} else {
					$target.parent().siblings('.spoiler').toggle();
				}
				if ($target.text() == "+") {
					$target.text("-");
				}
				else {
					$target.text("+");
				}
			});

			$('a.new_member').click(function (event) {
				var $target = $(event.target);
				$target.parent().parent().next().toggle();
				if ($target.text() == "Neuer Member +") {
					$target.text("Neuer Member -");
				}
				else {
					$target.text("Neuer Member +");
				}
			});

			$(document).ready(function()
			{
			    $(".defaultText").focus(function(srcc)
			    {
			        if ($(this).val() == $(this)[0].title)
			        {
			            $(this).removeClass("defaultTextActive");
			            $(this).val("");
			        }
			    });
			    
			    $(".defaultText").blur(function()
			    {
			        if ($(this).val() == "")
			        {
			            $(this).addClass("defaultTextActive");
			            $(this).val($(this)[0].title);
			        }
			    });
			    
			    $(".defaultText").blur();        
			});
		</script>

	</body>
</html>