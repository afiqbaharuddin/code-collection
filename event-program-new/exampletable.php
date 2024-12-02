<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Example Pax Table using API</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">

    <!-- jQuery (required for DataTables) -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <style media="screen">
      table{
        width: 100%a;
        border-collapse: collapse;
      }
      table,th,td {
        border: 1px solid black;
      }
      th,td {
        padding: 10px;
        text-align: center;
      }
    </style>
  </head>

  <body>
    <h1>List Pax Using API</h1>

    <div class="table-responsive">
      <table id='paxtable'>
        <thead class="table w-100 nowrap">
          <tr>
            <th>ID</th>
            <th>Nickname</th>
            <th>Email</th>
            <th>Telephone Number</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>

    <script>
      // Fetch data from the API
      fetch('http://synergy_enhanced.test/event-program/api.php')  // Adjust URL to match the path to your API
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#paxtable tbody');

            // Loop through the fetched data and create table rows
            data.forEach(pax => {
              const row = document.createElement('tr');

              // Create cells for ID, Nickname, Email, and Telephone
              const idCell = document.createElement('td');
              idCell.textContent = pax.id;
              row.appendChild(idCell);

              const nicknameCell = document.createElement('td');
              nicknameCell.textContent = pax.nickname;
              row.appendChild(nicknameCell);

              const emailCell = document.createElement('td');
              emailCell.textContent = pax.email;
              row.appendChild(emailCell);

              const telCell = document.createElement('td');
              telCell.textContent = pax.tel;
              row.appendChild(telCell);

              // Append the row to the table body
              tableBody.appendChild(row);
            });

            $(document).ready(function(){
              $('#paxtable').DataTable({
                "paging"    : true,
                "searching" : true,
                "searching" : true,
                "ordering"  : true,
                "info"      : true
              });
            });
          })
          .catch(error => console.error('Error fetching data:', error));
    </script>
  </body>
</html>
