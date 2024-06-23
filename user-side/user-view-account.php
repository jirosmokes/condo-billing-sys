<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>My Menu</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../user-style/user-view-account.css">
</head>
<body>
<input type="checkbox" id="check">
<label for="check">
 <i class="fas fa-bars" id="btn"></i>
 <i class="fas fa-times" id="cancel"></i>
</label>

<div class="sidebar">
 <header>DormHub</header>
 
 <a href="#" class="active">
  <i class="fas fa-qrcode"></i>
  <span>Dashboard</span>
 </a>

 <a href="#" onclick="showContent('profile')">
  <i class="fas fa-user-alt"></i>
  <span>Profile</span>
 </a>

 <a href="#" onclick="showContent('bills')">
  <i class="fa fa-money"></i>
  <span>Bills </span>
 </a>

 <a href="#">
  <i class="far fa-question-circle"></i>
  <span>About</span>
 </a>

 <a href="#">
  <i class="fa fa-gear"></i>
  <span>Settings</span>
 </a>

<a href="#">
    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
</a>


<div id="content" style="margin-left: 240px;">
  </div> 
 
<script>
 function showContent(section) {
  var contentArea = document.getElementById("content");

  if (section === 'profile') {
    
    contentArea.innerHTML = `<h2>Profile</h2><form>...</form>`; 
  } else if (section === 'bills') {
   
    contentArea.innerHTML = `<h2>Bills</h2><ul>...</ul>`; 
  }
 }
</script>

</body>
</html>
