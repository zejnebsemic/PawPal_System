function initLoginPage() {
  const $form = $("#login-form");

  if (!$form.length) return;

  $form.off("submit");
  if ($form.data("validator")) {
    $form.validate().destroy();
  }

  $form.validate({
    rules: {
      email: { required: true, email: true },
      password: { required: true }
    },
    messages: {
      email: { required: "Please enter your email", email: "Please enter a valid email" },
      password: { required: "Please enter your password" }
    },
    submitHandler: function() {
      const payload = {
        email: $form.find("input[name='email']").val(),
        password: $form.find("input[name='password']").val()
      };

      console.log("LOGIN PAYLOAD:", payload);
      UserService.login(payload);
      return false;
    }
  });
}

