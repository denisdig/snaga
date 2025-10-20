document.addEventListener('DOMContentLoaded', function() { // la page html soit totalement chargée
    const btnSup = document.querySelector(".btnsup");

    if (btnSup) { // si il y a bien le btnsup
        btnSup.addEventListener('click', function(event) {
            const avertissement = confirm("Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.");
            if (!avertissement) {
                event.preventDefault();
            }
        });
    }
});