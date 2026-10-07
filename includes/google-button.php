<?php
/** google-button.php — expects $googleLabel (string) */
?>
<button type="submit" name="google" value="1" class="btn btn--google" formnovalidate>
    <img src="assets/img/google.svg" alt="" width="20" height="20">
    <span><?= e($googleLabel) ?></span>
</button>
