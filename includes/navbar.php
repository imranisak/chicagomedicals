<?php require "sessionInfo.php";
if($role!='admin'){
?>
<!-- USER NAVBAR-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?php $_SERVER['DOCUMENT_ROOT']?>/index.php">Chicago Medicals</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
      </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Clinics
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/pages/clinics">All clinics</a>
                <a class="dropdown-item" href="/pages/clinics/addClinic.php">Add a clinis</a>
            </div>
        </li>
      <?php if($isLoggedIn){ ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/post/logout.php">Log out</a>
        </li>
      <?php 
      }
      else { ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/login.php">Log in</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/register.php">Register</a>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>
<?php } else{ ?>
    <!-- ADMIN NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/admin">Chicago Medicals Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#"> Users <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Comments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Clinics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/tags/tags.php">Tags</a>
            </li>
            <?php if($isLoggedIn){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/post/logout.php">Log out</a>
            </li>
            <?php
            }
            else { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/login.php">Log in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/register.php">Register</a>
            </li>
            <?php } ?>
            <?php if($isAdmin){?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin panel</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>
<?php }?>
