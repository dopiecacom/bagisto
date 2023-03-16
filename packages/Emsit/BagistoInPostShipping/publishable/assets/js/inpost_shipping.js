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
                        let locationsInput = $('#paczkomaty_locations_search');

                        defaultInput = locationsInput.val();

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
                                clearInput = false;
                                locationsInput.val(defaultInput);
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

    let clearInput = false;
    let defaultInput = '';
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
        let locationsDatalist = $('#paczkomaty_locations');

        locationsInput.click(function () {
            if (clearInput === false) {
                locationsInput.val('');
                clearInput = true;
            }
        });

        locationsInput.keyup(function () {
            $.ajaxSetup({
                headers:
                    {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $.ajax({
                url: `${baseUrl}/bagistoinpostshipping/locations-query/${$(this).val()}`,
                type: "GET",
                success: function (response) {
                    locationsDatalist.empty();
                    $.each(response, function (key, value) {
                        locationsDatalist.append(`
                            <option value="[${value['name']}] ${value['address']} ${value['post_code']} ${value['city']}">
                            </option>`);
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

        locationsInput.on('input', function () {
            let val = this.value;
            if ($('#paczkomaty_locations option').filter(function () {
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                setDeliveryLocation();
                $('#payment-section').show();
                $('#summary-section').show();
            }
        });
    }

    /**
     * Set selected location as new shipping address.
     */
    function setDeliveryLocation() {
        let location = $('#paczkomaty_locations_search').val();

        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            url: `${baseUrl}/bagistoinpostshipping/location-selected/${location}`,
            type: "GET",
            success: function (response) {
                //
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
});