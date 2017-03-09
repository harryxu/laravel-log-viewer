<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel log viewer</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link href="//cdn.bootcss.com/codemirror/5.24.2/codemirror.min.css" rel="stylesheet">

    <style>
      .table-container {
        height: 100vh;
        display: flex;
        flex-direction: column;
      }

      .log-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
      }

      h1 {
        font-size: 1.5em;
        margin-top: 0px;
      }
      .stack {
        font-size: 0.85em;
      }
      .date {
        min-width: 75px;
      }
      .text {
        word-break: break-all;
      }
      a.llv-active {
        z-index: 2;
        background-color: #f5f5f5;
        border-color: #777;
      }

    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <h1><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Laravel Log Viewer</h1>
          <p class="text-muted"><i>by Rap2h</i></p>
          <div class="list-group">
            @foreach($files as $file)
              <a href="?l={{ base64_encode($file) }}" class="list-group-item @if ($current_file == $file) llv-active @endif">
                {{$file}}
              </a>
            @endforeach
          </div>
        </div>
        <div class="col-sm-9 col-md-10 table-container">
          @if ($rawlog === null)
            <div>
              Log file >50M, please download it.
            </div>
          @else
            <div id="log-content" class="log-content">

            </div>
          @endif
          <div>
            <a href="?dl={{ base64_encode($current_file) }}"><span class="glyphicon glyphicon-download-alt"></span> Download file</a>
            -
            <a id="delete-log" href="?del={{ base64_encode($current_file) }}"><span class="glyphicon glyphicon-trash"></span> Delete file</a>

            -

            <label><input type="checkbox" id="line-wrap" /> wrap</label>

            -

            <button type="button" id="btn-to-bottom">bottom</button>
            <button type="button" id="btn-to-top">top</button>
          </div>
        </div>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/codemirror/5.24.2/codemirror.min.js"></script>
    <script>
      var cm = null

      function resizeEditor() {
        var h = $('#log-content').height();
        cm.setSize('100%', h);
      }

      $(document).ready(function(){

        var rawlog = {!! json_encode($rawlog) !!};

        cm = CodeMirror($('#log-content').get(0), {
          value: rawlog,
          lineNumbers: true,
          lineWrapping: false,
          readOnly: true
        });

        cm.scrollIntoView({line: cm.lineCount()-1});

        $('#btn-to-bottom').click(function() {
          cm.scrollIntoView({line: cm.lineCount()-1});
        });
        $('#btn-to-top').click(function() {
          cm.scrollIntoView({line: 0});
        });

        resizeEditor();

        $(window).on('resize', function() {
          resizeEditor();
        });

        $('#line-wrap').change(function() {
          var lineWrap = $(this).is(':checked');
          cm.setOption('lineWrapping', lineWrap);
        });

        $('#delete-log').click(function(){
          return confirm('Are you sure?');
        });
      });
    </script>
  </body>
</html>
