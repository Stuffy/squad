<?php
	function load_teams() {
		$file = "squad.xml";
		$xml = simplexml_load_file($file);
		$teams = array();
		foreach ($xml as $key => $value) {
			if ($key == "member") {
				if (!in_array((string)$value->remark, $teams)) {
					$team_name = (string)$value->remark;
					$attributes = $value->attributes();
					$teams[$team_name][(string)$value->name]['Id'] = (string)$attributes['id'];
					$teams[$team_name][(string)$value->name]['Rang'] = (string)$value->title;
					$teams[$team_name][(string)$value->name]['Email'] = (string)$value->email;
					$teams[$team_name][(string)$value->name]['ICQ'] = (string)$value->icq;
					$teams[$team_name][(string)$value->name]['Remark'] = (string)$value->remark;
				}
			}
		}
		return $teams;
	}

	function load_squad() {
		$file = "http://stuffyserv.net/squad/squad.xml";
		$xml=simplexml_load_file($file);
		return $xml;
	}
?>