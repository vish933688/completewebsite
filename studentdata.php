<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vishal Public School</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <link rel="manifest" href="favicon/site.webmanifest">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/style%1.css">
  <script src="js/script.js"></script>

</head>

<body>
  <div class="ht1">
    /*<?php include 'head.html' ?>*/
  </div>

  <div class="table1">
    <div class="stu1">
      <h1 class=stu-head>Welcome to Our Page</h1>
    </div>
    <div class="tu1">
      <table>
        <tr>
          <td>Name</td>
          <td>Class</td>
          <td>Age</td>
          <td>Address</td>
          <td>ContactNo</td>
        </tr>
        <tr>
          <td> ggg</td>
          <td>hhh </td>
          <td> hh</td>
          <td>hh </td>
          <td>hh </td>

        </tr>
        <tr>
          <td> hh</td>
          <td>kk </td>
          <td> h</td>
          <td> o</td>
          <td> ii</td>
        </tr>
      </table>
      <section>
        <h1>GeeksForGeeks</h1>
        <!-- TABLE CONSTRUCTION -->
        <table>
          <tr>
            <th>GFG UserHandle</th>
            <th>Practice Problems</th>
            <th>Coding Score</th>
            <th>GFG Articles</th>
          </tr>
          <!-- PHP CODE TO FETCH DATA FROM ROWS -->
          <?php 
                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
          <tr>
            <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
            <td><?php echo $rows['username'];?></td>
            <td><?php echo $rows['problems'];?></td>
            <td><?php echo $rows['score'];?></td>
            <td><?php echo $rows['articles'];?></td>
          </tr>
          <?php
                }
            ?>
        </table>
      </section>
    </div>
  </div>


  <?php include 'footer.html'; ?>
</body>

</html>