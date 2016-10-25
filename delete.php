<?php

include('config.php');

foreach ($accounts as $account) {
	print ("ACCOUNT: {$account['username']}\n");
	// connect to IMAP server by account data
	$append = $account['ssl'] ? '/ssl' : '';
	$connect = "{{$account['domain']}:{$account['port']}/imap{$append}}";
	$mailbox = imap_open("{$connect}INBOX",  $account['username'], $account['password']);

	if ( $mailbox ) {
		// retrieve all mails older than the age_in_seconds variable from now - the UID is returned in an array
		$seconds = $account['age_in_days'] * 86400;
		$mails = imap_search($mailbox, 'BEFORE "' . date('d-M-Y', (time() - $seconds)) . '"', SE_UID);
		if ($mails)
			// mark all the mails older than age_in_seconds as deleted
			foreach($mails as $id) {
				$info = imap_fetch_overview($mailbox, $id, FT_UID);
				if ($info && $info[0]) {
					print ("{$account['username']}: delete id {$id} - {$info[0]->from}: {$info[0]->subject}\n");
					imap_delete($mailbox, $id, FT_UID);
				}
			}

		imap_close($mailbox, CL_EXPUNGE);
	} else {
		echo ("could not connect to {{$account['domain']}:{$account['port']}/imap} for {$account['username']}.\n");
	}
}

