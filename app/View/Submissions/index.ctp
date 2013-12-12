<div id="logo">
  <img src="img/logo.jpg" alt="Majestic Media">
  <h1><img src="img/help.jpg" alt=""></h1>
</div>
<section>
  <div id="underlines"></div>
  <?= $this->Form->create('Submission', array(
  	'novalidate' => true
  )) ?>

  <?= $this->Form->input('name', array(
  	'Name'
  )); ?>

  <?= $this->Form->input('company', array(
  	'label' => 'Company'
  )) ?>

  <?= $this->Form->input('email', array(
  	'label' => 'E-Mail Address'
  )) ?>

  <?= $this->Form->input('phone', array(
  	'label' => 'Contact Phone'
  )) ?>

  <?= $this->Form->input('body', array(
  	'label' => 'Message'
  )) ?>

  <?= $this->Form->end('Send') ?>
  <div id="underlines"></div>
</section>