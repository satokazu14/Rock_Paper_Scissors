<?php
require_once './func.php';
require_once './config.php';

$your_hand = mt_rand(0,2);

$cn = new mysqli(HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}
$cn->set_charset("utf-8");

$query = "INSERT INTO rps(my_hand,your_hand) VALUES(?,?)";
$stmt = $cn->prepare($query);
$stmt->bind_param("ii", $_GET['hand'], $your_hand);
$stmt->execute();
$cn->close();

$cn = new mysqli(HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}
$cn->set_charset("utf-8");

$query = "SELECT * FROM rps";
$stmt = $cn->prepare($query);
$stmt->execute();
$row = fetch_all($stmt);
$cn->close();

$hand   = array('グー',  'チョキ','パー',);
$result = array('あいこ','負け',  '勝ち',);

$win = 0;
foreach($row as $val){
  if(($val['my_hand'] - $val['your_hand'] + 3) % 3 == 2){
    $win++;
  }
}

if($win !== 0){
  $win = count($row)/$win * 10;
}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
  <title>Document</title>
  </head>
  <body>
    <div class="container">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>自分の手</th>
            <th>相手の手</th>
            <th>勝敗</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($row as $val) : ?>
          <tr>
            <td><?php echo $hand[$val['my_hand']] ?></td>
            <td><?php echo $hand[$val['your_hand']] ?></td>
            <td><?php echo $result[($val['my_hand'] - $val['your_hand'] + 3) % 3]; ?></td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <p>勝率:<?php echo $win ?>%</p>
    </div>
  <script src="js/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>