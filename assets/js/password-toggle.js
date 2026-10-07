/**
 * password-toggle.js
 * Eye button: show / hide the password in the input next to it.
 */
document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
    var input = button.parentElement.querySelector('input');
    var icon  = button.querySelector('img');
    if (!input || !icon) return;

    button.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        icon.src = show ? 'assets/img/eye-off.svg' : 'assets/img/eye.svg';
        button.setAttribute('aria-pressed', String(show));
        button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
});
