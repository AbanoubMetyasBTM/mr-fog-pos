<?php
/**
 * @var \Illuminate\Support\Collection $report_sold_products
 */
?>


<?php if(is_object($report_sold_products) && count($report_sold_products)): ?>

    <table id="datatable2" class="table display ">
        <thead>
            <tr>
                <th class="wd-15p"><span>#</span></th>
                <th class="wd-15p"><span>Product name</span></th>
                <th class="wd-15p"><span>Quantity</span></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($report_sold_products as $key => $item): ?>
                <tr id="row{{$key+1}}">
                    <td>
                        {{$key+1}}
                    </td>
                    <td>
                        {{$item->item_name}}
                    </td>
                    <td>
                        {{$item->order_quantity_sum}}
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

<?php else : ?>

    @include('global_components.no_results_found')

<?php endif; ?>
