<?php
// src/Enum/JobStatus.php
namespace App\Enum;

enum JobStatus: string
{
    case A_POSTULER = "À postuler";
    case EN_ATTENTE = "En attente";
    case ENTREVUE = "Entretien";
    case REFUSE = "Refusé";
    case ACCEPTE = "Accepté";
}
