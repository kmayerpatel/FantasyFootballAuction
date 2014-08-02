<?php

class FFAuctionConstants {
	const SALARY_CAP = 100;
	const MIN_ROSTER_SIZE = 12;
	const STATE_FILE_LOCATION = 'auction_state.txt';
	const STATE_LOG_FILE_LOCATION = 'auction_state_log.txt';

	static function owners() {
		return array('Ketan', 
			 		 'Jamo', 
			 		 'Forbes', 
			 		 'CG', 
			 		 'Z', 
			 		 'Los', 
			 		 'Elder', 
			 		 'Singer', 
			 		 'Vince', 
					 "O'Malley", 
					 'Rich', 
					 'Terrence');

	}
}