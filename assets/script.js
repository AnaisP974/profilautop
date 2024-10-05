// Gérer l'événement de début de glissement
document.querySelectorAll('[draggable="true"]').forEach(draggableElement => {
    draggableElement.addEventListener('dragstart', function(event) {
        event.dataTransfer.setData('text', event.target.id);  // Enregistrer l'ID de l'élément
    });
});

// Liste des dropzones
let dropzones = ['dropzone1', 'dropzone2', 'dropzone3', 'dropzone4', 'dropzone5'];

// Fonction pour gérer le dragover
function handleDragOver(event) {
    event.preventDefault();  // Empêche le comportement par défaut pour autoriser le drop
}

// Fonction pour gérer le dépôt
function handleDrop(event) {
    event.preventDefault();
    let data = event.dataTransfer.getData('text');  // Récupérer l'ID de l'élément déplacé
    let element = document.getElementById(data);
    event.target.appendChild(element);  // Ajouter l'élément dans la zone de dépôt
}

// Attacher les événements aux dropzones
dropzones.forEach(function(zoneId) {
    let dropzone = document.getElementById(zoneId);
    dropzone.addEventListener('dragover', handleDragOver);
    dropzone.addEventListener('drop', handleDrop);
});