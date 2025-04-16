function llenarModalEditar(id, fechaEvento, horaInicio, horaFin, areaSolicitante, nombreEvento, nombreSolicitante, contactoSolicitante, comentarios) {
        document.getElementById('editId').value = id;
        document.getElementById('editFechaEvento').value = fechaEvento;
        document.getElementById('editHoraInicio').value = horaInicio;
        document.getElementById('editHoraFin').value = horaFin;
        document.getElementById('editAreaSolicitante').value = areaSolicitante;
        document.getElementById('editNombreEvento').value = nombreEvento;
        document.getElementById('editNombreSolicitante').value = nombreSolicitante;
        document.getElementById('editContactoSolicitante').value = contactoSolicitante;
        document.getElementById('editComentarios').value = comentarios;
    }