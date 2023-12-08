<?php
include_once dirname(__FILE__, 2) . "/lang/lang.php";
?>
<form>
    <label>
        <span><?= str_get_string("text_enter_a_name_file") ?></span>
        <input type="text" placeholder="simple.txt">
    </label>
    <button type="submit"><?= str_get_string("action_create") ?></button>
</form>
