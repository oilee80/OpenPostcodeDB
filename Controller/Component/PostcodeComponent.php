<?php
/**
 * Address Component
 * @author Lee Bradley <bradley.lee80@googlemail.com>
 */
class PostcodeComponent extends Component {

	/**
 * Find Addresses that match the supplied Postcode
 */
	public function findAddressesByPostcode($postcode) {
		$postcode = trim($postcode);
		if(!preg_match(PostcodeLookup::POST_CODE_REGEX, $postcode, $matches))
			return false;
debug($matches);
		$opts = array(
			'recursive' => -1,
			'conditions' => array(
				'post_code' => $matches
			)
		);
	}
}