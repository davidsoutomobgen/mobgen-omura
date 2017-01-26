<div class="content-wrapper" style="text-align:center">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>404 Error Page</h1>
    </section>

    <!-- Main content -->
    <section class="content" style="">
      <div class="error-page">
        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
          <p>
            <?php
            if (isset($message))
                echo $message;
            else
                echo 'We could not find the page you were looking for. Are you trying aleatory links? :)';
            ?>
          </p>
          <p>Please contact us if you think this is a server error. Thank you.</p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
  </div>
