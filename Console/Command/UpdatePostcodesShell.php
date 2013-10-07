<?php

/**
 * @property PostcodeLookup $PostcodeLookup PostcodeLookup Model
 */
class UpdatePostcodesShell extends AppShell {

	public $uses = array(
		'PostcodeDB.PostcodeLookup'
	);

	protected static $openDbDownload = 'http://postcodedb.sourceforge.net/dumpCSV.php';

/**
 * Imports the Latest Updates from the Open Database
 * Suggest running once a week
 * @return void(0)
 */
	public function main() {
		if($this->params['flush-table']) {
			$this->PostcodeLookup->deleteAll(true);
		}
		$fh = fopen(self::$openDbDownload, 'r');
		$saveData = array();
		while($data  = fgetcsv($fh)) {
			$d = $this->PostcodeLookup->tidyData($data);
			if(!$this->PostcodeLookup->save($d)) {
				$this->err(vsprintf('Failed to Save data for %s', $d['PostcodeLookup']['postcode']));
				$this->out( print_r($d['PostcodeLookup'], true));
				foreach ($this->PostcodeLookup->validationErrors As $field => $errors) {
					foreach($errors As $error) {
						$this->out(sprintf ('%s, failed for %s (%s)', $field, $error, print_r($d['PostcodeLookup'][$field], true)), 1, Shell::VERBOSE);
					}
				}
			}
		}
	}

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addOption('flush-table', array(
			'short' => 'f',
			'boolean' => true,
			'help' => 'Flush the Postcode Lookup Table and Re-import from scratch'
		))->
		description(array(
			'Update the Postcode Lookup Table',
			vsprintf('This will update the table based on any new Postcodes that have been submitted to "%s"', array(UpdatePostcodesShell::$openDbDownload))
		));
		return $parser;
	}
}