<?php 

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

  $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
  $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
  $details = trim(filter_input(INPUT_POST, 'details', FILTER_SANITIZE_SPECIAL_CHARS));

  if ($name == '' || $email == '' || $details == '') {
    echo 'Please fill in the required fields: Name, Email and/or Details';
    exit;
  }
  if ($_POST['address'] != '') {
    echo 'Bad Form Input!';
    exit;
  }

  echo "<pre>";
  $email_body = "";
  $email_body .= "Name: " . $name . "\n";
  $email_body .= "Email: " . $email . "\n";
  $email_body .= "Details: " . $details . "\n";
  echo $email_body;
  echo "</pre>";


  header('location:suggest.php?status=thanks');
}

$pageTitle = 'Suggest a Media Item';
$section = 'suggest';

include('inc/header.php'); 
?>

<div class="section page">
  <div class="wrapper">
    <h1>Suggest a Media Item</h1>
    <?php 
      if (isset($_GET['status']) && $_GET['status'] == 'thanks') {
          echo "<p>Thanks for the email. I will check out your suggestion shortly!</p>";
      } else {

    ?>
    <p>If something is missing, let me know! Send me an email.</p>
    <form method="post" action="suggest.php">
      <table>
        <tr>
          <th>
            <label for="name">Name</label>            
          </th>
          <td>
            <input type="text" id="name " name="name"> 
          </td>
        </tr>
        <tr>
          <th>
            <label for="email">email</label>            
          </th>
          <td>
            <input type="text" id="email " name="email"> 
          </td>
        </tr>   
        <tr>
          <th>
            <label for="details">Suggest Item Details</label>            
          </th>
          <td>
            <textarea name="details" id="details"></textarea>
          </td>
        </tr>
        <tr style="display: none;">
          <th>
            <label for="address">address</label>            
          </th>
          <td>
            <input type="text" id="address " name="address"> 
            <p>Please leave this field blank</p>
          </td>
        </tr> 
      </table>
      <input type="submit" value="send">
    </form>
  <?php } ?>
  </div>
</div>

<?php include('inc/footer.php'); ?>