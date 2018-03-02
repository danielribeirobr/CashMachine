<?php

declare(strict_types=1);

require(realpath(__DIR__ . '/../') . '/autoload.php');

use PHPUnit\Framework\TestCase;

use Libs\CashMachine;
use Libs\Exceptions\InvalidArgumentException;
use Libs\Exceptions\NoteUnavailableException;

final class CashMachineTest extends TestCase {

	protected $atm;

	protected function setUp() {
		$this->atm = new CashMachine();
	}

	public function testWithPositiveValues() {
		$this->assertEquals(
			$this->atm->withdraw(40),
			[20, 20]
		);

		$this->assertEquals(
			$this->atm->withdraw(30),
			[20, 10]
		);

		$this->assertEquals(
			$this->atm->withdraw(40),
			[20, 20]
		);

		$this->assertEquals(
			$this->atm->withdraw(80),
			[50, 20, 10]
		);

		$this->assertEquals(
			$this->atm->withdraw(90),
			[50, 20, 20]
		);
	}

	public function testWithNegativeValues() {
		$this->expectException(InvalidArgumentException::class);

		$this->atm->withdraw(-130);
	}

	public function testWithInvalidValue() {
		$this->expectException(InvalidArgumentException::class);

		$this->atm->withdraw('A');
	}

	public function testWithNoValues() {
		$this->assertEquals(
			$this->atm->withdraw(0),
			[]
		);

		$this->assertEquals(
			$this->atm->withdraw(''),
			[]
		);
	}

	public function testWithNoAvaiableNotes() {
		$this->expectException(NoteUnavailableException::class);

		$this->atm->withdraw(125);
	}

}