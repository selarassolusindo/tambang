<?php

$string = "<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel=\"stylesheet\" href=\"<?php //echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>\"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style=\"margin-top:0px\">".ucfirst($table_name)." <?php //echo \$button ?></h2>
        <form action=\"<?php echo \$action; ?>\" method=\"post\">";
foreach ($non_pk as $row) {
    if ($row["data_type"] == 'text')
    {
    $string .= "\n\t\t\t<div class=\"form-group\">
            \t<label for=\"".$row["column_name"]."\">".label(strtoupper($row["column_name"]))." <?php echo form_error('".$row["column_name"]."') ?></label>
            \t<textarea class=\"form-control\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label(strtoupper($row["column_name"]))."\"><?php echo $".$row["column_name"]."; ?></textarea>
        \t</div>";
    } else
    {
    $string .= "\n\t\t\t<div class=\"form-group\">
            \t<label for=\"".$row["data_type"]."\">".label(strtoupper($row["column_name"]))." <?php echo form_error('".$row["column_name"]."') ?></label>
            \t<input type=\"text\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label(strtoupper($row["column_name"]))."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
        \t</div>";
    }
}
$string .= "\n\t\t\t<input type=\"hidden\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";
$string .= "\n\t\t\t<button type=\"submit\" class=\"btn btn-primary\"><?php echo \$button ?></button> ";
$string .= "\n\t\t\t<a href=\"<?php echo site_url('".$c_url."') ?>\" class=\"btn btn-secondary\">Batal</a>";
$string .= "\n\t\t</form>
    </body>
</html>";

$hasil_view_form = createFile($string, $target."views/" . $c_url . "/" . $v_form_file);

?>
