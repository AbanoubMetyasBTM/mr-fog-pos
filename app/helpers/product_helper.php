<?php


function calculateProductTotalItemsQty($oldTotalItemsQty, $standardBoxQty, $boxesQty, $itemsQty, $operation)
{
    // operation => increase, decrease

    $itemsQtyInBoxes = intval($standardBoxQty) * intval($boxesQty);
    $allItemsQty     = intval($itemsQtyInBoxes) + intval($itemsQty);

    if ($operation == 'increase'){
        $newTotalItemsQty =  intval($oldTotalItemsQty) + intval($allItemsQty);
    }
    else{
        $newTotalItemsQty =  intval($oldTotalItemsQty) - intval($allItemsQty);
    }

    return $newTotalItemsQty;
}

function calculateProductBoxesAndItemsAfterUsed($standardBoxQty, $oldBoxesQty, $oldItemsQty, $boxesQtyWillBeUsed, $itemsQtyWillBeUsed)
{


    if($itemsQtyWillBeUsed > $oldItemsQty && $boxesQtyWillBeUsed < $oldBoxesQty) {
        $availableBoxesToOpen        = intval($oldBoxesQty) - intval($boxesQtyWillBeUsed);
        $allItemsInBoxes             = intval($standardBoxQty) * $availableBoxesToOpen;
        $allItemsAfterDecreaseBoxQty = intval($allItemsInBoxes) + intval($oldItemsQty);
        $newItemsQty                 = $allItemsAfterDecreaseBoxQty - intval($itemsQtyWillBeUsed);
        $newBoxesQty                 = $newItemsQty > $standardBoxQty  ? intval($newItemsQty /  intval($standardBoxQty)) : 0;
    }
    else{
        $newBoxesQty = intval($oldBoxesQty) - intval($boxesQtyWillBeUsed);
        $newItemsQty = intval($oldItemsQty) - intval($itemsQtyWillBeUsed);
    }

    return [
        'new_boxes_qty' => $newBoxesQty,
        'new_items_qty' => $newItemsQty
    ];

}

//TODO mets should remove this
function productNameBindingWithVariantValueNameHandler($items){


    foreach ($items as $item){
        $item->product_name = $item->product_name .' [ ' . $item->ps_selected_variant_type_values_text . ' ]';
    }
    return $items;

}
