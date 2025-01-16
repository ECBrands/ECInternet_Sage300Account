define([
    'jquery'
], function ($) {
    'use strict';

    log('"invoice_payments.js" loaded!');

    // Cache 'amount' inputs
    const $payInputs    = $("#my-orders-table input[name^='pay']");
    const $amountInputs = $("#my-orders-table input[name^='amount']");

    // Initially disable all 'amount' inputs
    $amountInputs.prop('disabled', true);

    // Add 'change' handler for 'pay' checkboxes
    $payInputs.on('change', function () {
        log("'pay' checkbox changed!");

        // Cache row
        const $row = $(this).parents("tr").first();

        // Cache fields
        const $amountInput = $row.find("input[name^='amount']");
        const $totalDueValue = clean($row.find('.total_due').text().trim());

        // Handle checkbox
        if (this.checked) {
            // Ensure Amount input is enabled
            $amountInput.prop('disabled', false);

            // Set total due if 'amount' is empty so we don't wipe out already-entered values
            if ($amountInput.val() === '') {
                $amountInput.val($totalDueValue);
            }
        } else {
            $amountInput.prop('disabled', true);
            $amountInput.val('');
        }

        // Update total
        setTotal();
    });

    // Add 'change' handler for 'amount' inputs
    $amountInputs.on('input', function () {
        log("'amount' input changed!");

        // Cache row
        const $row = $(this).parents("tr").first();

        // Cache fields
        const $value = $(this).val();
        const $payInput = $row.find("input[name^='pay']");

        // If value is cleared from input, uncheck checkbox
        if ($value === "" || $value === "0") {
            $payInput.prop("checked", false);
            $payInput.change();
        }

        if ('$' + clean($value) !== $value) {
            $(this).val(clean($value));
        }

        setTotal();
    });

    /**
     * Calculate invoice payment total and put value in 'total_value' div.
     */
    function setTotal()
    {
        log("setTotal()");

        // Grab all of the values in the enabled 'amount' inputs
        const $validAmounts = $("#my-orders-table input[name^='amount']:enabled");

        let total = 0.00;

        // Iterate over values and sum the result - Skip if empty
        $validAmounts.each(function (index, element) {
            if (element.value !== "") {
                total = total + parseFloat(clean(element.value));
            }
        });

        // Write total to UI
        $('.total_value').text(Number(total.toFixed(2)).toLocaleString('en'));
    }

    function clean(value)
    {
        return value.replace(/[^0-9_.]/g, '');
    }

    /**
     * Log message with extension prefix.
     *
     * @param message
     */
    function log(message)
    {
        console.log("ECInternet_Sage300Account - " + message);
    }
});
