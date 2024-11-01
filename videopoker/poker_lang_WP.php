<?php 
/*
language functions, to simplify localization

*/
global $a_poker_lang;
$a_poker_lang = array(
//cm_deposit.php , cm_withdraw.php - not going to localize for now 
//index.php
	'poker_deposit' => __('Deposit','simple-bitcoin-faucets'),
	'poker_withdraw' => __('Withdraw','simple-bitcoin-faucets'),
	'poker_bet' => __('Bet','simple-bitcoin-faucets'),
	'poker_balance' => __('Balance','simple-bitcoin-faucets'),
	'poker_deal' => __('Deal !','simple-bitcoin-faucets'),
	'poker_trade' => __('Trade !','simple-bitcoin-faucets'),
	'poker_time_to_play' => __("It's time to play! Click button to deal!",'simple-bitcoin-faucets'),
//a_poker.php
	'poker_got_gonus' => __("You've got bonus %n satoshi",'simple-bitcoin-faucets'),
	'royal_flush' => __("Royal flush",'simple-bitcoin-faucets'),
	'straight_flush' => __("Straight flush",'simple-bitcoin-faucets'),
	'four_of_a_kind' => __("Four of a kind",'simple-bitcoin-faucets'),
	'full_house' => __("Full house",'simple-bitcoin-faucets'),
	'a_flush' => __("A flush",'simple-bitcoin-faucets'),
	'a_straight' => __("A straight",'simple-bitcoin-faucets'),
	'three_of_a_kind' => __("Three of a kind",'simple-bitcoin-faucets'),
	'two_pair' => __("Two pair",'simple-bitcoin-faucets'),
	'jacks_or_better' => __("Jacks or better",'simple-bitcoin-faucets'),
	'almost_deal' => __("Almost! Deal to try again...",'simple-bitcoin-faucets'),
	'you_won' => __("You win %n satoshi",'simple-bitcoin-faucets'),
//poker_util.js	
	'satoshi' => __("satoshi",'simple-bitcoin-faucets'),
	'and' => __("and",'simple-bitcoin-faucets'),
	'cancel' => __("Cancel",'simple-bitcoin-faucets'),
	'must_win_1' => __("While playing on the bonus you must win at least",'simple-bitcoin-faucets'),
	'must_win_2' => __("times before withdraw.",'simple-bitcoin-faucets'),
	'enter_address' => __("Enter bitcoin or email address to withdraw your satoshi to",'simple-bitcoin-faucets'),
	'enter_amount' => __("Enter amount of satoshi to deposit",'simple-bitcoin-faucets'),
	'incorrect_amount' => __("is incorrect value for satoshi amount",'simple-bitcoin-faucets'),
	'must_be_between' => __("Must be between",'simple-bitcoin-faucets'),
	'something_went_wrong' => __("Something went wrong, please reset",'simple-bitcoin-faucets'),
	'you_still_have' => __("You still have",'simple-bitcoin-faucets'),
	'disable_adblock' => __("Please disable AdBlock",'simple-bitcoin-faucets'),
//poker.js - pain in the ass
	'must_bet_value' => __("You must enter bet value between",'simple-bitcoin-faucets'),
	'you_have_zero' => __("You have 0 satoshi",'simple-bitcoin-faucets'),
	'deposit_some' => __("Please deposit some",'simple-bitcoin-faucets'),
	'not_that_much' => __("You don't have that much money to bet",'simple-bitcoin-faucets'),
	'bet_to_1' => __("Bet set to 1 satoshi",'simple-bitcoin-faucets'),
	'click_cards_to_reade' => __("Click the cards you want to trade",'simple-bitcoin-faucets'),
	'no_satoshi_reset' => __("You've run out of satoshi! Click 'OK' to reset.",'simple-bitcoin-faucets'),
	
);

function poker_text($key)
{
	global $a_poker_lang;
	return($a_poker_lang[$key]);
}

function poker_text_to_js()
{
	global $a_poker_lang;

	$ret = "\n<script>";
	foreach ($a_poker_lang as $k => $v) 
	{
		$ret .= "\n var poker_text_" . $k . " = \"" . $v . "\";";
	}
	$ret .= "\n</script>\n";
	return($ret);
}


