
<?php foreach($itemData as $key => $value): ?>

    <tr>
        <th style="width: 40%; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box;">
            {{$key}}
        </th>
        <td style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;" bgcolor="#eee">

            <b style="{{($oldData[$key] != $newData[$key]) ? "color:red" : ""}}">{{$value}}</b>

        </td>
    </tr>

<?php endforeach; ?>





