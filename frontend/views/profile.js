function initProfilePage() {
  if (!UserService.requireLogin()) return;
  
  const user = UserService.getCurrentUser();
  if (user) {
    $('#full_name').val(user.full_name || '');
    $('#email').val(user.email || '');
    $('#phone').val(user.phone_number || '');
    $('#address').val(user.address || '');
    $('#city').val(user.city || '');
    $('#date_of_birth').val(user.date_of_birth || '');
  }

  UserService.initProfile();
}

