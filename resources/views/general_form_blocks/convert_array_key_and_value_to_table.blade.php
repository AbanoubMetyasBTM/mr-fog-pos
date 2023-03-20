<?php
/**
 * @var array $arr
 */
?>
<table>
    <?php foreach($arr as $key=>$item): ?>
        <tr>
            <td>{{capitalize_string($key)}}</td>
            <td>{{$item}}</td>
        </tr>
    <?php endforeach; ?>
</table>
