<table>
    <tr>
        <th><?= $this->Paginator->sort('id', 'ID') ?></th>
        <th>E-mail</th>
        <th>Name</th>
        <th>Phone</th>
        <th><?= $this->Paginator->sort('company', 'Company') ?></th>
        <th><?= $this->Paginator->sort('created', 'Submitted') ?></th>
    </tr>
    <?php foreach ($data as $recipe): ?>
    <tr>
        <td><?= $recipe['Submission']['id'] ?> </td>
        <td><?= h($recipe['Submission']['email']) ?> </td>
        <td><?= h($recipe['Submission']['name']) ?> </td>
        <td><?= h($recipe['Submission']['phone']) ?> </td>
        <td><?= h($recipe['Submission']['company']) ?> </td>
        <td><?= $this->Time->timeAgoInWords($recipe['Submission']['created']) ?> </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
    echo $this->Paginator->numbers();
    echo '<br>';
    echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled'));
    echo ' - ';
    echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled'));
    echo '<br>';
    echo '<br>';
    echo $this->Paginator->counter();
?>