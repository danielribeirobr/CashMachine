<?php

namespace Libs;

use Libs\CashMachine;
use Libs\Exceptions\RemoveNoteException;

class CustomCashMachine extends CashMachine {

	protected $_availableNotes = [];
	protected $_availableNotesQuantity = [];

	/**
	 * Add a note in the cashmachine
	 *
	 * @param      double   $noteValue  The note value
	 * @param      integer  $quantity   The quantity
	 */
	public function add($noteValue, $quantity = 1) {
		if(array_search($noteValue, $this->_availableNotes) === false) {
			array_push($this->_availableNotes, $noteValue);
			arsort($this->_availableNotes);
		}
		if(!array_key_exists($noteValue, $this->_availableNotesQuantity))
			$this->_availableNotesQuantity[$noteValue] = $quantity;
		else
			$this->_availableNotesQuantity[$noteValue] += $quantity;
	}

	/**
	 * Remove a note from cashmachine
	 *
	 * @param      double                         $noteValue  The note value
	 * @param      integer                        $quantity   The quantity
	 *                                                               
	 * @throws     \Libs\Exceptions\RemoveNoteException
	 */
	public function remove($noteValue, $quantity = 1) {		
		if(
			!array_key_exists($noteValue, $this->_availableNotesQuantity)
			|| ($this->_availableNotesQuantity[$noteValue] < $quantity)
		)
			throw new RemoveNoteException("Impossible to remove {$quantity} notes of value {$noteValue} from this machine");
		$this->_availableNotesQuantity[$noteValue] -= $quantity;
	}

	/**
	 * @overriding
	 *
	 */
	protected function _prepareToWithDraw($noteValue, $quantity) {
		parent::_prepareToWithDraw($noteValue, $quantity);
		$this->remove($noteValue, $quantity);
	}

	/**
	 * @overriding
	 *
	 */
	protected function _getCountNotes($value, $noteValue) {				
		$countNotes = parent::_getCountNotes($value, $noteValue);
		return $countNotes <= $this->_availableNotesQuantity[$noteValue] ? $countNotes : $this->_availableNotesQuantity[$noteValue];
	}

}
