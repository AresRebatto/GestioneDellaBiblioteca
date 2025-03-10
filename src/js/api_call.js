$(document).ready(function() {
    $('#fileInput').on('change', function(event) {
        let file = event.target.files[0];
        if (file) {
            handleFile(file);
        }
    });
});

function handleFile(file) {
    let formData = new FormData();
    formData.append("file", file);

    fetch("http://127.0.0.1:5000/get_book_info", {
        method: "POST",
        body: formData,
        headers: {
            "Accept": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            window.location.assign("../inserimento_libri/inserimento_fallito.php");
        }
        return response.json();
    })
    .then(data => {
        console.log($("#sede").val());
        fetch("../../helpers/insert_book_from_isbn.php", {
            method: "POST",
            body: JSON.stringify({
                ...data,
                sede: $("#sede").val(),
            }),
            headers: {
                "Content-Type": "*/*"
            }
        })
        .then(response => response.text())
        .then(data => {
            if (data['message'] == "success") {
                window.location.assign("../inserimento_libri/inserimento_successo.php");
            } else {
                window.location.assign("../inserimento_libri/inserimento_fallito.php");
            }
        })
    })
    .catch(error => {
        alert("Errore nella comunicazione con l'API");
    });
}