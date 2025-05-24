const tableEmptyRow = '<tr><td colspan="8" class="text-center"><i>Item is empty</i></td></tr>'
const select2Params = {
  theme: 'bootstrap-5',
  placeholder: 'Find Product',
  ajax: {
    url: '/sales-purchases/products', // ganti dengan URL kamu
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
      return {
        results: data.map(item => ({
          id: item.id,
          text: item.name,
          prices: item.prices
        }))
      };
    },
    cache: true
  }
}

function calculateRow(row) {
    const qty = row.find('.qty').val().replaceAll(',','') || 0;
    const basePrice = row.find('.base_price').val().replaceAll(',','') || 0;
    const discount = row.find('.discount').val() || 0;

    const subtotal = qty * basePrice;
    const finalPrice = subtotal - discount;

    row.find('.subtotal').val(subtotal.toFixed(2));
    row.find('.subtotal-label').html(subtotal.toFixed(2));
    row.find('.final_price').val(finalPrice.toFixed(2));
    row.find('.final_price-label').html(formatNumber(finalPrice.toFixed(2)));

    calculateTotal()
}

function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function updateRowNumbers() {
    $('.table-invoice-item tbody tr').each(function(index) {
        $(this).find('td:first').text(index + 1);
    });

    if($('.table-invoice-item tbody tr').length == 0)
    {
        $('.table-invoice-item tbody').append(tableEmptyRow)
        items = 0
    }

    calculateTotal()
}

function isDuplicateCombination(currentRow) {
  const currentProduct = currentRow.find('.product-select').val();
  const currentUnit = currentRow.find('.unit').val();
  let duplicate = false;

  $('tr').each(function () {
    const row = $(this);
    if (row.is(currentRow)) return; // lewati baris sendiri

    const product = row.find('.product-select').val();
    const unit = row.find('.unit').val();

    if (product === currentProduct && unit === currentUnit) {
      duplicate = true;
      return false; // break loop
    }
  });

  return duplicate;
}

function initAutoNumericFields(row) {
    row.find('.autonumeric').each(function () {
      setAutoNumeric(this);
    });
}

function setAutoNumeric(el){
    new AutoNumeric(el, {
        digitGroupSeparator: ',',
        decimalCharacter: '.',
        decimalPlaces: 0,
        unformatOnSubmit: true,
    })
}

function calculateTotal() {
  let total = 0;
  let qty = 0;

  $('.final_price').each(function () {
    const anFinalPrice = AutoNumeric.getAutoNumericElement(this);
    if (anFinalPrice) {
      total += anFinalPrice.getNumber(); // Ambil angka plain
    } else {
      total += parseFloat($(this).val()) || 0;
    }
  });
  
  $('.qty').each(function () {
    const allQty = AutoNumeric.getAutoNumericElement(this);
    if (allQty) {
      qty += allQty.getNumber(); // Ambil angka plain
    } else {
      qty += parseFloat($(this).val()) || 0;
    }
  });

  // Tampilkan hasil total di elemen tertentu (misal: #total)
  const anTotal = AutoNumeric.getAutoNumericElement($('[name=total_price]')[0]);
  if (anTotal) {
    anTotal.set(total);
  } else {
    $('[name=total_price]').val(total.toFixed(2));
  }

  const anQty = AutoNumeric.getAutoNumericElement($('[name=total_qty]')[0]);
  if (anQty) {
    anQty.set(qty);
  } else {
    $('[name=total_qty]').val(qty.toFixed(2));
  }
  
  const anItem = AutoNumeric.getAutoNumericElement($('[name=total_item]')[0]);
  if (anItem) {
    anItem.set($('tr.item').length);
  } else {
    $('[name=total_item]').val($('tr.item').length.toFixed(2));
  }
  
  const anDiscount = AutoNumeric.getAutoNumericElement($('[name=invoice_discount]')[0]);
  var invoice_discount = 0
  if (anDiscount) {
    invoice_discount = anDiscount.getNumber() ?? 0;
  } else {
    invoice_discount = $('[name=invoice_discount]').val() ?? 0;
  }

  const anTotalFinalPrice = AutoNumeric.getAutoNumericElement($('[name=final_price]')[0]);
  if(anTotalFinalPrice)
  {
    anTotalFinalPrice.set(total - invoice_discount)
  }
  else
  {
    $('[name=final_price]').val(total - invoice_discount)
  }

}

setAutoNumeric(document.querySelector('[name=total_item]'))
setAutoNumeric(document.querySelector('[name=total_qty]'))
setAutoNumeric(document.querySelector('[name=total_price]'))
setAutoNumeric(document.querySelector('[name=invoice_discount]'))
setAutoNumeric(document.querySelector('[name=final_price]'))
if(document.querySelector('.autonumeric'))
{
    document.querySelectorAll('.autonumeric').forEach(el => setAutoNumeric(el))
}

$('.btn-add-item').click(function () {
    if(window.items == 0)
    {
        $('.table-invoice-item tbody').html('')
    }

    const items = window.items

    const newRow = $(`
        <tr class="item" data-index="${items}">
            <td></td>
            <td>
                <select name="items[${items}][product_id]" class="form-control form-select product-select"></select>
            </td>
            <td><input type="text" name="items[${items}][qty]" class="qty form-control autonumeric" value="1"></td>
            <td>
                <select class="form-control form-select unit" name="items[${items}][unit]"></select>
            </td>
            <td><input type="text" name="items[${items}][base_price]" class="base_price form-control autonumeric" value=""></td>
            <td>
                <input type="hidden" name="items[${items}][total_discount]" value="0" class="discount">
                <input type="hidden" name="items[${items}][final_price]" class="final_price">
                <span class="final_price-label final_price-${items} autonumeric"></span>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm removeRowBtn"><i class="bx bx-trash"></i></button></td>
        </tr>
    `);

    // Tambahkan event handler untuk input perubahan
    newRow.find('input').on('input', function () {
        calculateRow(newRow);
    });

    // Tambahkan event handler untuk tombol hapus
    newRow.find('.removeRowBtn').on('click', function () {
        newRow.remove();
        updateRowNumbers();
    });

    newRow.find('.product-select').select2(select2Params)

    newRow.find('.product-select').on('select2:select', function (e) {
        const prices = e.params.data.prices;
        const unitSelect = newRow.find('.unit');
        unitSelect.empty(); // kosongkan
        prices.forEach(price => {
            unitSelect.append(
                `<option value="${price.unit}" data-amount="${price.amount_1}">${price.unit}</option>`
            );
        });

        if (prices.length > 0) {
            unitSelect.val(prices[0].unit).trigger('change');
        }

        setTimeout(() => {
            if (isDuplicateCombination(newRow)) {
            alert('Product already selected!');
            newRow.find('.product-select').val(null).trigger('change');
            newRow.find('.unit').empty();
            }
        }, 200);
    });

    newRow.find('.unit').on('change', function () {
        if (isDuplicateCombination(newRow)) {
            alert('Product already selected!');
            $(this).val('').trigger('change');
            return
        }
        const selectedOption = $(this).find(':selected');
        const price = parseFloat(selectedOption.data('amount')) || 0;

        const base_price = AutoNumeric.getAutoNumericElement(newRow.find('.base_price')[0]);
        base_price.set(price)
        calculateRow(newRow);
    });

    $('.table-invoice-item tbody').append(newRow);
    calculateRow(newRow);
    updateRowNumbers();
    initAutoNumericFields(newRow);
    window.items++
});

$('[name=invoice_discount]').on('keyup', calculateTotal)

$(document).ready(function(){
    $('.product-select').select2(select2Params)
    $('.product-select').on('select2:select', function (e) {
        const prices = e.params.data.prices;
        const newRow = $(this).parent().parent()
        const unitSelect = newRow.find('.unit');
        unitSelect.empty(); // kosongkan
        prices.forEach(price => {
            unitSelect.append(
                `<option value="${price.unit}" data-amount="${price.amount_1}">${price.unit}</option>`
            );
        });
    
        if (prices.length > 0) {
            unitSelect.val(prices[0].unit).trigger('change');
        }
    
        setTimeout(() => {
            if (isDuplicateCombination(newRow)) {
                alert('Product already selected!');
                newRow.find('.product-select').val(null).trigger('change');
                newRow.find('.unit').empty();
            }
        }, 200);
    });
    
    $('.unit').on('change', function () {
        const newRow = $(this).parent().parent()
        if (isDuplicateCombination(newRow)) {
            alert('Product already selected!');
            $(this).val('').trigger('change');
            return
        }
        const selectedOption = $(this).find(':selected');
        const price = parseFloat(selectedOption.data('amount')) || 0;
    
        const base_price = AutoNumeric.getAutoNumericElement(newRow.find('.base_price')[0]);
        base_price.set(price)
        calculateRow(newRow);
    });
    
    $('.removeRowBtn').on('click', function () {
        const newRow = $(this).parent().parent()
        newRow.remove();
        updateRowNumbers();
    });
})