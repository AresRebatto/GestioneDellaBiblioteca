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
    fetch("http://127.0.0.1:5000/get_book_info",{
        method:"POST",
        body: formData.append("file", file)
    })
    .then(response => response.json)
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        alert("Errore nella comunicazione con l'API");
    });
    console.log('File caricato:', file);
}