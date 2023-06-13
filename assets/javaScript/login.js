// Obtenha o elemento .error
var errorElement = document.querySelector('.error');

// Adicione a classe .stop-blink após 5 segundos
setTimeout(function() {
  errorElement.style.animation = 'none ';
  errorElement.style.opacity = '0 ';
}, 3000);
