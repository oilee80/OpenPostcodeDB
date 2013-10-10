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

		$this->PostcodeLookup->recursive = -1;
// Process Parameters, Truncate the table if necessary
		if($this->params['flush-table']) {
			$this->PostcodeLookup->deleteAll(true);
		}
// Open File Handle
		$fh = fopen(self::$openDbDownload, 'r');
		$saveData = array();
// Initailize Counters
		$failed = $imported = $newAddress = 0;
// Loop through each line of CSV
		while($data  = fgetcsv($fh)) {
			$isNewAddress = false;
			$d = $this->PostcodeLookup->tidyData($data);
// Check if the Address is new
			if(!$this->PostcodeLookup->findByPostcode($d['PostcodeLookup']['postcode']))
				$isNewAddress = true;
			if(!$this->PostcodeLookup->save($d)) {
// Output Error messages for Failed Save
				$failed++;
				$this->out('<error>' . __('Failed to Save data for %s', array($d['PostcodeLookup']['postcode'])) . '</error>');
				$this->out('<debug>' . print_r($d['PostcodeLookup'], true) . '</debug>', 1, Shell::VERBOSE);
				foreach ($this->PostcodeLookup->validationErrors As $field => $errors) {
					foreach($errors As $error) {
						$this->out(
							__('<info>%s, failed for %s (%s)</info>',
								array(
									$field,
									$error,
									print_r($d['PostcodeLookup'][$field], true)
								)
							)
						);
					}
				}
			} else {
				$imported++;
				if($isNewAddress)
					$newAddress++;
			}
		}
		$this->out('<success>' . __('Successfully imported %d Addresses (%d where new)', array($imported, $newAddress)) . '</success>');
		if($failed)
			$this->out('<error>' . __('Failed to import %d Addresses', array($failed)) . '</error>');
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