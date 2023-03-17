$(document).ready(function () {
    // Create a MutationObserver to listen for changes to the DOM tree
    const observer = new MutationObserver(function (mutationsList, observer) {
        // Loop through each mutation
        mutationsList.forEach(function (mutation) {
            // Check if a node was added to the DOM
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // Loop through each added node
                mutation.addedNodes.forEach(function (node) {
                    // Check if the added node is a shipping section
                    if ($(node).is('#shipping-section')) {
                        let locationsContainer = $('#paczkomaty_locations_dropdown');

                        // Add a change event listener to the radio button
                        $('input[type="radio"][name="shipping_method"]').on('change', function () {
                            selectedShipping = $(this).val();

                            // Check if the radio button is clicked
                            if ($(this).is(':checked') && selectedShipping === 'bagistoinpostshipping_bagistoinpostshipping') {
                                // Handle the event
                                locationsContainer.show();
                                handlePaczkomatyLocations();
                                $('#payment-section').hide();
                                $('#summary-section').hide();
                            } else {
                                locationsContainer.hide();
                                $('#payment-section').show();
                                $('#summary-section').show();
                            }
                        })
                    }

                    if ($(node).is('#payment-section') && selectedShipping === 'bagistoinpostshipping_bagistoinpostshipping') {
                        $('#payment-section').hide();
                    }
                })
            }
        })
    })

    const baseUrl = window.location.origin;

    let selectedShipping = '';

    // Start observing the DOM tree for changes
    observer.observe(document.body, {childList: true, subtree: true});

    /**
     * Get InPost Paczkomaty locations based on user input (keyup function).
     * If item is selected or inserted manually (on input function), and it matches item in datalist
     * save it as new shipping address.
     */
    function handlePaczkomatyLocations() {
        let locationsInput = $('#paczkomaty_locations_search');
        let selectedValue = null;

        locationsInput.flexdatalist({
            minLength: 0,
            searchIn: ['name', 'address', 'post_code', 'city'],
            url: `${baseUrl}/bagistoinpostshipping/locations-query/`,
            dataType: 'json',
            valueProperty: ['name', 'address', 'post_code', 'city'],
            render: function (data) {
                return `<option value="${data.name}" data-address="${data.address}" data-post-code="${data.post_code}" data-city="${data.city}">${data.name}, ${data.address}, ${data.post_code}, ${data.city}</option>`;
            },
            visibleProperties: ['name', 'address', 'post_code', 'city'],
            noResultsText: 'Brak wyników wyszukiwania...'
        });

        $('#paczkomaty_locations_search-flexdatalist').on('click focus', function() {
            $(this).val('');
        });

        $('#paczkomaty_locations_search-flexdatalist').on('blur', function () {
            // If no new value is selected, insert the saved one
            if (selectedValue != null) {
                locationsInput.flexdatalist('value', selectedValue);
                $('#paczkomaty_locations_search-flexdatalist').val(selectedValue);
            }
        });

        locationsInput.on('select:flexdatalist', function(event, data) {
            selectedValue = data.name;
            setDeliveryLocation(data.name);
            $('#payment-section').show();
            $('#summary-section').show();
        });
    }

    /**
     * Set selected location as new shipping address.
     */
    function setDeliveryLocation(location) {
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            url: `${baseUrl}/bagistoinpostshipping/location-selected/${location}`,
            type: "GET",
            success: function (response) {
                if ($('.shipping-address').length > 0) {
                    let shippingAddressInfo = $('.shipping-address .card-content ul').children('li');
                    shippingAddressInfo[1].innerHTML = `${response.first_name} ${response.last_name}`;
                    shippingAddressInfo[2].innerHTML = `${response.address1}`;
                    shippingAddressInfo[3].innerHTML = `${response.postcode} ${response.city}`;
                    shippingAddressInfo[4].innerHTML = `${response.state}`;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
});