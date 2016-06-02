<div>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Experience</th>
            <th>Salary</th>
        </tr>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->fname }}</td>
            <td>{{ $row->lname }}</td>
            <td>{{ $row->experience }}</td>
            <td>{{ $row->salary }}</td>
        </tr>
        @endforeach
    </table>


</div>