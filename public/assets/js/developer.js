 $(function () {
  // Form sticky actions
  
  // Select2 Country
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        // placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  }

  // Datepicker
  invoiceDateList = document.querySelectorAll('.date-picker');
  if (invoiceDateList) {
    invoiceDateList.forEach(function (invoiceDateEl) {
      invoiceDateEl.flatpickr({
        monthSelectorType: 'static'
      });
    });
  }

  //shortcut key
  $(document).on('keydown', function(event) {
    if (event.altKey && event.key === 'F1') {
        window.location.href = "./change_year"; 
    }
  });

  //Select all checkbox click rights permission
  const selectAll = document.querySelector('#selectAll'),
  checkboxList = document.querySelectorAll('[type="checkbox"]');
  selectAll.addEventListener('change', t => {
      checkboxList.forEach(e => {
        e.checked = t.target.checked;
      });
  });

  //password toggle
  $('#togglePassword').on('click',function() {
      var passwordField = $('#password');
      var icon = $(this).find('i');

      if (passwordField.attr('type') === 'password') {
          passwordField.attr('type', 'text');
          icon.removeClass('ti-eye-off').addClass('ti-eye');
      } else {
          passwordField.attr('type', 'password');
          icon.removeClass('ti-eye').addClass('ti-eye-off');
      }
  });

  
  //old password toggle
  $('#toggleOldPassword').on('click',function() {
      var passwordField = $('#oldpassword');
      var icon = $(this).find('i');

      if (passwordField.attr('type') === 'password') {
          passwordField.attr('type', 'text');
          icon.removeClass('ti-eye-off').addClass('ti-eye');
      } else {
          passwordField.attr('type', 'password');
          icon.removeClass('ti-eye').addClass('ti-eye-off');
      }
  });

   //confirm password toggle
   $('#toggleCnfPassword').on('click',function() {
    var passwordField = $('#cnfpassword');
    var icon = $(this).find('i');

    if (passwordField.attr('type') === 'password') {
        passwordField.attr('type', 'text');
        icon.removeClass('ti-eye-off').addClass('ti-eye');
    } else {
        passwordField.attr('type', 'password');
        icon.removeClass('ti-eye').addClass('ti-eye-off');
    }
});

});


