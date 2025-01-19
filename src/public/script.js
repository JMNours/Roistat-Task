let addField = false;
let countdown = 30;

window.onload = function() {

    setTimeout(function () {
        addField = true;
    }, countdown * 1000);

    let form  = document.getElementById("sendLeadForm");
        
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        let formData = new FormData(form);
        
        if (addField) {
            formData.append('additional', true);
        }
        
        sendRequest(formData);
    })
}

function sendRequest(formData) {
    fetch("/amo-add-leads.php", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
    })
    .then(data => {
        console.log("Response from server:", data);
    })
    .catch(error => {
        console.error("Error sending request:", error);
    });
}