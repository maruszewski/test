<?php

class SubmissionsController extends AppController {

	public $paginate = array(
		'Submission' => array(
			'limit' => 10,
			'order' => array(
				'Submission.created ASC'
			)
		)
	);

	function index() {

		if ($this->request->is('post')) {

			$saved = $this->Submission->save($this->data, array(
				'email',
				'name',
				'phone',
				'company',
				'body'
			));

			if ($saved) {

				$this->redirect(array(
					'controller' => 'submissions',
					'action' => 'greeting'
				));

			}

		}		

	}

	function greeting() {

	}

	/* Admin */

	function admin_index() {

		$data = $this->paginate('Submission');
		$this->set(compact('data'));

	}

}