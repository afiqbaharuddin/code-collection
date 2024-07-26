<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>File Upload Progress Bar</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
  </head>

  <body>
    <div class="container">
      <div class="mt-4">
        <h3 align="center">File Upload with Progress Bar</h3>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">File Upload with Progress Bar</h3>
        </div>
        <div class="panel-body">
          <div class="mt-4">
            <form class="" action="{{route('upload')}}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-md-3" align="right">
                  <h4>Select Image</h4>
                </div>
                <div class="col-md-6">
                  <input type="submit" name="upload" value="Upload" class="btn btn-success">
                </div>
              </div>
            </form>

            <div class="mt-4">
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                  0%
                </div>
              </div>
              <br/>
              <div id="succcess">

              </div>
              <br/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

<script>
  $(document).ready(function(){

    $('form').ajaxForm({

    })
  });
</script>
