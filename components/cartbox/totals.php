<?php if ($cartItemsCount) { ?>
    <div class="cart-total">
        <div class="table-responsive">
            <table class="table table-none">
                <tbody>

                <tr>
                    <td>
                    <span class="text-muted">
                        <?= lang('sampoyigi.cart::default.text_sub_total'); ?>:
                   </span>
                    </td>
                    <td class="text-right">
                        <?= currency_format($subTotal = $cartSubtotal); ?>
                    </td>
                </tr>

                <?php foreach ($cartConditions as $id => $condition) { ?>
                    <tr>
                        <td>
                        <span class="text-muted">
                            <?= e($condition->getLabel()); ?>:
                            <?php if ($condition->removeable) { ?>
                                <button
                                    type="button"
                                    class="btn btn-light btn-sm"
                                    data-cart-condition-id="<?= $id; ?>"
                                    data-cart-control="remove-condition"
                                ><i class="fa fa-times"></i></button>
                            <?php } ?>
                       </span>
                        </td>
                        <td class="text-right">
                            <?= is_numeric($result = $condition->calculatedValue())
                                ? currency_format($result)
                                : '--'; ?>
                        </td>
                    </tr>
                <?php } ?>

                <tr>
                    <td>
                    <span class="text-muted">
                        <?= lang('sampoyigi.cart::default.text_order_total'); ?>:
                   </span>
                    </td>
                    <td class="text-right">
                        <?= currency_format($cartTotal); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
