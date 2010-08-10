<?php

require_once 'lib/tikiaccesslib.php';
require_once 'lib/language/Language.php';

/**
 * Test class for Language.
 * Generated by PHPUnit on 2010-08-05 at 10:04:14.
 */
class LanguageTest extends TikiTestCase {
	/**
	 * @var Language
	 */
	protected $obj;

	protected $lang;

	protected $langDir;

	protected $tikiroot;

	protected function setUp() {
		$this->tikiroot = dirname(__FILE__) . '/../../../';
		$this->lang = 'test_language';
		$this->langDir = $this->tikiroot . 'lang/' . $this->lang;

		chdir($this->tikiroot);
		mkdir($this->langDir);

		$this->obj = new Language($this->lang);

		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('Contributions by author', $this->lang, 'Contribuições por autor'));
		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('Remove', $this->lang, 'Novo remover'));
		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('Approved Status', $this->lang, 'Aprovado'));
		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('Something', $this->lang, 'Algo'));
		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('Trying to insert malicious PHP code back to the language.php file', $this->lang, 'asff"); echo \'teste\'; $dois = array(\'\',"'));
		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('Should escape "double quotes" in the source string', $this->lang, 'Deve escapar "aspas duplas" na string original'));
	}

	protected function tearDown() {
		if (file_exists($this->langDir . '/language.php')) {
			unlink($this->langDir . '/language.php');
		}

		if (file_exists($this->langDir . '/custom.php')) {
			unlink($this->langDir . '/custom.php');
		}

		rmdir($this->langDir);

		TikiDb::get()->query('DELETE FROM `tiki_language` WHERE `lang` = ?', array($this->lang));
	}

	// TODO: We need a way to create a Tiki database just for the tests
	/*public function testGetDbTranslatedLanguages() {
	}*/

	public function testAddPhpSlashes() {
		$string = "\n \t \r " . '\\ $ "';
		$expectedResult = '\n \t \r \\\\ \$ \"';
		$this->assertEquals($expectedResult, Language::addPhpSlashes($string));
	}

	public function testRemovePhpSlashes() {
		$string = '\n \t \r \\\\ \$ \"';
		$expectedResult = "\n \t \r " . '\\ $ "';
		$this->assertEquals($expectedResult, Language::removePhpSlashes($string));
	}

	public function testUpdateTransShouldInsertNewTranslation() {
		$this->obj->updateTrans('New string', 'New translation');
		$result = TikiDb::get()->getOne('SELECT `tran` FROM `tiki_language` WHERE `lang` = ? AND `source` = ?', array($this->lang, 'New string'));
		$this->assertEquals('New translation', $result);
		TikiDb::get()->query('DELETE FROM `tiki_language` WHERE `lang` = ? AND `source` = ?', array($this->lang, 'New string'));
	}

	public function testUpdateTransShouldUpdateTranslation() {
		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('New string', $this->lang, 'Old translation'));
		$this->obj->updateTrans('New string', 'New translation');
		$result = TikiDb::get()->getOne('SELECT `tran` FROM `tiki_language` WHERE `lang` = ? AND `source` = ?', array($this->lang, 'New string'));
		$this->assertEquals('New translation', $result);
		TikiDb::get()->query('DELETE FROM `tiki_language` WHERE `lang` = ? AND `source` = ?', array($this->lang, 'New string'));
	}

	public function testUpdateTransShouldDeleteTranslation() {
		TikiDb::get()->query('INSERT INTO `tiki_language` VALUES (?, ?, ?)', array('New string', $this->lang, 'New translation'));
		$this->obj->updateTrans('New string', '');
		$result = TikiDb::get()->getOne('SELECT `tran` FROM `tiki_language` WHERE `lang` = ? AND `source` = ?', array($this->lang, 'New string'));
		$this->assertFalse($result);
	}

	public function testWriteLanguageFile() {
		copy(dirname(__FILE__) . '/fixtures/language_orig.php', $this->langDir . '/language.php');
		$this->obj->writeLanguageFile();
		$this->assertEquals(file_get_contents(dirname(__FILE__) . '/fixtures/language_modif.php'), file_get_contents($this->langDir . '/language.php'));
	}

	public function testWriteLanguageFileCallingTwoTimesShouldNotDuplicateStringsInTheFile() {
		copy(dirname(__FILE__) . '/fixtures/language_orig.php', $this->langDir . '/language.php');
		$this->obj->writeLanguageFile();
		$this->obj->writeLanguageFile();
		$this->assertEquals(file_get_contents(dirname(__FILE__) . '/fixtures/language_modif.php'), file_get_contents($this->langDir . '/language.php'));
	}

	public function testWriteLanguageShouldReturnTheNumberOfNewStringsInLanguageFile() {
		copy(dirname(__FILE__) . '/fixtures/language_orig.php', $this->langDir . '/language.php');
		$expectedResult = array('modif' => 2, 'new' => 4);
		$return = $this->obj->writeLanguageFile();
		$this->assertEquals($expectedResult, $return);
	}

	public function testDeleteTranslations() {
		$this->obj->deleteTranslations();
		$this->assertFalse(TikiDb::get()->getOne('SELECT * FROM `tiki_language` WHERE `lang` = ?', array($this->obj->lang)));
	}

}
?>
