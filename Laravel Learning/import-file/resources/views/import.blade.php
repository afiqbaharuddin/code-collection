<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Import File Function</title>
  </head>

  <body>
    @if(session('success'))
      <div>{{session('success')}}</div>
    @endif

    <form class="" action="{{route('import')}}" method="post" enctype="multipart/form-data">
      @csrf
      <input type="file" name="file" value="">
      <button type="submit" name="submit" class="btn btn-primary">Import File</button>
    </form>
  </body>
</html>
