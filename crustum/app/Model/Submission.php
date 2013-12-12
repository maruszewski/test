<?php

class Submission extends AppModel {

	public $actsAs = array(
		'Encryption.Encrypted' => array(
			'fields' => array(
				'email',
				'name',
				'phone'
			)
		)
	);

	public $validate = array(

		'email' => array(
			'rule' => 'email',
			'allowEmpty' => false,
			'message' => 'Please, enter a valid e-mail'
		),
		'name' => array(
			'rule' => array('minLength', 3),
			'allowEmpty' => false,
			'message' => 'Please, enter your full name'
		),
		'phone' => array(
			'rule' => 'phone',
			'allowEmpty' => false,
			'message' => 'Please, enter your phone'
		),
		'body' => array(
			'rule' => array('minLength', 5),
			'allowEmpty' => false,
			'message' => 'Please, provide a message'
		)

	);

}