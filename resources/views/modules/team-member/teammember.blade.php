<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <title>Laravel 6 Import Export Excel with Heading using Laravel Excel 3.1 - MyNotePaper</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="text-center" style="margin: 20px 0px 20px 0px;">
        <a href="https://shouts.dev/" target="_blank"><img src="https://i.imgur.com/hHZjfUq.png"></a><br>
        <span class="text-secondary">Laravel 6 Import Export Excel with Heading using Laravel Excel 3.1</span>
    </div>
    <br/>

    <div class="clearfix">
        <div class="float-left">
            <form class="form-inline" action="{{team-member.import}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="imported_file"/>
                        <label class="custom-file-label">Choose file</label>
                    </div>
                </div>
                <button style="margin-left: 10px;" class="btn btn-info" type="submit">Import</button>
            </form>
        </div>
        <div class="float-right">
            <form action="{{team-member.import}}" enctype="multipart/form-data">
                <button class="btn btn-dark" type="submit">Export</button>
            </form>
        </div>
    </div>
    <br/>

    @if(count($members))
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ID</td>
                <td>user_id</td>
                <td>profile_photo</td>
                <td>employee_id</td>
                <td>password</td>
                <td>password_confirmation</td>
                <td>country_code</td>
                <td>phone_number</td>
                <td>department</td>
                <td>designation</td>
                <td>blood_group</td>
                <td>dob</td>
                <td>doj</td>
                <td>added_by</td>
                <td>banned</td>
            </tr>
            </thead>
            @foreach($members as $member)
                <tr>
                    <td>{{$member->id}}</td>
                    <td>{{$member->user_id}}</td>
                    <td>{{$member->profile_photo}}</td>
                    <td>{{$member->employee_id}}</td>
                    <td>{{$member->password}}</td>
                    <td>{{$member->password_confirmation}}</td>
                    <td>{{$member->country_code}}</td>
                    <td>{{$member->phone_number}}</td>
                    <td>{{$member->department}}</td>
                    <td>{{$member->designation}}</td>
                    <td>{{$member->blood_group}}</td>
                    <td>{{$member->dob}}</td>
                    <td>{{$member->doj}}</td>
                    <td>{{$member->added_by}}</td>
                    <td>{{$member->banned}}</td>
                </tr>
            @endforeach
        </table>
    @endif

</div>

</body>
</html>