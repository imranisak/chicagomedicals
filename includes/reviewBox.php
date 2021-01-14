<?php
if($isLoggedIn){
?>
<div class="stars">
    <h3>Write a review!</h3>
    <form action="/pages/clinics/post/addReview.php" method="post">
        <input class="star star-5" id="star-5" type="radio" name="star"/>
        <label class="star star-5" for="star-5"></label>
        <input class="star star-4" id="star-4" type="radio" name="star"/>
        <label class="star star-4" for="star-4"></label>
        <input class="star star-3" id="star-3" type="radio" name="star"/>
        <label class="star star-3" for="star-3"></label>
        <input class="star star-2" id="star-2" type="radio" name="star"/>
        <label class="star star-2" for="star-2"></label>
        <input class="star star-1" id="star-1" type="radio" name="star"/>
        <label class="star star-1" for="star-1"></label>
        <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'];?>" required>
        <textarea rows='10' cols='30' type="text" name="review" ></textarea>
        <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
        <input type="hidden" name="score" id="score" value="">
        <button type="submit" class="btn btn-primary">Add Review</button>
    </form>
</div>

<script type="text/javascript">
    $('.star').click(function(){
        var $this = $(this);
        var score=$this.attr('ID');
        score=score.slice(-1)
        $("#score").attr("value", score);
    });
</script>
<?php
}
else echo "<h3>You must be logged in to submit a review!</h3>";

?>