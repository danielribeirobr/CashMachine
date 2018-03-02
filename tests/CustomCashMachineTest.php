<?php

declare(strict_types=1);

require(realpath(__DIR__ . '/../') . '/autoload.php');
require(__DIR__ . '/CashMachineTest.php');

use PHPUnit\Framework\TestCase;

use Libs\CustomCashMachine;
use Libs\Exceptions\InvalidArgumentException;
use Libs\Exceptions\NoteUnavailableException;
use Libs\Exceptions\RemoveNoteException;

final class CustomCashMachineTest extends TestCase {

	protected $atm;

	protected function setUp() {
		$this->atm = new CustomCashMachine();
	}

	public function testWithNoNotesAdded() {
		$this->expectException(NoteUnavailableException::class);
		$this->atm->withdraw(125);
	}

	public function testWithInsuficientNotesAdded() {
		$this->expectException(NoteUnavailableException::class);
		$this->atm->add(100);
		$this->atm->add(10);
		$this->atm->withdraw(120);
	}

	public function testWithMultiplesWithDrawWithInsuficientNotesAdded() {
		$this->expectException(NoteUnavailableException::class);
		$this->atm->add(100, 2);
		$this->atm->withdraw(200);
		$this->atm->withdraw(100);
	}

	public function testWithMultiplesWithDraw() {

		// Withdraw 1 (add 350 and withdraw)
		$this->atm->add(100, 2);
		$this->atm->add(50, 4);
		$this->assertEquals(
			$this->atm->withdraw(350),
			[100, 100, 50, 50, 50]
		);

		// Withdraw 2 (add more 70 and withdraw)
		$this->atm->add(10, 2);
		$this->assertEquals(
			$this->atm->withdraw(70),
			[50, 10, 10]
		);
	}

	public function testWithAddedNotesAndRemoveBeforeWithDraw() {
		$this->expectException(NoteUnavailableException::class);
		$this->atm->add(10, 3);
		$this->atm->remove(10, 2);
		$this->atm->withdraw(30);
	}

	public function testWithRemoveNotesThatDoesNotExists() {
		$this->expectException(RemoveNoteException::class);
		$this->atm->add(10);
		$this->atm->remove(30);
	}

	public function testWithRemoveMoreNotesThanExists() {
		$this->expectException(RemoveNoteException::class);
		$this->atm->add(10, 5);
		$this->atm->remove(10, 8);
	}

}