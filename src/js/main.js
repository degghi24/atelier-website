/* Atelier Tang - main.js
   Progressive enhancement only: the site works without JavaScript, without CSS
   and in text browsers, because every form is also validated on the server.
   - menu button on small screens, created here so it never appears without JavaScript;
   - accessible client-side validation with inline errors and an error summary;
   - focus on outcome messages after a form submission;
   - FAQ answers opened before printing. */

/* Runs immediately (the script is loaded in <head>) so CSS can hide the small-screen
   menu before the first paint, avoiding a layout shift. */
document.documentElement.classList.add('js');

(function () {
  'use strict';

  /* ---------- Navigation menu (small screens) ---------- */
  /* The open state is stored only in aria-expanded: the stylesheet reads the same
     attribute, so what screen readers announce always matches what is shown. */
  function initMenu() {
    var menu = document.getElementById('primary-menu');
    if (!menu) { return; }
    // Without the screen stylesheet the menu is always visible: no button is needed.
    if (window.getComputedStyle(menu.parentNode).display !== 'flex') { return; }

    var toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'nav-toggle';
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-controls', 'primary-menu');
    var icon = document.createElement('span');
    icon.className = 'menu-icon';
    icon.setAttribute('aria-hidden', 'true');
    for (var i = 0; i < 3; i++) { icon.appendChild(document.createElement('span')); }
    toggle.appendChild(icon);
    toggle.appendChild(document.createTextNode('Menu'));
    menu.parentNode.insertBefore(toggle, menu);

    function isOpen() { return toggle.getAttribute('aria-expanded') === 'true'; }
    function setOpen(open) { toggle.setAttribute('aria-expanded', open ? 'true' : 'false'); }

    toggle.addEventListener('click', function () { setOpen(!isOpen()); });

    // Close the menu after following an in-page link, or with the Escape key.
    menu.addEventListener('click', function (event) {
      if (event.target.closest && event.target.closest('a')) { setOpen(false); }
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && isOpen()) {
        setOpen(false);
        toggle.focus();
      }
    });
  }

  /* ---------- Form validation ---------- */
  var EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  function valueOf(field) {
    // Passwords are compared exactly; other values ignore surrounding spaces.
    return field.type === 'password' ? field.value : field.value.trim();
  }

  function matchesPattern(pattern, value) {
    var expression;
    try {
      expression = new RegExp('^(?:' + pattern + ')$', 'u');
    } catch (error) {
      return true; // Older browsers: the server still checks the value.
    }
    return expression.test(value);
  }

  /* Returns the message to show, or an empty string when the field is valid.
     Messages come from data attributes written by the server. */
  function validationMessage(field) {
    var required = field.getAttribute('data-msg-required') || 'Compila questo campo.';
    var invalid = field.getAttribute('data-msg-invalid') || 'Controlla il valore inserito.';
    var value = valueOf(field);

    if (field.type === 'checkbox') {
      return field.required && !field.checked ? required : '';
    }
    if (field.validity && field.validity.badInput) { return invalid; }
    if (value === '') { return field.required ? required : ''; }

    var minLength = Number(field.getAttribute('minlength') || 0);
    var maxLength = Number(field.getAttribute('maxlength') || 0);
    if (minLength && value.length < minLength) { return invalid; }
    if (maxLength && value.length > maxLength) { return invalid; }

    var pattern = field.getAttribute('pattern');
    if (pattern && !matchesPattern(pattern, value)) { return invalid; }
    if (field.type === 'email' && !EMAIL_PATTERN.test(value)) { return invalid; }

    if (field.type === 'date') {
      // Dates use the ISO format YYYY-MM-DD, so string comparison is chronological.
      if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) { return invalid; }
      if (field.min && value < field.min) { return invalid; }
      if (field.max && value > field.max) { return invalid; }
    }

    var matchId = field.getAttribute('data-match');
    if (matchId) {
      var other = document.getElementById(matchId);
      if (other && other.value !== field.value) { return invalid; }
    }
    return '';
  }

  function showFieldError(field, message) {
    var error = document.getElementById('error-' + field.id);
    if (error) {
      error.textContent = '';
      if (message) {
        var prefix = document.createElement('span');
        prefix.className = 'sr-text';
        prefix.textContent = 'Errore: ';
        error.appendChild(prefix);
        error.appendChild(document.createTextNode(message));
      }
    }
    if (message) {
      field.setAttribute('aria-invalid', 'true');
    } else {
      field.removeAttribute('aria-invalid');
    }
  }

  function validateField(field) {
    var message = validationMessage(field);
    showFieldError(field, message);
    return message;
  }

  /* Builds the error summary when the server has not printed one. The heading level
     comes from data-summary-heading, so it fits the page outline. */
  function createSummary(form) {
    var summary = document.createElement('div');
    summary.className = 'error-summary';
    summary.id = 'riepilogo-errori';
    summary.tabIndex = -1;
    var title = document.createElement(form.getAttribute('data-summary-heading') || 'h2');
    title.className = 'error-summary-title';
    summary.appendChild(title);
    summary.appendChild(document.createElement('ul'));
    form.insertBefore(summary, form.firstChild);
    return summary;
  }

  function updateSummary(summary, problems) {
    var title = summary.querySelector('.error-summary-title');
    var list = summary.querySelector('ul');
    list.textContent = '';
    problems.forEach(function (problem) {
      var item = document.createElement('li');
      var link = document.createElement('a');
      link.href = '#' + problem.field.id;
      link.textContent = problem.message;
      item.appendChild(link);
      list.appendChild(item);
    });
    title.textContent = problems.length === 1
      ? 'Il modulo contiene 1 errore'
      : 'Il modulo contiene ' + problems.length + ' errori';
    summary.hidden = problems.length === 0;
  }

  function initForm(form) {
    var summary = form.querySelector('.error-summary');
    var fields = form.querySelectorAll('input:not([type="hidden"]), select, textarea');

    // Custom accessible messages replace the native browser bubbles.
    form.setAttribute('novalidate', 'novalidate');

    Array.prototype.forEach.call(fields, function (field) {
      // Validate when the user leaves a field they have filled in; empty required
      // fields are reported on submit, so tabbing through the form shows no errors.
      field.addEventListener('blur', function () {
        if (field.type === 'checkbox') { return; }
        if (valueOf(field) !== '' || field.getAttribute('aria-invalid') === 'true') {
          validateField(field);
        }
      });
      // Remove an error as soon as the user fixes it.
      var liveEvent = field.type === 'checkbox' || field.tagName === 'SELECT' ? 'change' : 'input';
      field.addEventListener(liveEvent, function () {
        if (field.getAttribute('aria-invalid') === 'true') { validateField(field); }
      });
    });

    form.addEventListener('submit', function (event) {
      var problems = [];
      Array.prototype.forEach.call(fields, function (field) {
        var message = validateField(field);
        if (message) { problems.push({ field: field, message: message }); }
      });
      if (!problems.length) {
        if (summary) { summary.hidden = true; }
        return;
      }
      event.preventDefault();
      if (!summary) { summary = createSummary(form); }
      updateSummary(summary, problems);
      summary.focus();
    });
  }

  /* Error summary links move focus to the field and keep its label in view. */
  function initSummaryLinks() {
    document.addEventListener('click', function (event) {
      var link = event.target.closest ? event.target.closest('.error-summary a') : null;
      if (!link) { return; }
      var target = document.getElementById(link.getAttribute('href').slice(1));
      if (!target) { return; }
      event.preventDefault();
      var label = document.querySelector('label[for="' + target.id + '"]');
      (label || target).scrollIntoView({ block: 'center' });
      target.focus({ preventScroll: true });
    });
  }

  /* ---------- Outcome messages ---------- */
  function focusOutcome() {
    var outcome = document.querySelector('[data-focus-on-load="true"]:not([hidden])');
    if (outcome) { outcome.focus(); }
  }

  /* ---------- Printing ---------- */
  function initPrint() {
    window.addEventListener('beforeprint', function () {
      Array.prototype.forEach.call(document.querySelectorAll('details:not([open])'), function (details) {
        details.open = true;
        details.setAttribute('data-print-opened', 'true');
      });
    });
    window.addEventListener('afterprint', function () {
      Array.prototype.forEach.call(document.querySelectorAll('details[data-print-opened]'), function (details) {
        details.open = false;
        details.removeAttribute('data-print-opened');
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initMenu();
    Array.prototype.forEach.call(document.querySelectorAll('form[data-validate]'), initForm);
    initSummaryLinks();
    focusOutcome();
    initPrint();
  });
})();
