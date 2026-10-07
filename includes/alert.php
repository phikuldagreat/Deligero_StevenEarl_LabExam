<?php
/**
 * alert.php — renders one message box.
 * Expects $alert = ['type' => 'error'|'success'|'info', 'message' => '...'] or null.
 */
if (!empty($alert)):
    $role = $alert['type'] === 'error' ? 'alert' : 'status';
?>
<div class="alert alert--<?= e($alert['type']) ?>" role="<?= $role ?>">
    <?= e($alert['message']) ?>
</div>
<?php endif; ?>
