<!-- Password toggle -->
<script>
  document.querySelectorAll(".toggle").forEach(toggle => {
    toggle.addEventListener("click", () => {
      const input = toggle.closest(".input-group").querySelector(".password");
      if (input.type === "password") {
        input.type = "text";
        toggle.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        input.type = "password";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
      }
    });
  });
</script>

<!-- /.content-wrapper -->
<footer class="main-footer">
  <strong>&copy; 2014-<?= date('Y') ?> <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 3.2.0
  </div>
</footer>

</div> <!-- Close wrapper -->

<!-- Core Libraries -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script>$.widget.bridge('uibutton', $.ui.button);</script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Plugins -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../plugins/toastr/toastr.min.js"></script>
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>

<!-- DataTables JS -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>

<!-- AdminLTE Scripts -->
<script src="../../dist/js/adminlte.js"></script>
<script src="../../dist/js/demo.js"></script>
<script src="../../dist/js/pages/dashboard.js"></script>

<!-- Initialize DataTables -->
<script>
  function initializeTable(selector, columnDefs) {
    const table = $(selector).DataTable({
      responsive: true,
      lengthChange: true,
      autoWidth: false,
      ordering: true,
      info: true,
      paging: true,
      searching: true,
      columnDefs: columnDefs,
      buttons: [
        "copy", "csv", "excel", "pdf", "print", "colvis",
        {
          text: 'Reset',
          action: function () {
            location.reload();
          }
        }
      ]
    });

    table.buttons().container().appendTo(selector + '_wrapper .col-md-6:eq(0)');
  }

  $(function () {
    // Initialize both tables
    if ($('#userTable').length) {
      initializeTable('#userTable', [{ orderable: false, targets: [4, 8] }]);
    }

    if ($('#studentTable').length) {
      initializeTable('#studentTable', [{ orderable: false, targets: [3, 6] }]);
    }

    bsCustomFileInput.init();

    window.openDeleteModal = function (id) {
      var modal = new bootstrap.Modal(document.getElementById('deleteModal' + id));
      modal.show();
    };
  });
</script>

<!-- jQuery Validation and Date Picker -->
<script>
  $(function () {
    $.validator.addMethod('pattern', function (value, element, param) {
      return this.optional(element) || param.test(value);
    }, 'Invalid format.');

    $('#userForm').validate({
      rules: {
        first_name: { required: true, minlength: 3, pattern: /^[A-Za-z\s'-]+$/ },
        last_name: { required: true, minlength: 3, pattern: /^[A-Za-z\s'-]+$/ },
        email: { required: true, email: true },
        phone_no: { required: true, digits: true, minlength: 10, maxlength: 10, pattern: /^[6-9]\d{9}$/ },
        password: { required: true, minlength: 8, pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}$/ },
        confirm_password: { required: true, equalTo: '#password' },
        DOB: { required: true, date: true },
        gender: { required: true },
        'hobby[]': { required: true, minlength: 1 },
        address: { required: true, minlength: 10, pattern: /^[A-Za-z0-9\s,.'-]{10,}$/ },
        country: { required: true },
        image_path: {
          required: function () {
            return window.location.pathname.includes('create.php');
          },
          extension: "jpg|jpeg|png|gif"
        }
      },
      messages: {
        first_name: {
          required: "Please enter your first name",
          minlength: "Minimum 3 characters",
          pattern: "Letters, spaces, apostrophes, and hyphens only."
        },
        last_name: {
          required: "Please enter your last name",
          minlength: "Minimum 3 characters",
          pattern: "Letters, spaces, apostrophes, and hyphens only."
        },
        email: "Please enter a valid email",
        phone_no: {
          required: "Enter phone number",
          digits: "Digits only",
          minlength: "Must be 10 digits",
          maxlength: "Must be 10 digits",
          pattern: "Starts with 6-9"
        },
        password: {
          required: "Enter password",
          minlength: "Min 8 characters",
          pattern: "Include upper, lower, number & special char"
        },
        confirm_password: {
          required: "Confirm password",
          equalTo: "Passwords do not match"
        },
        DOB: "Select your birthdate",
        gender: "Select a gender",
        'hobby[]': "Select at least one hobby",
        address: {
          required: "Enter your address",
          minlength: "Min 10 characters",
          pattern: "Invalid address format"
        },
        country: "Select a country",
        image_path: {
          required: "Please upload a profile image",
          extension: "Only image files allowed (jpg, jpeg, png, gif)"
        }
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
        element.closest('.form-group').find('.bg-light').addClass('is-invalid');
      },
      highlight: function (element) {
        $(element).addClass('is-invalid');
        $(element).closest('.form-group').find('.bg-light').addClass('is-invalid');
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
        $(element).closest('.form-group').find('.bg-light').removeClass('is-invalid').addClass('is-valid');
      }
    });

    $('#dateofbirth, #dobPicker').datetimepicker({
      format: 'YYYY-MM-DD',
      useCurrent: false,
      maxDate: moment().subtract(18, 'years'),
      minDate: moment().subtract(100, 'years'),
      defaultDate: "<?= !empty($formData['DOB']) ? date('Y-m-d', strtotime($formData['DOB'])) : '' ?>"
    });

    $('.custom-file-input').on('change', function () {
      var fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').html(fileName);
    });
  });
</script>

<?php if (!empty($message)) : ?>
<script>
  $(function () {
    toastr.options = {
      closeButton: true,
      progressBar: true,
      timeOut: 3000,
      positionClass: "toast-top-right"
    };
    <?php if ($status === 'success') : ?>
      toastr.success("<?= addslashes($message) ?>");
    <?php else : ?>
      toastr.error("<?= addslashes($message) ?>");
      <?php foreach ($errors as $err) : ?>
        toastr.error("<?= addslashes($err) ?>");
      <?php endforeach; ?>
    <?php endif; ?>
  });
</script>
<?php endif; ?>

<!-- Inline Style for Validation -->
<style>
  .is-valid { border: 2px solid #28a745; }
  .is-invalid { border: 2px solid #dc3545; }
</style>
</body>
</html>
