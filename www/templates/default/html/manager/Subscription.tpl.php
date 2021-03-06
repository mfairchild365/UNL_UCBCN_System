<?php
	$crumbs = new stdClass;
	$crumbs->crumbs = array(
		"Events Manager" => "/manager",
		$context->calendar->name => $context->calendar->getManageURL(),
		"Subscriptions" => NULL
	);
	echo $savvy->render($crumbs, 'BreadcrumbBar.tpl.php');
?>

<?php if (count($context->getSubscriptions()) > 0): ?>
<?php foreach($context->getSubscriptions() as $subscription): ?>
<form id="delete-subscription-<?php echo $subscription->id ?>" method="POST" action="<?php echo $subscription->getDeleteURL() ?>" class="delete-form hidden">
    <input type="hidden" name="subscription_id" value="<?php echo $subscription->id ?>" />
    <button type="submit">Submit</button>
</form>
<?php endforeach; ?>
<h1 class="wdn-brand">
	Current Subscriptions
</h1>
<div>
	<table>
		<thead>
			<tr>
				<th>Title</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
	    <?php foreach($context->getSubscriptions() as $subscription): ?>
			<tr>
				<td>
		        	<?php echo $subscription->name; ?>
				</td>
				<td class="small-center table-actions">
			        <a class="wdn-button wdn-button-brand" href="<?php echo $subscription->getEditURL() ?>">Edit</a>
			        <span class="small-hidden">|</span><br class="hidden small-block"><br class="hidden small-block">
		            <button class="wdn-button wdn-button-triad" form="delete-subscription-<?php echo $subscription->id ?>" type="submit">Delete</button>
				</td>
			</tr>
	    <?php endforeach; ?>
		</tbody>
	</table>
</div>
<br>
<?php else: ?>
	<br>
	<label>There are currently no subscriptions on this calendar.</label>
	<br>
<?php endif; ?>

<br>
<a href="<?php echo $base_manager_url . $context->calendar->shortname ?>/subscriptions/new/" 
	class="wdn-button wdn-button-brand">Add Subscription
</a>
