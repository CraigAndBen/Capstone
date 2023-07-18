$(document).ready(function() {
  $('#togglePassword').click(function() {
    var passwordInput = $('#password');
    var type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
    passwordInput.attr('type', type);
    $(this).toggleClass('fa-eye-slash');
  });
});

