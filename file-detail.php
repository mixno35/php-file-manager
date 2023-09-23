<?php
global $main_path;

include_once "php/data.php";
include_once "lang/lang.php";
include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = trim($_GET["path"]) ?? "";
$id = trim($_GET["id"]) ?? "";
?>

<header class="header">
    <h4>
        <?= is_dir($path) ? str_get_string("text_about_dir") : str_get_string("text_about_file") ?>
    </h4>
    <button onclick="document.getElementById('<?= $id ?>').remove()" title="<?= str_get_string('tooltip_close') ?>">
        <span>&#215;</span>
    </button>
</header>

<div class="container">
    <div class="content">
        <div class="preview">
            <img src="<?= $file_parse->get_icon($path, true) ?>" alt="Image">

            <?php if (is_file($path) && strlen(trim($file_manager->get_file_format($path))) > 0) { ?>
                <span class="format"><?= $file_manager->get_file_format($path) ?></span>
            <?php } ?>
        </div>

        <div class="info">
            <span>
                <label><?= is_dir($path) ? str_get_string("text_about_dir_name") : str_get_string("text_about_file_name") ?></label>
                <?= basename($path) ?>
            </span>
            <span>
                <label><?= str_get_string("text_about_path") ?></label>
                <?= $path ?>
            </span>
            <span>
                <label><?= str_get_string("text_about_size") ?></label>
                <?= is_dir($path) ? $file_manager->format_size($file_manager->get_directory_size($path)) : $file_manager->format_size($file_manager->get_file_size($path)) ?>
            </span>
            <span>
                <label><?= str_get_string("text_about_date_modified") ?></label>
                <?= date("Y-m-d H:i:s", $file_manager->get_date_modified($path)) ?>
            </span>
        </div>
    </div>

    <nav class="bottom-actions">
        <span onclick="run_command().remove('<?= addslashes($path) ?>')">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADd0lEQVR4nO3WS08TURgG4MNaEzdsXCj0fqEttG6Uf2DcSEj8C2DcmBiBdmHd+QvEfaHQu6VMgUK7kzWYKEwBE7WXmc5MWyMuicecGQ7UMqUz0zNIYt/kTSCB6fM1M2c+APrpp5//PLM7jwZmdssDM7tQVV/ulMDMx4f/mg8QRDX+rDvfr14c/HQTzO3fA376CQjQz8HcZwjm9iDw70MQQKVPW+zw8770t/49KP4vuga6Frrmi90bZLGzX26BwMEkCBTfgACdAwH6m4TRq/Rv4C9+lT4LfebBpGjQHD+9py+42L3IoDkBOnsNBqBAr/HmG0e+QhN2qzeP24DerQYcw90872gOtS51ow49uOtS3esCdK8J0J0VDgGp+PLN7SuDr0l1UfwHYgN4C43UBXi+O3x0swWtFJ7F5ZPEBvAVmu9k4Vt6wAXoogTopLh5YgN4C83XWuAeDfARihfrzHBBcgPk69P4NvHK3d89w/kz+MiqVGeGnyI2gC/XnPB2ezBl4G4t8FURDx0Z/jGxAcYK9XFFJwoBuFMsB+0Z9gGxAdxbP4yKj8IWuEsD3LEi1brCGIgN4MkxN1SdKD3AHahpDqLPBCQzul7/perBzJ6fKPJwThZuT3PQluaOAel4NoQjtUehWrgd9X0N2lLsIfkB1oRtxfBWtAq4HeGlAcitETjuLJ/SeqI4FMNrYq1JltwagePKCvM9PZgK4DYRX4OWBPuW/ACUENR6otgVwq1JVmqCfUV+gAw/rRl+ilYAhxbUGDtFfoBVYUIrvB3dEZ5goSUu/k5ujcAZoWrjPcOTl8MtcRaa4yw0xQiuEThuijWqOwrbv+1TeKITnIHmmFRrjOAagYNe7cTgcXm4OcZAU5SBnhDhNQLHvsIda4FbFMJNUQYaI9WfuuDFAdLckYqjUB4ek4ebUCMMNCxXD/UbIMVua4Gb2+HRi3BjpCrWsFQlv0bg2FK1FDF45G+4cVnq8FIlqd8ASXZeC7z92za1oY3LVXTroG8fDUB+jcCxJtggcfhSaytwOFwmv0bgmFPstBa4sRs8jOC4JfJrBI4lyU50evkogRsugy9KHQpVyK8ROOgVrxt8sQKHFlBL93UbwBquDJpj1ROt8OFL4WU4FCqd3A5XBoGeMUWZZ6Yo01AOr3SHL5Th3VC5fidUfqorvp9++gHXLn8A4SXcqAb6QuoAAAAASUVORK5CYII=" alt="Remove">
            <label><?= str_get_string("action_remove") ?></label>
        </span>
    </nav>
</div>

