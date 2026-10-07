<?php
/** footer.php — expects optional $pageJs (string[]) */
$pageJs = $pageJs ?? [];
?>
</main>
<?php foreach ($pageJs as $js): ?>
<script src="assets/js/<?= e($js) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
