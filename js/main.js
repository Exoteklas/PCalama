//Documento para control de vistas 

// Desactiva todas las paginas cuyo ID comienza con "page"
function blankPage() {
  // Selecciona todos los elementos cuyo ID comienza con "page"
  var paginas = document.querySelectorAll('[id^=page]');

  // Itera sobre cada página y las oculta cambiando su estilo de visualización a "none"
  paginas.forEach(page => {
    page.style.display = 'none';
  });
}

// Abre el modal especificado
// modal: nombre del modal
function openModal(modal){
  // Muestra el overlay y el modal correspondiente cambiando su estilo de visualización a "block"
  document.getElementById(modal+'-overlay').style.display = 'block';
  document.getElementById(modal+'-modal').style.display = 'block';
}

// Cierra el modal especificado
// modal: nombre del modal
function closeModal(modal){
  // Oculta el overlay y el modal correspondiente cambiando su estilo de visualización a "none"
  document.getElementById(modal+'-overlay').style.display = 'none';
  document.getElementById(modal+'-modal').style.display = 'none';
}

// Inicializa la pagina en vista general dependiendo del estado de la sesión
if(getCookie('jwt')){
  parking(); // Si hay una cookie de sesión, muestra la página de parking
} else {
  nosotros();// Si no hay una cookie de sesión, muestra la página de nosotros
}

// Oculta el elemento de pantalla de carga
document.getElementById('loadingscreen').style.display = 'none';

// Abre el modal de inicio de sesión
function openLoginModal() {
  document.getElementById('login-modal').style.display = 'block';
  document.getElementById('modal-overlay').style.display = 'block';
}

// Cierra el modal de inicio de sesión
function closeLoginModal() {
  document.getElementById('login-modal').style.display = 'none';
  document.getElementById('modal-overlay').style.display = 'none';
}

// Muestra la página "Nosotros" y oculta las demás
function nosotros(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageNosotros').style.display = 'block';
}
// Muestra la página "Parking" y oculta las demás
function parking(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageParking').style.display = 'block';
}
// Muestra la página "Contacto" y oculta las demás
function contacto(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageContacto').style.display = 'block';
}
// Muestra la página "Movimientos" y oculta las demás
function entradaSalida(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageMovimientos').style.display = 'block';
}
// Muestra la página "Pagos" y oculta las demás
function pagos(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pagePagos').style.display = 'block';
}
// Muestra la página "Enrolar" y oculta las demás
function enrolar(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageEnrolar').style.display = 'block';
}
// Muestra la página "Whitelist" y oculta las demás
function listablanca(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageWhitelist').style.display = 'block';
}
// Muestra la página "Buses" y oculta las demás
function buses(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageBuses').style.display = 'block';
}
// Muestra la página "Empresas" y oculta las demás
function empresas(){
  blankPage();// Oculta todas las páginas
  document.getElementById('pageEmpresas').style.display = 'block';
}