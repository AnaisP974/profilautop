document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.getElementById('csrf-token-container').getAttribute('data-csrf-token');

    const dropzones = [
        { id: 'dropzone1', status: 'À postuler' },
        { id: 'dropzone2', status: 'En attente' },
        { id: 'dropzone3', status: 'Entretien' },
        { id: 'dropzone4', status: 'Refusé' },
        { id: 'dropzone5', status: 'Accepté' }
    ];

    function handleDragStart(event) {
        event.dataTransfer.setData('text/plain', event.target.id);
    }

    function handleDragOver(event) {
        event.preventDefault();
    }

    function handleDrop(event) {
        event.preventDefault();
        const data = event.dataTransfer.getData('text');
        const draggedElement = document.getElementById(data);
        const dropzone = event.currentTarget;
        
        dropzone.appendChild(draggedElement);

        const jobOfferId = data.split('_')[1];
        const newStatus = dropzones.find(zone => zone.id === dropzone.id).status;

        updateJobStatus(jobOfferId, newStatus);
    }

    function updateJobStatus(jobOfferId, newStatus) {
        console.log('Envoi du statut:', newStatus);
        fetch('/update-job-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
                jobOfferId: jobOfferId,
                newStatus: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Statut mis à jour avec succès', { jobOfferId, newStatus });
                window.location.reload(); // Rafraichir la page
            } else {
                console.error('Erreur lors de la mise à jour du statut:', data.message);
            }
        })
        .catch(error => console.error('Erreur lors de la requête:', error));
    }

    document.querySelectorAll('[draggable="true"]').forEach(element => {
        element.addEventListener('dragstart', handleDragStart);
    });

    dropzones.forEach(zone => {
        const dropzone = document.getElementById(zone.id);
        dropzone.addEventListener('dragover', handleDragOver);
        dropzone.addEventListener('drop', handleDrop);
    });
});