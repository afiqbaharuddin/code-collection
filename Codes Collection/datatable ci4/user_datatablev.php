<!DOCTYPE html>
<html lang="en">
  <head>
    <style media="screen">
    .button-container {
      justify-content: flex-end;
      float: right;
      }
    </style>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
      <title><?= $title; ?></title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css" />
  </head>

  <body>
      <!-- Content -->
      <div class="container">

        <h4 style="margin-top:40px;text-align:center">Datatable</h4>
          <div class="row">
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header">
                        <a href="<?php echo base_url(); ?>">Home</a>
                          \User List

                          <div class="button-container">
                            <a href="<?php echo base_url(); ?>insert_user">
                              <button  class="btn btn-primary btn-sm ">Insert User</button>
                            </a>
                            <a href="<?= site_url('export_csv') ?>">
                              <button class="btn btn-info btn-sm ">Download CSV</button>
                            </a>
                          </div>
                      </div>
                      <div class="card-body">
                          <table id="user-table" class="table table-striped table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <td>No</td>
                                      <td>Name</td>
                                      <td>Email</td>
                                      <td>Hobby</td>
                                      <td>Edit</td>
                                  </tr>
                              </thead>
                              <tbody>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- /.Content -->

      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

      <script type="text/javascript">
          $(document).ready(function() {
              var table = $('#user-table').DataTable({
                  "processing": true,
                  "serverSide": true,
                  "order": [],
                  "ajax": {
                      "url": "<?php echo site_url('User_Datatable/list') ?>",
                      "type": "POST"
                  },
                  "columnDefs": [{
                      "targets": [],
                      "orderable": false,
                  }, ],
              });
          });
      </script>

  </body>
</html>
