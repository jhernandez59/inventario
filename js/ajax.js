// Selecciona todos los formularios con la clase FormularioAjax
const formularios_ajax = document.querySelectorAll(".FormularioAjax");

// Función para enviar el formulario cuando se hace clic en el botón "Enviar" (e)
function enviar_formulario_ajax(e) {
  // Evita el envío del formulario de forma predeterminada
  e.preventDefault();

  let enviar = confirm("Quieres enviar el formulario");

  if (enviar == true) {
    let data = new FormData(this);
    let method = this.getAttribute("method");
    let action = this.getAttribute("action");

    let encabezados = new Headers();

    let config = {
      method: method,
      headers: encabezados,
      mode: "cors",
      cache: "no-cache",
      body: data,
    };

    fetch(action, config)
      .then((respuesta) => respuesta.text())
      .then((respuesta) => {
        let contenedor = document.querySelector(".form-rest");
        contenedor.innerHTML = respuesta;
      });
  }
}
// Agrega un event listener a cada formulario con la clase FormularioAjax
formularios_ajax.forEach((formularios) => {
  formularios.addEventListener("submit", enviar_formulario_ajax);
});
