<?php

namespace Libs;

use Libs\Exceptions\InvalidArgumentException;
use Libs\Exceptions\NoteUnavailableException;

class CashMachine {

	/**
	 * Notes avaible to withdraw
	 *
	 * @var        array
	 */
	protected $_availableNotes = [100, 50, 20, 10];

	/**
	 * Array with notes that customer will receive
	 *
	 * @var        array
	 */
	protected $_withDraw = [];

	/**
	 * Withdraw notes
	 *
	 * @param      double                                    $value  The value to withdreay
	 *
	 * @throws     \Libs\Exceptions\InvalidArgumentException  The value of withdraw is invalid
	 * @throws     \Libs\Exceptions\NoteUnavailableException  There's no available notes to complete the customer request
	 *
	 * @return     array                                     ( Array with notes that customer will receive )
	 */
	public function withdraw($value) {

		if(strlen($value) && ($value < 0 || !is_numeric($value)))
			throw new InvalidArgumentException('Your argument is invalid');

		$remainingValue = $value;

		foreach($this->_availableNotes as $noteValue) {
			$countNotes = $this->_getCountNotes($remainingValue, $noteValue);
			if($countNotes > 0) {
				$this->_prepareToWithDraw($noteValue, $countNotes);
				$remainingValue -= $countNotes * $noteValue;

				if($remainingValue == 0)
					break;
			}
		}

		if($remainingValue > 0) {
			$this->_resetWithDraw();
			throw new NoteUnavailableException('Unavailable notes for withdraw');
		}

		$output = $this->_getWithDrawOutput();
		$this->_resetWithDraw();
		return $output;
	}

	/**
	 * Necessary after the customer complete the withdraw
	 */
	protected function _resetWithDraw() {
		$this->_withDraw = [];
	}

	/**
	 * Assign the note to withdraw to the customer
	 *
	 * @param      double  $noteValue  The note value
	 * @param      integer  $quantity   The quantity
	 */
	protected function _prepareToWithDraw($noteValue, $quantity) {
		array_push(
			$this->_withDraw,
			[
				'value' => $noteValue,
				'quantity' => $quantity
			]
		);
	}

	/**
	 * Gets the max count notes to reach the amount of value
	 *
	 * @param      integer  $value      The value
	 * @param      integer  $noteValue  The note value
	 *
	 * @return     integer   The count notes.
	 */
	protected function _getCountNotes($value, $noteValue) {
		return intval($value / $noteValue);
	}

	/**
	 * Gets the with draw output.
	 *
	 * @return     array  The with draw output.
	 */
	protected function _getWithDrawOutput() {
		$output = [];
		foreach($this->_withDraw as $note)
			for($i=0; $i < $note['quantity']; $i++)
				array_push($output, $note['value']);
		return $output;
	}

}