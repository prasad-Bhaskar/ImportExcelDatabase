<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

        <!-- Styles -->
        
    </head>
    <body class="antialiased">
        <div class="relative sm:flex  container mt-5">
          <form id="imageUploadForm" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="file" name="images[]" id="images" multiple="multiple" class="form-control my-2">
            <input type="button" value="Upload" class="btn btn-primary text-dark" id="upload">
          </form>
        </div>
    </body>
    <script type="text/javascript">
      $('document').ready(function(){
        // console.log("test");
        $('#upload').click(function(){
          var formData = new FormData($("#imageUploadForm")[0]);

          $.ajax({
            url: '{{route("upload")}}', // Specify your server-side script to handle the file uploads
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                // Handle the response from the server (if needed)
            },
            error: function(error) {
                console.error(error);
                // Handle the error (if any)
            }
        });
        })
      })
    </script>
</html>
