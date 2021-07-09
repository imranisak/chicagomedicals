<?php require "sessionInfo.php";
if($role!='admin'){
?>
<!-- USER NAVBAR-->
<DIV STYLE="text-align: center; background-color: red;">
    <h3>THIS APP IS STILL IN THE EARLY TESTING PHASE - NOT FOR USE...</h3>
    <p style="margin:0px;">That said, you can take it for a spin if you want - if you find bugs - and you most likely will - send me a mail to <a href="mailto:info@imranisak.com">info@imranisak.com</a></p>
</DIV>
<div>
    <div class="col-md-10 offset-md-1">
      <!--These DIVs are closed in the footer-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="/"><img src="/media/pictures/logo_200x100.png" id="logo"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
          </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Clinics
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/pages/clinics">All clinics</a>
                    <a class="dropdown-item" href="/pages/clinics/addClinic.php">Add a clinic</a>
                </div>
            </li>
          <?php if($isLoggedIn){ ?>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" d>
                      <img src="<?php echo $profilePicture; ?>" width="40" height="40">
                      <?php echo $name." ".$surname; ?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="/pages/users/editProfile.php?ID=<?php echo $id ?>">Edit profile</a>
                      <a class="dropdown-item" href="/pages/users/post/logout.php">Log out</a>
                  </div>
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
        <a class="navbar-brand" href="/admin"><img src="/media/pictures/logo_200x100.png" id="logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/admin/users/">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/comments">Comments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/clinics/">Clinics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/tags/tags.php">Tags</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/reports/">Reports</a>
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
