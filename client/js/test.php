<html>
<head><title>PHP Test</title></head>
<?php $a = 1; ?>
<body>
<?php
function div($a, $b) {
    for($i = 0; $i < 10; $i++) {
        if($b % $a == 0)
            echo "$a<br/>";
        }
    }
$a = 100;
$b = 20;
div($b, $a)
?>
</body>
</html>
