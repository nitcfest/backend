<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="{{URL::to('/')}}/bower_components/purecss/pure-nr-min.css" rel="stylesheet">
    <title>{{$event->name}}</title>

    <style>
      body{
        font-size: 10px;
      }
    </style>

    @section('head')
    @show
</head>

<body>

  @if(isset($teams))
    <h2>{{$event->name}}</h2>
    @if(count($teams) > 0)
      <div id="print-section">
      @foreach($teams as $team)
        <h3>{{$team->event_code.$team->team_code}}</h3>
        <table class="pure-table pure-table-bordered">
            <thead>
                <tr>
                    <th style="width: 3%">#</th>
                    <th style="width: 10%">Ragam ID</th>
                    <th style="width: 20%">Name</th>
                    <th style="width: 20%">Email</th>
                    <th style="width: 12%">Phone</th>
                    <th style="width: 36%">College</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($team->team_members as $team_member)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{Config::get('app.id_prefix').$team_member->details->id }}</td>
                       <td>{{$team_member->details->name }}</td>
                       <td>{{$team_member->details->email }}</td>
                       <td>{{$team_member->details->phone }}</td>
                       <td>{{$team_member->details->college->name }}</td>
                   </tr>
                @endforeach
            </tbody>
        </table> 
      @endforeach
      
      </div>
    @else
    <h4>No confirmed teams for this event.</h4>
    @endif
  @endif

  <br>
  <p>Start On-Spot Registrations from: {{$on_spot}}</p>  

  <script>
    print();
  </script>
</body>

</html>
