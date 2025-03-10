$("#search").autocomplete({
    source: function (request, response) {
        fetch("../helpers/autocomplete.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ term: request.term })
        })
        .then(res => res.json())
        .then(data => response(data))
        .catch(error => console.error("Errore:", error));
    },
    minLength: 2
});