
  <style>
    
    .input-fecha {
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      padding: 0.375rem 0.75rem;
      width: 100%;
      font-size: 1rem;
      color: #495057;
      height: 38px !important;
    }
    /* Estilos adicionales para pulir la card */
    .card-post {
      border: 0;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(20, 20, 20, 0.08);
      overflow: hidden;
    }

    .card-post .meta {
      color: #6c757d;
      font-size: 0.9rem;
      gap: .6rem;
    }

    .author-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
    }

    .post-thumb {
      width: 120px;
      height: 80px;
      object-fit: cover;
      border-radius: 6px;
      display: block;
      margin-top: 1.75rem;
    }

    /* Ajustes responsivos: en pantallas pequeñas la miniatura queda debajo */
    @media (max-width: 576px) {
      .post-content-row {
        flex-direction: column;
      }
      .post-thumb {
        width: 100%;
        height: 200px;
        margin-top: 0.75rem;
      }
    }
    .modal-body .cke {
        width: 100% !important;
    }

    #respuesta {
        width: 100% !important;
        overflow: hidden;
    }

    .btn-responder {
    background-color: #007bff;     /* Azul bootstrap */
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 20px;           /* Bordes redondeados */
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
    transition: 0.2s ease-in-out;
}

.btn-responder:hover {
    background-color: #0056b3;     /* Azul más oscuro */
}

  </style>