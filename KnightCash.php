<?php

/*
|--------------------------------------------------------------------------
| KnightCash Class 
|--------------------------------------------------------------------------
| This class handels the knight cash. you could use it to give knight cash.
| 
|
*/
class KnightCash
{
	protected $_knightCash,
	          $_db;

	const ACCOUNT_TABLE = 'TB_USER',
	      ACCOUNT_ID_COL = 'strAccountID',
	      KNIGHT_CASH_COL = 'nKnightCash',
	      TRANSACTIONS_TABLE = 'STORE_TRANSACTIONS',
	      PAYMENT_STATUS_COL = 'bStatus',
	      STATUS_COMPLETED = 3;

	public function __construct($transactionId, $db)
	{
		$this->_db = $db;
		$this->changeTransactionStatus($transactionId);
	}

	public function give($knightCash)
	{
		$this->_knightCash = $knightCash;

		return $this;
	}

	public function to($userId)
	{
        
		$userKnightCash = $this->getUserKnightCash($userId);
		
		$this->_db->doQuery('UPDATE ' . self::ACCOUNT_TABLE . ' SET ' . self::KNIGHT_CASH_COL . ' = ' . $userKnightCash . ' + ' . $this->_knightCash . ' WHERE ' . self::ACCOUNT_ID_COL . ' = ?', $userId);
	}

	protected function getUserKnightCash($userId)
	{
		$this->_db->doQuery('SELECT ' . self::KNIGHT_CASH_COL . ' FROM ' . self::ACCOUNT_TABLE . ' WHERE ' . self::ACCOUNT_ID_COL . ' = ?', $userId);

		if ($this->_db->hasError() || !$this->_db->hasRows())
				return;
	
		$row = $this->_db->doRead();

		return $row[self::KNIGHT_CASH_COL];
	}

	protected function changeTransactionStatus($transactionId)
	{
		$this->_db->doQuery('UPDATE ' . self::TRANSACTIONS_TABLE . ' SET ' . self::PAYMENT_STATUS_COL . '=' . self::STATUS_COMPLETED . ' WHERE id = ?', $transactionId);
	}
}
