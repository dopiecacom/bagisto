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
                        // Add a change event listener to the radio button
                        $('input[type="radio"][name="shipping_method"]').on('change', function () {
                            selectedShipping = $(this).val();

                            // Check if the radio button is clicked
                            if ($(this).is(':checked') && selectedShipping === 'bagistoinpostshipping_bagistoinpostshipping') {
                                // Handle the event
                                $('#paczkomaty_locations_dropdown').show();
                                handlePaczkomatyLocations()
                            } else {
                                $('#paczkomaty_locations_dropdown').hide();
                            }
                        })
                    }
                })
            }
        })
    })

    var selectedShipping = '';

    // Start observing the DOM tree for changes
    observer.observe(document.body, {childList: true, subtree: true});

    function handlePaczkomatyLocations() {
        $('#paczkomaty_locations_search').keyup(function () {
            $.ajaxSetup({
                headers:
                    {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $.ajax({
                url: `http://localhost:8000/bagistoinpostshipping/locations-query/${$(this).val()}`,
                type: "GET",
                success: function (response) {
                    $('#paczkomaty_locations').empty();
                    $.each(response, function (key, value) {
                        //$('#paczkomaty_locations').append('<option value="[' + value['name'] + '] ' + value['address'] + '"></option>');
                        $('#paczkomaty_locations').append(`<option value="[${value['name']}] ${value['address']} ${value['post_code']} ${value['city']}"></option>`);
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

        $("#paczkomaty_locations_search").on('input', function () {
            let val = this.value;
            if($('#paczkomaty_locations option').filter(function(){
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                setDeliveryLocation();
            }
        });
    }

    function setDeliveryLocation() {
        let location = $('#paczkomaty_locations_search').val();

        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            url: `http://localhost:8000/bagistoinpostshipping/location-selected/${location}`,
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