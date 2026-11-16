/* Atelier Tang - main.js
   Handles the navigation menu and client-side form validation.
   The site remains usable when JavaScript is unavailable. */

(function () {
  'use strict';

  // Let CSS know that JavaScript is available.
  document.documentElement.classList.add('js');

  /* ---------- Navigation menu ---------- */
  var toggle = document.querySelector('.nav-toggle');
  var menu = document.getElementById('primary-menu');

  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      var isOpen = menu.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Close the menu after link activation or when Escape is pressed.
    menu.addEventListener('click', function (event) {
      if (event.target.tagName === 'A') {
        menu.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && menu.classList.contains('is-open')) {
        menu.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.focus();
      }
    });
  }

  /* ---------- Form validation ---------- */
  var form = document.getElementById('booking-form');
  if (!form) { return; }

  function errorElement(field) {
    return document.getElementById('error-' + field.id);
  }

  function validationMessage(field) {
    var value = field.value.trim();

    if (field.type === 'checkbox') {
      return field.checked ? '' : 'Devi acconsentire al trattamento dei dati.';
    }
    if (field.required && value === '') {
      return field.tagName === 'SELECT' ? 'Scegli un servizio.' : 'Questo campo è obbligatorio.';
    }
    if (field.id === 'full-name' && value.length < 2) {
      return 'Inserisci almeno 2 caratteri.';
    }
    if (field.id === 'full-name' && !/^[A-Za-zÀ-ÖØ-öø-ÿ' .-]+$/.test(value)) {
      return 'Il nome può contenere solo lettere, spazi, apostrofi e trattini.';
    }
    if (field.id === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) {
      return 'Inserisci un indirizzo email valido, ad esempio nome@dominio.it.';
    }
    if (field.id === 'phone' && value !== '' && !/^[0-9+ ]{6,20}$/.test(value)) {
      return 'Usa da 6 a 20 caratteri tra numeri, spazi e +.';
    }
    if (field.id === 'preferred-date' && value !== '') {
      var today = new Date();
      today.setHours(0, 0, 0, 0);
      var dateParts = value.split('-');
      var selectedDate = new Date(Number(dateParts[0]), Number(dateParts[1]) - 1, Number(dateParts[2]));
      if (selectedDate < today) { return 'Scegli una data a partire da oggi.'; }
    }
    if (field.id === 'message' && (value.length < 10 || value.length > 1000)) {
      return 'Il messaggio deve avere da 10 a 1000 caratteri.';
    }
    return '';
  }

  function validateField(field) {
    var message = validationMessage(field);
    var error = errorElement(field);
    if (error) { error.textContent = message; }
    if (message) {
      field.setAttribute('aria-invalid', 'true');
    } else {
      field.removeAttribute('aria-invalid');
    }
    return message === '';
  }

  var fields = form.querySelectorAll('input:not([type="hidden"]), select, textarea');

  Array.prototype.forEach.call(fields, function (field) {
    field.addEventListener('blur', function () { validateField(field); });
    // Revalidate the field as the user corrects an error.
    field.addEventListener('input', function () {
      if (field.getAttribute('aria-invalid') === 'true') { validateField(field); }
    });
  });

  form.addEventListener('submit', function (event) {
    var firstInvalidField = null;
    Array.prototype.forEach.call(fields, function (field) {
      if (!validateField(field) && !firstInvalidField) { firstInvalidField = field; }
    });
    if (firstInvalidField) {
      event.preventDefault();
      firstInvalidField.focus();
    }
  });

  form.addEventListener('reset', function () {
    Array.prototype.forEach.call(fields, function (field) {
      field.removeAttribute('aria-invalid');
      var error = errorElement(field);
      if (error) { error.textContent = ''; }
    });
  });
})();
