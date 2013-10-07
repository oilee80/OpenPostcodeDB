<?php

class PostcodeLookup extends AppModel {

	public $primaryKey = 'postcode';

	const POST_CODE_REGEX = '/[A-Z]{1,2}[0-9]{1,2}\s?[0-9]{1,2}[A-Z]{1,2}/i';

	public $actsAs = array(
		'Enum.Enum' => array(
			'country' => array('England', 'Scotland', 'Wales', 'Northern Ireland')
		)
	);

	public $validate = array(
		'line_1' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'line_2' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'line_7' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'postcode' => array(
			'notempty' => array(
				'rule' => array('notempty')
			),
			'postal' => array(
				'rule' => array('postal', '/\\A\\b[A-Z]{1,2}[0-9][A-Z0-9]? ?[0-9][ABD-HJLNP-UW-Z]{2}\\b\\z/i', 'uk')
//				'rule' => array('postal', null, 'uk')
			)
		),
		'country' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'allowEmpty' => true
			)
		)
	);
/***/
	public static function formatPostcode($postcode) {
		return trim(preg_replace('/[^A-Z0-9\s]/', '', strtoupper($postcode)));
	}
/**
 * Used in conjunction with PostcodeLookup::tidyData() to check if the line needs to be removed
 * Will cause any empty lines to be removed when a call back for 'array_filter'
 * @param String $line An address Line
 * @return Boolean Whether to remove the line when called from array_filter
 */
	private function removeEmptyLines($line) {
		$line = preg_replace('/[^A-Z0-9]/i', '', $line);
		return !!strlen($line);
	}
/**
 * This will tidy the Data in an Array for use in a SaveAll
 * @param Array $data Array of lines of an Address with Postcode as the Last line and the most local line first
 * @return Array Array to be used in an App::save()
 */
	public function tidyData($data) {
		$origData = $data;
		$data = array_filter($data, array($this, 'removeEmptyLines'));
		$origData2 = $data;
		$pc = array_pop($data);
		$pc = PostcodeLookup::formatPostcode($pc);
		$l7 = array_pop($data);
// If the last Line of the Address is a Country then assign to the Country Field and get the next last line
		$country = in_array($l7, $this->enumValues('county'));
		if($country === false) {
			$l7 = array_pop($data);
			$country = null;
		}
			
		$l1 = array_shift($data);
		$l2 = array_shift($data);
		$l3 = array_shift($data);
		$l4 = array_shift($data);
		$l5 = array_shift($data);
		$l6 = array_shift($data);

		return array('PostcodeLookup' => array(
			'line_1' => $l1,
			'line_2' => $l2,
			'line_3' => $l3,
			'line_4' => $l4,
			'line_5' => $l5,
			'line_6' => $l6,
			'line_7' => $l7,
			'postcode' => $pc,
			'country' => $country
		));
	}
}