function initRegisterPage() {
  const $form = $("#register-form");

  if (!$form.length) return;

  $form.off("submit");
  if ($form.data("validator")) {
    $form.validate().destroy();
  }

  $form.validate({
    rules: {
      name: { required: true, minlength: 2 },
      email: { required: true, email: true },
      password: { required: true, minlength: 8 },
      phone: { required: true },
      address: { required: true }
    },
    messages: {
      name: { required: "Please enter your name", minlength: "Name must be at least 2 characters" },
      email: { required: "Please enter your email", email: "Please enter a valid email" },
      password: { required: "Please enter a password", minlength: "Password must be at least 8 characters" },
      phone: { required: "Please enter your phone number" },
      address: { required: "Please enter your address" }
    },
    submitHandler: function() {
      const payload = {
        email: $form.find("input[name='email']").val(),
        password: $form.find("input[name='password']").val(),
        full_name: $form.find("input[name='name']").val() || null,
        phone_number: $form.find("input[name='phone']").val() || null,
        address: $form.find("input[name='address']").val() || null
      };

      console.log("REGISTER PAYLOAD:", payload);
      UserService.register(payload, function() {
        $form[0].reset();
      });
      return false;
    }
  });
}

