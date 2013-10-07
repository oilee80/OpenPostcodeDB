<?php

class PostcodeLookupController extends AppController {

	public function index() {
		$this->PostcodeLookup->recursive = 0;
		$this->set('Addresses', $this->Paginator->paginate());
	}

}