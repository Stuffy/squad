<?php
	function write_xml($id, $nick, $displaynick, $email, $icq, $title, $remark) {
		$file = "squad.xml";
		$xml = simplexml_load_file($file);

		$member = $xml->addChild('member');
		$member->addAttribute('id', $id);
		$member->addAttribute('nick', $nick);

		$member->addChild('name', $displaynick);
		$member->addChild('email', $email);
		$member->addChild('icq', $icq);
		$member->addChild('title', $title);
		$member->addChild('remark', $remark);

		if ( $xml->asXML('squad.xml') == 1 ) {
			return true;
		}
		else {
			return false;
		}

		return false;
	}

	function edit_member($id, $new_id, $new_rank, $new_email, $new_icq, $team) {
		$file = "squad.xml";
		$xml = simplexml_load_file($file);
		$suc = false;

		for ($i = 0; $i < count($xml->member); $i++) {
			if ($xml->member[$i]['id'] == $id) {
				$xml->member[$i]->id = $new_id;
				$xml->member[$i]->title = $new_rank;
				$xml->member[$i]->email = $new_email;
				$xml->member[$i]->icq = $new_icq;
				if ($xml->member[$i]->remark != $team) {
					$xml->member[$i]->remark = $team;
				}
				$suc = true;
			}
		}

		$xml->asXML('squad.xml');
		return $suc;
	}

	function del_member($id) {
		$file = "squad.xml";
		$xml = simplexml_load_file($file);
		$suc = false;

		for ($i = 0; $i < count($xml->member); $i++) {
			if ($xml->member[$i]['id'] == $id) {
				unset($xml->member[$i]);
				$suc = true;
			}
		}

		$xml->asXML('squad.xml');
		return $suc;
	}

?>