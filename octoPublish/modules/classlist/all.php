<?php
set_time_limit(0);
$classes = eZContentClass::fetchAllClasses();
?>

<!doctype html>
<html>
<head>
    <title>Class list</title>
    <style>
        body {
            font-family: helvetica;
            color:#414342;
            font-size: 14px;
        }
    h2 {
        font-size: 1.1em;
    }
        .quickAcces {
            list-style: none;
            position: fixed;
            right:10px;
            top:10px;
            background: #ccc;
            width: 150px;
            padding:10px;
            font-size: 1em;
        }
        li {
            margin-bottom: 5px;
        }
        .quickAcces a {
            text-decoration: none;
        }
    .classBlock {
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
        .minorInfo {
            font-size: 0.9em;
            color:#666;
        }
    </style>
</head>
<body>

<?php

echo '<ul class="quickAcces">';
foreach ($classes as $class) {
    echo '<li><a href="#' . $class->Identifier . '">' . $class->Identifier . '</a></li>';
}
echo '</ul>';

foreach ($classes as $class) {

    $dm = $class->dataMap();

    echo '<div class="classBlock">';
    echo '<h2 id="' . $class->Identifier . '">' . $class->Identifier . ' (id: ' . $class->ID . ')</h2>';
    echo '<ul>';
    foreach ($dm as $attr) {



        $required = ($attr->IsRequired) ? ' - required' : '';
        echo '<li>' . $attr->Identifier . ' <span class="minorInfo">(id: ' . $attr->ID . ' - type : ' . $attr->DataTypeString  . $required . ')</span></li>';
    }
    echo '</ul>';
    echo '</div>';
}

?>
</body>
</html>


<?php
eZExecution::cleanExit();

?>
