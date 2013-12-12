<?php

class EncryptedBehavior extends ModelBehavior {

	protected $options = array();

	public function setup(Model $Model, $options = array()) {

		if (!isset($this->options[$Model->alias])) {
			$this->options[$Model->alias] = array(
				'fields' => array()
			);
		}

		$this->options[$Model->alias] = array_merge($this->options[$Model->alias], (array) $options);

		if (empty($this->options[$Model->alias]['fields'])) {
			throw new InternalErrorException('Encrypted Behavior: Fields not specified');
		}

		if (!function_exists('mcrypt_module_open')) {
			throw new InternalErrorException('Encrypted Behavior: Missing "mcrypt" module');
		}

		if (!Configure::read('Security.salt') ||
			Configure::read('Security.salt') === 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi') {
			throw new InternalErrorException('Encrypted Behavior: Missing or forbidden Security.salt variable');
		}

	}

	public function afterFind(Model $Model, $results, $primary) {

		$options = $this->options[$Model->alias];

		if (empty($results)) return $results;

		if (isset($results[0][$Model->alias])) {
			$this->processMulti($Model, &$results);
		}
		elseif (isset($results[$Model->alias])) {
			$this->processOne($Model, &$results);
		}


		return $results;

	}

	private function processMulti(Model $Model, &$results) {
		
		$options = $this->options[$Model->alias];

		for ($i = 0; $i < count($results); $i++) {

			if (empty($results[$i][$Model->alias])) {
				continue;
			}

			foreach ($options['fields'] as $field) {

				if (!isset($results[$i][$Model->alias][$field])) {
					continue;
				}

				$data = $this->eb_decrypt($results[$i][$Model->alias][$field]);

				$results[$i][$Model->alias][$field] = $data;

			}

		}

	}

	private function processOne(Model $Model, &$results) {

		$options = $this->options[$Model->alias];

		foreach ($options['fields'] as $field) {

			if (!isset($results[$Model->alias][$field])) {
				continue;
			}

			$data = $this->eb_decrypt($results[$Model->alias][$field]);

			$results[$Model->alias][$field] = $data;

		}

	}

	public function beforeSave(Model $Model) {

		$options = $this->options[$Model->alias];

		foreach ($options['fields'] as $field) {

			if (!isset($Model->data[$Model->alias][$field])) {
				continue;
			}

			$data = $this->eb_encrypt($Model->data[$Model->alias][$field]);

			if (!empty($Model->data[$Model->alias][$field]) && empty($data)) {
				throw new InternalErrorException('Encrypted Behavior: Encoding error');
			}

			$Model->data[$Model->alias][$field] = $data;

		}

		return true;

	}

	public function afterSave(Model $Model) {

		$options = $this->options[$Model->alias];

		foreach ($options['fields'] as $field) {

			if (!isset($Model->data[$Model->alias][$field])) {
				continue;
			}

			$data = $this->eb_decrypt($Model->data[$Model->alias][$field]);

			if (!empty($Model->data[$Model->alias][$field]) && empty($data)) {
				throw new InternalErrorException('Encrypted Behavior: Decoding error');
			}

			$Model->data[$Model->alias][$field] = $data;

		}

		return true;

	}

	/* Encryption */

	private function eb_encrypt($data = null) {

		if ($data === null) return '';

		$td = mcrypt_module_open('blowfish', '', 'ecb', '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		
		mcrypt_generic_init($td, Configure::read('Security.salt'), $iv);
		
		$encrypted_data = mcrypt_generic($td, $data);
		
		$encoded = base64_encode($encrypted_data);

		if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
			$encoded = '';
		}

		return $encoded;

	}

	/* Decryption */

	private function eb_decrypt($data = null) {

		if ($data === null) return '';

		$data = (string) base64_decode($data);

		$td = mcrypt_module_open('blowfish', '', 'ecb', '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

		mcrypt_generic_init($td, Configure::read('Security.salt'), $iv);

		$data = (string) trim(mdecrypt_generic($td, $data));

		if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
			$data = false;
		}

		return $data;

	}

}