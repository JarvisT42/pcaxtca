// js/address-handler.js

document.addEventListener('DOMContentLoaded', function () {
    const addressSelector = document.getElementById('addressSelector');
    if (!addressSelector) return;

    addressSelector.addEventListener('change', function () {
        const selectedOption = this.value;
        if (!selectedOption) return;

        try {
            const address = JSON.parse(selectedOption);
            document.getElementById('street_address').value = address.address || '';
            document.getElementById('postcode').value = address.postcode || '';
            document.getElementById('city').value = address.city || '';
            document.getElementById('state').value = address.state || '';
            document.getElementById('phone_number').value = address.phone || '';
        } catch (e) {
            alert("Error parsing address data.");
        }
    });
});
