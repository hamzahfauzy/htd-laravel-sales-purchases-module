$(document).ready(function(){
    var unitPlaceholder = $('#unit').html()
    $('#product_id').change(function(){
        const value = $(this).val()

        // load units
        fetch('/items/' + value + '/get-units').then(res => res.json())
        .then(res => {
            $('#unit').html('')
            $('#unit').append(unitPlaceholder)
            res.data.forEach(data => {
                var newOption = `<option value="${data}">${data}</option>`
                $('#unit').append(newOption)
            })
        })
    })
})