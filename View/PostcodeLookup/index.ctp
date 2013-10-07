<div class="clients index">
	<h2><?php echo __('Addresses'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('line_1'); ?></th>
			<th><?php echo $this->Paginator->sort('line_2'); ?></th>
			<th><?php echo $this->Paginator->sort('line_7'); ?></th>
			<th><?php echo $this->Paginator->sort('postcode'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($Addresses as $address): ?>
	<tr>
		<td class="name"><?php echo h($address['PostcodeLookup']['line_1']); ?>&nbsp;</td>
		<td class="name"><?php echo h($address['PostcodeLookup']['line_2']); ?>&nbsp;</td>
		<td class="name"><?php echo h($address['PostcodeLookup']['line_7']); ?>&nbsp;</td>
		<td class="name"><?php echo h($address['PostcodeLookup']['postcode']); ?>&nbsp;</td>
		<td class="address">
<?php
	echo $this->Html->link($client['Address']['line_1'], array('controller' => 'addresses', 'action' => 'view', $client['Address']['id']));
	if($client['Address']['line_2'])
		echo ',<br />' . $client['Address']['line_2'];
	if($client['Address']['postal_code'])
		echo ',<br />' . $client['Address']['postal_code'];
?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $client['Client']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $client['Client']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $client['Client']['id']), null, __('Are you sure you want to delete # %s?', $client['Client']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
